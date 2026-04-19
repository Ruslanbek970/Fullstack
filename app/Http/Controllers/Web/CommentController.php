<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use App\Models\Meme;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function store(Request $request, Meme $meme)
    {
        if (! $meme->isPublished()) {
            abort(403, 'Комментировать можно только опубликованные мемы.');
        }
        if (! $request->user()->can('comment.create')) {
            abort(403);
        }

        $validated = $request->validate([
            'body' => ['required', 'string', 'max:2000'],
        ]);

        Comment::create([
            'meme_id' => $meme->id,
            'user_id' => $request->user()->id,
            'body' => $validated['body'],
            'is_hidden' => false,
        ]);

        return back()->with('message', 'Комментарий добавлен.');
    }

    public function destroy(Request $request, Comment $comment)
    {
        if ($comment->user_id !== $request->user()->id && ! $request->user()->can('comment.delete')) {
            abort(403);
        }

        $comment->delete();

        return back()->with('message', 'Комментарий удалён.');
    }

    public function hide(Request $request, Comment $comment)
    {
        if (! $request->user()->can('comment.moderate')) {
            abort(403);
        }

        $comment->update(['is_hidden' => true]);

        return back()->with('message', 'Комментарий скрыт.');
    }

    public function unhide(Request $request, Comment $comment)
    {
        if (! $request->user()->can('comment.moderate')) {
            abort(403);
        }

        $comment->update(['is_hidden' => false]);

        return back()->with('message', 'Комментарий снова виден.');
    }
}
