@extends('layouts.app')

@section('title', __('site.moderation_title'))

@section('content')
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3">
        <h1 class="h4 mb-0">{{ __('site.moderation_heading') }}</h1>
        <a href="{{ route('home') }}" class="btn btn-sm btn-outline-secondary">{{ __('site.moderation_home') }}</a>
    </div>

    <form method="get" action="{{ route('memes.moderation') }}" class="row g-2 align-items-end mb-4 flex-wrap">
        <div class="col-auto">
            <label class="form-label small mb-0" for="modSearch">{{ __('site.search_label') }}</label>
            <input id="modSearch" type="text" name="q" value="{{ $q ?? '' }}" class="form-control form-control-sm bg-body text-body border-secondary" placeholder="{{ __('site.moderation_search_placeholder') }}" style="min-width:220px">
        </div>
        <div class="col-auto">
            <button type="submit" class="btn btn-sm btn-warning">{{ __('site.moderation_search') }}</button>
        </div>
    </form>

    @forelse($memes as $m)
        <div class="card border-secondary bg-body text-body mb-3">
            <div class="card-body">
                <div class="d-flex justify-content-between flex-wrap gap-2">
                    <div>
                        <div class="fw-bold">#{{ $m->id }} — {{ $m->title }}</div>
                        <div class="small text-secondary">{{ $m->category }} · {{ $m->user?->email ?? '—' }}</div>
                    </div>
                    <div class="d-flex flex-wrap gap-2 align-items-start">
                        <form action="{{ route('memes.publish', $m) }}" method="post" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-sm btn-success">{{ __('site.moderation_accept') }}</button>
                        </form>
                        <form action="{{ route('memes.reject', $m) }}" method="post" class="d-flex flex-wrap gap-1 align-items-start">
                            @csrf
                            <input type="text" name="rejection_reason" class="form-control form-control-sm bg-body text-body border-secondary" style="min-width:200px" placeholder="{{ __('site.moderation_reason_placeholder') }}">
                            <button type="submit" class="btn btn-sm btn-danger">{{ __('site.moderation_reject') }}</button>
                        </form>
                    </div>
                </div>
                @if($m->mediaUrl())
                    <div class="mt-3">
                        @if($m->isVideo())
                            <video src="{{ $m->mediaUrl() }}" class="w-100 rounded" style="max-height:240px" controls preload="metadata"></video>
                        @else
                            <img src="{{ $m->mediaUrl() }}" class="img-fluid rounded" alt="" style="max-height:240px;object-fit:contain">
                        @endif
                    </div>
                @endif
            </div>
        </div>
    @empty
        <p class="text-secondary">{{ __('site.moderation_empty') }}</p>
    @endforelse

    <div class="mt-3">{{ $memes->links() }}</div>
@endsection
