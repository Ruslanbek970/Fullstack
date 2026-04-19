@php $url = $meme->mediaUrl(); @endphp
@if($url)
    @if($meme->isVideo())
        <video src="{{ $url }}" class="w-100 rounded" style="max-height:180px;object-fit:cover" muted playsinline preload="metadata"></video>
    @else
        <img src="{{ $url }}" class="img-fluid rounded" alt="" loading="lazy" style="max-height:180px;object-fit:cover;width:100%">
    @endif
@endif
