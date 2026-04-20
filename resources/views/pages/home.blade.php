@extends('layouts.app')

@section('title', __('site.home_title'))

@section('content')
    <div class="mb-4">
        <h1 class="h3 mb-1">{{ __('site.home_heading') }}</h1>
        <p class="text-secondary small mb-0">{{ __('site.home_sub') }}</p>
    </div>

    <div class="mx-auto" style="max-width:760px;">
        @include('partials.home-search')
    </div>

    <div class="row g-3 mb-4 meme-feed-grid">
        @forelse($memes as $meme)
            <div class="col-md-6 col-lg-4">
                <div class="card h-100 border-secondary bg-body meme-unified-card">
                    <div class="card-body d-flex flex-column">
                        <h2 class="h6 card-title text-truncate mb-1">{{ $meme->title }}</h2>
                        <div class="small text-secondary mb-2 text-truncate">
                            {{ $meme->category }} · ♥ {{ $meme->liked_by_count }} · 👎 {{ $meme->disliked_by_count }}
                            @if($meme->isVideo())
                                · <span class="badge bg-info text-dark">{{ __('site.home_video_badge') }}</span>
                            @endif
                        </div>
                        @if($meme->mediaUrl())
                            <a href="{{ route('memes.show', $meme) }}" class="mb-2 d-block text-center meme-thumb-wrap">
                                @include('partials.meme-media-thumb', ['meme' => $meme])
                            </a>
                        @endif
                        <a href="{{ route('memes.show', $meme) }}" class="btn btn-sm btn-outline-warning mt-auto">{{ __('site.home_open') }}</a>
                    </div>
                </div>
            </div>
        @empty
            <p class="text-secondary">{{ __('site.home_empty') }}</p>
        @endforelse
    </div>

    <div class="mb-4">{{ $memes->withQueryString()->links() }}</div>

    @include('partials.home-collections')

    @include('partials.home-ad-and-filters')
@endsection
