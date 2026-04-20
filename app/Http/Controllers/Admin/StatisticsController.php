<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use App\Models\Meme;
use Illuminate\View\View;

class StatisticsController extends Controller
{
    public function __invoke(): View
    {
        $barRaw = Meme::query()
            ->published()
            ->leftJoin('meme_likes', 'memes.id', '=', 'meme_likes.meme_id')
            ->leftJoin('meme_dislikes', 'memes.id', '=', 'meme_dislikes.meme_id')
            ->selectRaw('memes.category, count(distinct meme_likes.id) as likes_count, count(distinct meme_dislikes.id) as dislikes_count')
            ->groupBy('memes.category')
            ->orderBy('memes.category')
            ->get();

        $barLabels = $barRaw->pluck('category')->all();
        $barData = $barRaw->pluck('likes_count')->map(fn ($v) => (int) $v)->all();
        $barDataDislikes = $barRaw->pluck('dislikes_count')->map(fn ($v) => (int) $v)->all();
        if ($barLabels === []) {
            $barLabels = ['Нет данных'];
            $barData = [0];
            $barDataDislikes = [0];
        }

        $pieRaw = Meme::query()
            ->selectRaw('status, count(*) as c')
            ->groupBy('status')
            ->get();

        $pieLabels = $pieRaw->pluck('status')->all();
        $pieData = $pieRaw->pluck('c')->map(fn ($v) => (int) $v)->all();
        if ($pieLabels === []) {
            $pieLabels = ['empty'];
            $pieData = [0];
        }

        $weekdayLabels = ['Пн', 'Вт', 'Ср', 'Чт', 'Пт'];
        $polarData = [];
        foreach (range(0, 4) as $offset) {
            $d = now()->startOfWeek()->addDays($offset);
            $polarData[] = Meme::query()->whereDate('created_at', $d->toDateString())->count();
        }

        $lineLabels = [];
        $lineData = [];
        foreach (range(6, 0) as $daysAgo) {
            $d = now()->subDays($daysAgo)->startOfDay();
            $lineLabels[] = $d->format('d.m');
            $lineData[] = Meme::query()->whereDate('created_at', $d->toDateString())->count();
        }

        $polarExtra = [
            'comments_week' => Comment::query()
                ->where('created_at', '>=', now()->subDays(7))
                ->count(),
            'memes_total' => Meme::query()->count(),
            'likes_total' => (int) \DB::table('meme_likes')->count(),
            'dislikes_total' => (int) \DB::table('meme_dislikes')->count(),
        ];

        return view('pages.admin.statistics', compact(
            'barLabels',
            'barData',
            'barDataDislikes',
            'pieLabels',
            'pieData',
            'weekdayLabels',
            'polarData',
            'lineLabels',
            'lineData',
            'polarExtra'
        ));
    }
}
