@extends('layouts.app')

@section('title', $meme->title)

@section('content')
    <article class="mx-auto" style="max-width:720px">
        <p class="mb-2"><a href="{{ route('home') }}" class="link-warning text-decoration-none">← На главную</a></p>

        <h1 class="h3">{{ $meme->title }}</h1>
        <p class="small text-secondary">
            {{ $meme->category }} · статус <strong>{{ $meme->status }}</strong>
            @if($meme->user) · {{ $meme->user->email }} @endif
        </p>
        @if($meme->description)
            <div class="mb-3 p-3 border border-secondary rounded meme-card-surface">
                <div class="small text-secondary mb-1">{{ __('site.meme_description') }}</div>
                <div class="text-body-emphasis">{{ $meme->description }}</div>
            </div>
        @endif

        @if($meme->mediaUrl())
            <div class="mb-4">
                @if($meme->isVideo())
                    <video src="{{ $meme->mediaUrl() }}" class="w-100 rounded border border-secondary" controls playsinline preload="metadata"></video>
                @else
                    <img src="{{ $meme->mediaUrl() }}" class="img-fluid rounded border border-secondary" alt="">
                @endif
            </div>
        @endif

        @if($meme->status === 'rejected' && $meme->rejection_reason)
            <div class="alert alert-danger py-2 small">Причина отклонения: {{ $meme->rejection_reason }}</div>
        @endif

        @auth
            @if($meme->isPublished())
                @can('like.toggle')
                    <div class="d-flex flex-wrap gap-2 align-items-center mb-4">
                        <form action="{{ route('memes.like', $meme) }}" method="post" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-sm {{ $liked ? 'btn-warning' : 'btn-outline-warning' }}">
                                {{ $liked ? '♥ Лайк' : '♡ Лайк' }} ({{ $meme->liked_by_count }})
                            </button>
                        </form>
                        <form action="{{ route('memes.dislike', $meme) }}" method="post" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-sm {{ $disliked ? 'btn-secondary' : 'btn-outline-secondary' }}">
                                {{ $disliked ? '👎 Убрать дизлайк' : '👎 Дизлайк' }} ({{ $meme->disliked_by_count }})
                            </button>
                        </form>
                    </div>
                @endcan
            @endif
        @endauth

        @if($meme->isPublished())
            <h2 class="h6 mb-2">Комментарии</h2>
            @auth
                @can('comment.create')
                    <form action="{{ url('/memes/'.$meme->id.'/comments') }}" method="post" class="mb-3" id="memeCommentForm">
                        @csrf
                        <textarea name="body" id="memeCommentBody" class="form-control bg-body text-body border-secondary mb-2" rows="3" maxlength="2000" placeholder="Текст комментария">{{ old('body') }}</textarea>
                        <button type="submit" class="btn btn-sm btn-outline-secondary">Отправить</button>
                    </form>
                @endcan
            @endauth

            <ul class="list-unstyled">
                @forelse($comments as $c)
                    <li class="border border-secondary rounded p-2 mb-2 {{ $c->is_hidden ? 'opacity-50' : '' }}">
                        <div class="small text-secondary">{{ $c->user->email }}
                            @if($c->is_hidden)<span class="badge bg-warning text-dark">скрыт</span>@endif
                        </div>
                        <div>{{ $c->body }}</div>
                        @auth
                            @if($c->user_id === auth()->id())
                                <form action="{{ url('/comments/'.$c->id) }}" method="post" class="mt-1 d-inline" onsubmit="return confirm('Удалить?');">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-link btn-sm text-danger p-0">Удалить свой</button>
                                </form>
                            @endif
                            @can('comment.delete')
                                @if($c->user_id !== auth()->id())
                                    <form action="{{ url('/comments/'.$c->id) }}" method="post" class="mt-1 d-inline" onsubmit="return confirm('Удалить?');">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn btn-link btn-sm text-danger p-0">Удалить</button>
                                    </form>
                                @endif
                            @endcan
                            @can('comment.moderate')
                                @if($c->is_hidden)
                                    <form action="{{ url('/comments/'.$c->id.'/unhide') }}" method="post" class="d-inline ms-2">@csrf<button type="submit" class="btn btn-link btn-sm p-0">Показать</button></form>
                                @else
                                    <form action="{{ url('/comments/'.$c->id.'/hide') }}" method="post" class="d-inline ms-2">@csrf<button type="submit" class="btn btn-link btn-sm p-0">Скрыть</button></form>
                                @endif
                            @endcan
                        @endauth
                    </li>
                @empty
                    <li class="text-secondary small">Пока нет комментариев.</li>
                @endforelse
            </ul>
        @endif
    </article>
@endsection

@auth
    @can('comment.create')
        @if($meme->isPublished())
            @push('scripts')
            <script>
                (function () {
                    var key = 'meme_comment_draft_{{ $meme->id }}';
                    var ta = document.getElementById('memeCommentBody');
                    var form = document.getElementById('memeCommentForm');
                    if (!ta || !form) return;
                    try {
                        var saved = localStorage.getItem(key);
                        if (saved && !ta.value) ta.value = saved;
                    } catch (e) {}
                    ta.addEventListener('input', function () {
                        try { localStorage.setItem(key, ta.value); } catch (e) {}
                    });
                    form.addEventListener('submit', function () {
                        try { localStorage.removeItem(key); } catch (e) {}
                    });
                    document.addEventListener('submit', function (e) {
                        if (!e || !e.target || e.target === form) return;
                        try { localStorage.setItem(key, ta.value); } catch (e2) {}
                    }, true);
                })();
            </script>
        @endif
    @endcan
@endauth
