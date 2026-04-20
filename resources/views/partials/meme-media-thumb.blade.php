@php $url = $meme->mediaUrl(); @endphp
@if($url)
    <div class="meme-thumb-media">
        @if($meme->isVideo())
            <video src="{{ $url }}" class="w-100 h-100 rounded" muted playsinline preload="metadata"></video>
        @else
            <img src="{{ $url }}" class="w-100 h-100 rounded" alt="" loading="lazy">
        @endif
    </div>
@endif
