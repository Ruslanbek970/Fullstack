<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Meme;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class MemeController extends Controller
{
    public function create(): View
    {
        return view('pages.memes.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'category' => ['required', 'string', 'max:100'],
            'remote_url' => ['nullable', 'string', 'max:8192'],
            'upload' => ['nullable', 'file', 'max:51200', 'mimes:jpeg,jpg,png,gif,webp,mp4,webm'],
        ]);

        if (! $request->hasFile('upload') && empty(trim((string) ($validated['remote_url'] ?? '')))) {
            return back()
                ->withErrors(['upload' => 'Загрузите файл или укажите ссылку на медиа.'])
                ->withInput();
        }

        $media = $this->resolveMediaPayload($request, null);

        if (empty($media['path'])) {
            return back()
                ->withErrors(['upload' => 'Укажите рабочую ссылку (прямую на картинку/видео или страницу Google Картинок) или загрузите файл. Проверьте, что ссылка открывается в браузере.'])
                ->withInput();
        }

        $meme = Meme::create([
            'user_id' => $request->user()->id,
            'title' => $validated['title'],
            'category' => $validated['category'],
            'image' => $media['path'],
            'media_type' => $media['type'],
            'status' => Meme::STATUS_PENDING,
            'rejection_reason' => null,
            'published_at' => null,
        ]);

        return redirect()->route('memes.mine')->with('message', 'Мем отправлен на модерацию (#'.$meme->id.').');
    }

    public function edit(Request $request, Meme $meme): View
    {
        if (! $request->user()->can('meme.edit')) {
            abort(403);
        }
        if (! $this->userCanEditMeme($request, $meme)) {
            abort(403);
        }

        return view('pages.memes.edit', compact('meme'));
    }

    public function mine(Request $request): View
    {
        $memes = Meme::query()
            ->where('user_id', $request->user()->id)
            ->latest()
            ->paginate(20);

        return view('pages.memes.mine', compact('memes'));
    }

    public function moderation(Request $request): View
    {
        $q = trim((string) $request->input('q', ''));

        $memes = Meme::query()
            ->pending()
            ->with('user')
            ->when($q !== '', function ($query) use ($q) {
                $query->where('title', 'like', '%'.$q.'%');
            })
            ->latest()
            ->paginate(20)
            ->withQueryString();

        return view('pages.memes.moderation', compact('memes', 'q'));
    }

    public function show(Request $request, Meme $meme): View
    {
        if (! $meme->canBeViewedBy($request->user())) {
            abort(403);
        }

        $comments = $meme->comments()
            ->with('user')
            ->visibleFor($request->user())
            ->latest()
            ->get();

        $meme->loadCount(['likedBy', 'dislikedBy']);

        $liked = $request->user()
            ? $meme->likedBy()->where('user_id', $request->user()->id)->exists()
            : false;

        $disliked = $request->user()
            ? $meme->dislikedBy()->where('user_id', $request->user()->id)->exists()
            : false;

        return view('pages.memes.show', compact('meme', 'comments', 'liked', 'disliked'));
    }

    public function update(Request $request, Meme $meme)
    {
        if (! $request->user()->can('meme.edit')) {
            abort(403);
        }
        if (! $this->userCanEditMeme($request, $meme)) {
            abort(403);
        }

        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'category' => ['required', 'string', 'max:100'],
            'remote_url' => ['nullable', 'string', 'max:8192'],
            'upload' => ['nullable', 'file', 'max:51200', 'mimes:jpeg,jpg,png,gif,webp,mp4,webm'],
        ]);

        $media = $this->resolveMediaPayload($request, $meme);

        if (empty($media['path'])) {
            return back()
                ->withErrors(['upload' => 'Укажите файл или ссылку, либо оставьте прежнее медиа без изменений.'])
                ->withInput();
        }

        $meme->update([
            'title' => $validated['title'],
            'category' => $validated['category'],
            'image' => $media['path'],
            'media_type' => $media['type'],
            'status' => Meme::STATUS_PENDING,
            'rejection_reason' => null,
            'published_at' => null,
        ]);

        return back()->with('message', 'Мем обновлён и снова отправлен на модерацию.');
    }

    public function destroy(Request $request, Meme $meme)
    {
        if (! $request->user()->can('meme.delete')) {
            abort(403);
        }

        if (! $this->userCanDeleteMeme($request, $meme)) {
            abort(403);
        }

        $this->deleteStoredMediaIfLocal($meme);
        $meme->delete();

        return back()->with('message', 'Мем удалён.');
    }

    public function publish(Request $request, Meme $meme)
    {
        if (! $request->user()->can('meme.publish')) {
            abort(403);
        }
        if ($meme->status !== Meme::STATUS_PENDING) {
            return back()->with('message', 'Можно принять только мем в статусе «на модерации».');
        }

        $meme->update([
            'status' => Meme::STATUS_PUBLISHED,
            'rejection_reason' => null,
            'published_at' => now(),
        ]);

        return back()->with('message', 'Мем опубликован.');
    }

    public function reject(Request $request, Meme $meme)
    {
        if (! $request->user()->can('meme.reject')) {
            abort(403);
        }
        if ($meme->status !== Meme::STATUS_PENDING) {
            return back()->with('message', 'Можно отклонить только мем в статусе «на модерации».');
        }

        $validated = $request->validate([
            'rejection_reason' => ['nullable', 'string', 'max:1000'],
        ]);

        $meme->update([
            'status' => Meme::STATUS_REJECTED,
            'rejection_reason' => $validated['rejection_reason'] ?? null,
            'published_at' => null,
        ]);

        return back()->with('message', 'Мем отклонён.');
    }

    public function toggleLike(Request $request, Meme $meme)
    {
        if (! $request->user()->can('like.toggle')) {
            abort(403);
        }
        if (! $meme->isPublished()) {
            abort(403, 'Лайкать можно только опубликованные мемы.');
        }

        $user = $request->user();
        if ($meme->likedBy()->where('user_id', $user->id)->exists()) {
            $meme->likedBy()->detach($user->id);
            $msg = 'Лайк снят.';
        } else {
            $meme->dislikedBy()->detach($user->id);
            $meme->likedBy()->attach($user->id);
            $msg = 'Лайк поставлен.';
        }

        return back()->with('message', $msg);
    }

    public function toggleDislike(Request $request, Meme $meme)
    {
        if (! $request->user()->can('like.toggle')) {
            abort(403);
        }
        if (! $meme->isPublished()) {
            abort(403, 'Дизлайкать можно только опубликованные мемы.');
        }

        $user = $request->user();
        if ($meme->dislikedBy()->where('user_id', $user->id)->exists()) {
            $meme->dislikedBy()->detach($user->id);
            $msg = 'Дизлайк снят.';
        } else {
            $meme->likedBy()->detach($user->id);
            $meme->dislikedBy()->attach($user->id);
            $msg = 'Дизлайк поставлен.';
        }

        return back()->with('message', $msg);
    }

    /**
     * @return array{path: ?string, type: string}
     */
    private function resolveMediaPayload(Request $request, ?Meme $existing): array
    {
        if ($request->hasFile('upload')) {
            $file = $request->file('upload');
            $mime = (string) $file->getMimeType();
            $isVideo = str_starts_with($mime, 'video');

            if ($existing) {
                $this->deleteStoredMediaIfLocal($existing);
            }

            $path = $file->store('memes', 'public');

            return [
                'path' => $path,
                'type' => $isVideo ? Meme::MEDIA_VIDEO : Meme::MEDIA_IMAGE,
            ];
        }

        $remote = trim((string) $request->input('remote_url', ''));
        if ($remote !== '') {
            if ($existing) {
                $this->deleteStoredMediaIfLocal($existing);
            }

            $normalized = $this->normalizeRemoteMediaUrl($remote);
            if ($normalized === '' || ! filter_var($normalized, FILTER_VALIDATE_URL)) {
                return [
                    'path' => null,
                    'type' => Meme::MEDIA_IMAGE,
                ];
            }

            $isVideo = (bool) preg_match('/\.(mp4|webm)(\?.*)?$/i', $normalized);

            return [
                'path' => $normalized,
                'type' => $isVideo ? Meme::MEDIA_VIDEO : Meme::MEDIA_IMAGE,
            ];
        }

        if ($existing && $existing->image) {
            return [
                'path' => $existing->image,
                'type' => $existing->media_type ?? Meme::MEDIA_IMAGE,
            ];
        }

        return [
            'path' => null,
            'type' => Meme::MEDIA_IMAGE,
        ];
    }

    /**
     * Вытаскивает прямую ссылку на картинку из страниц Google Картинок (параметр imgurl).
     */
    private function normalizeRemoteMediaUrl(string $url): string
    {
        $url = trim($url);
        if ($url === '') {
            return '';
        }

        if (preg_match('#google\.[^/]+/imgres#i', $url) || (str_contains($url, 'imgurl=') && str_contains($url, 'google'))) {
            $query = parse_url($url, PHP_URL_QUERY);
            if (is_string($query) && $query !== '') {
                parse_str($query, $params);
                if (! empty($params['imgurl'])) {
                    return trim(urldecode((string) $params['imgurl']));
                }
            }
        }

        return $url;
    }

    private function deleteStoredMediaIfLocal(Meme $meme): void
    {
        if (! $meme->image) {
            return;
        }
        if (str_starts_with($meme->image, 'http://') || str_starts_with($meme->image, 'https://')) {
            return;
        }

        Storage::disk('public')->delete($meme->image);
    }

    private function userCanEditMeme(Request $request, Meme $meme): bool
    {
        $user = $request->user();
        if ($user->hasRole(['admin', 'super-admin'])) {
            return true;
        }

        return $meme->user_id === $user->id;
    }

    private function userCanDeleteMeme(Request $request, Meme $meme): bool
    {
        $user = $request->user();
        if ($user->hasRole(['admin', 'super-admin'])) {
            return true;
        }

        return $meme->user_id === $user->id;
    }
}
