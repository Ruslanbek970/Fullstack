<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Meme;
use Illuminate\Http\Request;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function index(Request $request): View
    {
        $query = Meme::query()
            ->published()
            ->with('user')
            ->withCount(['likedBy', 'dislikedBy']);

        if ($request->filled('q')) {
            $query->where('title', 'like', '%'.addcslashes($request->input('q'), '%_\\').'%');
        }
        if ($request->filled('category')) {
            $query->where('category', $request->input('category'));
        }

        $memes = $query->latest('published_at')->paginate(12);

        $popular = Meme::query()
            ->published()
            ->withCount(['likedBy', 'dislikedBy'])
            ->orderByDesc('liked_by_count')
            ->orderByDesc('published_at')
            ->limit(4)
            ->get();

        $trending = Meme::query()
            ->published()
            ->withCount(['likedBy', 'dislikedBy'])
            ->latest('published_at')
            ->limit(4)
            ->get();

        $categories = Meme::query()
            ->published()
            ->distinct()
            ->orderBy('category')
            ->pluck('category');

        return view('pages.home', compact('memes', 'popular', 'trending', 'categories'));
    }

    public function about(): View
    {
        return view('pages.about');
    }
}
