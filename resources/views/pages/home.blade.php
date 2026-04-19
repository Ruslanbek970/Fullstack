@extends('layouts.app')

@section('title', __('site.home_title'))

@section('content')
    <div class="mb-4">
        <h1 class="h3 mb-1">{{ __('site.home_heading') }}</h1>
        <p class="text-secondary small mb-0">{{ __('site.home_sub') }}</p>
    </div>

    <div class="row g-3 mb-4">
        @forelse($memes as $meme)
            <div class="col-md-6 col-lg-4">
                <div class="card h-100 border-secondary bg-body">
                    <div class="card-body d-flex flex-column">
                        <h2 class="h6 card-title">{{ $meme->title }}</h2>
                        <div class="small text-secondary mb-2">
                            {{ $meme->category }} · ♥ {{ $meme->liked_by_count }} · 👎 {{ $meme->disliked_by_count }}
                            @if($meme->isVideo())
                                · <span class="badge bg-info text-dark">{{ __('site.home_video_badge') }}</span>
                            @endif
                        </div>
                        @if($meme->mediaUrl())
                            <a href="{{ route('memes.show', $meme) }}" class="mb-2 d-block text-center">
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

    <div class="mb-5">{{ $memes->withQueryString()->links() }}</div>

    @include('partials.home-ad-and-filters')

    @include('partials.home-static-galleries')
@endsection
