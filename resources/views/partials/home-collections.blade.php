<div class="mt-2 mb-4">
    <div class="d-flex justify-content-between align-items-center mb-2">
        <h2 class="h5 mb-0">{{ __('site.home_extra_blocks') }}</h2>
        <span class="small text-secondary">{{ __('site.home_extra_blocks_sub') }}</span>
    </div>
</div>
<div class="row g-3 mb-4">
    <div class="col-lg-6">
        <div class="d-flex justify-content-between align-items-baseline mb-2">
            <div class="fw-bold">{{ __('site.collections_popular') }}</div>
            <div class="small text-secondary">{{ __('site.collections_popular_sub') }}</div>
        </div>
        <div class="row g-2">
            @forelse($popular as $meme)
                <div class="col-6">
                    <a href="{{ route('memes.show', $meme) }}" class="text-decoration-none">
                        <div class="card h-100 border-secondary bg-body meme-unified-card">
                            <div class="card-body p-2 d-flex flex-column">
                            <div class="small fw-semibold text-body-emphasis text-truncate">{{ $meme->title }}</div>
                            <div class="small text-secondary text-truncate">{{ $meme->category }} · ♥ {{ $meme->liked_by_count }}</div>
                            <div class="mt-2 meme-thumb-wrap">
                                @include('partials.meme-media-thumb', ['meme' => $meme])
                            </div>
                            </div>
                        </div>
                    </a>
                </div>
            @empty
                <div class="col-12 text-secondary small">{{ __('site.home_empty') }}</div>
            @endforelse
        </div>
    </div>
    <div class="col-lg-6">
        <div class="d-flex justify-content-between align-items-baseline mb-2">
            <div class="fw-bold">{{ __('site.collections_trends') }}</div>
            <div class="small text-secondary">{{ __('site.collections_trends_sub') }}</div>
        </div>
        <div class="row g-2">
            @forelse($trending as $meme)
                <div class="col-6">
                    <a href="{{ route('memes.show', $meme) }}" class="text-decoration-none">
                        <div class="card h-100 border-secondary bg-body meme-unified-card">
                            <div class="card-body p-2 d-flex flex-column">
                            <div class="small fw-semibold text-body-emphasis text-truncate">{{ $meme->title }}</div>
                            <div class="small text-secondary text-truncate">{{ $meme->category }} · {{ optional($meme->published_at)->diffForHumans() }}</div>
                            <div class="mt-2 meme-thumb-wrap">
                                @include('partials.meme-media-thumb', ['meme' => $meme])
                            </div>
                            </div>
                        </div>
                    </a>
                </div>
            @empty
                <div class="col-12 text-secondary small">{{ __('site.home_empty') }}</div>
            @endforelse
        </div>
    </div>
</div>

