@extends('layouts.app')

@section('title', __('site.nav_create_meme'))

@section('content')
    <div class="mx-auto" style="max-width:560px">
        <h1 class="h4 mb-2">{{ __('site.nav_create_meme') }}</h1>
        <p class="small text-secondary">{{ __('site.meme_create_hint') }}</p>

        @if ($errors->any())
            <div class="alert alert-danger py-2 small">{{ $errors->first() }}</div>
        @endif

        <form action="{{ route('memes.store') }}" method="post" enctype="multipart/form-data" class="p-3 border border-secondary rounded meme-card-surface">
            @csrf
            <div class="mb-2">
                <label class="form-label small">{{ __('site.col_title') }}</label>
                <input type="text" name="title" class="form-control bg-body text-body border-secondary" value="{{ old('title') }}" required maxlength="255">
            </div>
            <div class="mb-2">
                <label class="form-label small">{{ __('site.category_label') }}</label>
                <input type="text" name="category" class="form-control bg-body text-body border-secondary" value="{{ old('category') }}" required maxlength="100">
            </div>
            <div class="mb-2">
                <label class="form-label small">Файл с устройства</label>
                <input type="file" name="upload" id="memeUploadInput" class="form-control bg-body text-body border-secondary" accept="image/*,video/mp4,video/webm,.webp">
                <div id="memeFilePreview" class="mt-2 rounded border border-secondary overflow-hidden bg-black bg-opacity-10 d-none" style="max-height:280px;">
                    <img id="memeFilePreviewImg" src="" alt="" class="w-100 h-100 object-fit-contain d-none" style="max-height:260px;object-fit:contain;">
                    <video id="memeFilePreviewVid" class="w-100 d-none" style="max-height:260px;" controls muted></video>
                </div>
            </div>
            <div class="mb-3">
                <label class="form-label small">Или ссылка (прямая на файл или страница Google Картинок)</label>
                <input type="text" name="remote_url" class="form-control bg-body text-body border-secondary" value="{{ old('remote_url') }}" placeholder="https://…" autocomplete="off">
            </div>
            <button type="submit" class="btn btn-warning">{{ __('site.meme_submit') }}</button>
            <a href="{{ route('memes.mine') }}" class="btn btn-outline-secondary btn-sm ms-2">{{ __('site.btn_cancel') }}</a>
        </form>
    </div>
@endsection

@push('scripts')
<script>
(function () {
    var input = document.getElementById('memeUploadInput');
    var box = document.getElementById('memeFilePreview');
    var img = document.getElementById('memeFilePreviewImg');
    var vid = document.getElementById('memeFilePreviewVid');
    if (!input || !box) return;
    input.addEventListener('change', function () {
        box.classList.add('d-none');
        img.classList.add('d-none');
        vid.classList.add('d-none');
        img.removeAttribute('src');
        vid.removeAttribute('src');
        var f = input.files && input.files[0];
        if (!f) return;
        box.classList.remove('d-none');
        if (f.type.indexOf('video/') === 0) {
            vid.classList.remove('d-none');
            vid.src = URL.createObjectURL(f);
        } else {
            img.classList.remove('d-none');
            img.src = URL.createObjectURL(f);
        }
    });
})();
</script>
@endpush
