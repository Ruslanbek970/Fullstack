<div class="mb-3 p-2 px-3 border border-secondary rounded meme-card-surface compact-search">
    <form action="{{ route('home') }}" method="get" class="row g-2 align-items-end">
        <div class="col-md-5">
            <label class="form-label small mb-0" for="homeSearchQ">{{ __('site.search_label') }}</label>
            <input id="homeSearchQ" name="q" value="{{ request('q') }}" class="form-control form-control-sm bg-body text-body border-secondary" placeholder="{{ __('site.search_placeholder') }}">
        </div>
        <div class="col-md-4">
            <label class="form-label small mb-0" for="homeSearchCat">{{ __('site.category_label') }}</label>
            <select id="homeSearchCat" name="category" class="form-select form-select-sm bg-body text-body border-secondary">
                <option value="">{{ __('site.category_all') }}</option>
                @foreach($categories ?? [] as $cat)
                    <option value="{{ $cat }}" @selected(request('category') === $cat)>{{ $cat }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-3 d-grid">
            <button class="btn btn-sm btn-warning">{{ __('site.btn_find') }}</button>
        </div>
        @if(request()->hasAny(['q', 'category']))
            <div class="col-12">
                <a href="{{ route('home') }}" class="btn btn-sm btn-outline-secondary">{{ __('site.btn_reset') }}</a>
            </div>
        @endif
    </form>
</div>

