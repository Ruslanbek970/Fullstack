@extends('layouts.app')

@section('title', __('site.mine_edit'))

@section('content')
    <div class="mx-auto" style="max-width:560px">
        <h1 class="h4 mb-2">#{{ $meme->id }}</h1>
        <p class="small text-secondary mb-3">
            {{ __('site.col_status') }}: <span class="badge bg-secondary">{{ $meme->status }}</span>
            @if($meme->mediaUrl())
                · <a href="{{ route('memes.show', $meme) }}" class="link-warning">{{ __('site.home_open') }}</a>
            @endif
        </p>
        <p class="small text-warning mb-3">{{ __('site.meme_edit_reminder') }}</p>

        @if ($errors->any())
            <div class="alert alert-danger py-2 small">{{ $errors->first() }}</div>
        @endif

        <form action="{{ route('memes.update', $meme) }}" method="post" enctype="multipart/form-data" class="p-3 border border-secondary rounded meme-card-surface">
            @csrf
            @method('PATCH')
            <div class="mb-2">
                <label class="form-label small">{{ __('site.col_title') }}</label>
                <input type="text" name="title" class="form-control bg-body text-body border-secondary" value="{{ old('title', $meme->title) }}" required maxlength="255">
            </div>
            <div class="mb-2">
                <label class="form-label small">{{ __('site.category_label') }}</label>
                <input type="text" name="category" class="form-control bg-body text-body border-secondary" value="{{ old('category', $meme->category) }}" required maxlength="100">
            </div>
            <div class="mb-2">
                <label class="form-label small">Новый файл</label>
                <input type="file" name="upload" class="form-control bg-body text-body border-secondary" accept="image/*,video/mp4,video/webm,.webp">
            </div>
            <div class="mb-3">
                <label class="form-label small">{{ __('site.search_placeholder') }}</label>
                <input type="text" name="remote_url" class="form-control bg-body text-body border-secondary" value="{{ old('remote_url', str_starts_with((string) $meme->image, 'http') ? $meme->image : '') }}" placeholder="https:// или ссылка Google Картинок" autocomplete="off">
            </div>
            <button type="submit" class="btn btn-warning">{{ __('site.profile_save') }}</button>
            <a href="{{ route('memes.mine') }}" class="btn btn-outline-secondary btn-sm ms-2">{{ __('site.btn_cancel') }}</a>
        </form>
    </div>
@endsection
