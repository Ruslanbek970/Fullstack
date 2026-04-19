<div class="mt-4">
    <div class="d-flex flex-wrap gap-2 mb-2 ad-banner-toolbar">
        <button type="button" class="btn btn-sm btn-outline-secondary" id="btnAdHide">{{ __('site.ad_hide') }}</button>
        <button type="button" class="btn btn-sm btn-outline-secondary" id="btnAdShow">{{ __('site.ad_show') }}</button>
        <button type="button" class="btn btn-sm btn-outline-danger" id="btnAdStop">{{ __('site.ad_stop') }}</button>
    </div>
    <div class="ad-banner-wrap meme-card-surface" id="adBannerOuter">
        <div class="fw-bold text-body-emphasis mb-2">{{ __('site.ad_title') }}</div>
        <div id="adMarqueeViewport" class="ad-marquee-viewport">
            <div id="adMarqueeStrip" class="ad-marquee-strip">{{ __('site.ad_text') }}</div>
        </div>
    </div>
</div>

<div class="mt-4 d-flex flex-wrap gap-2">
    <button type="button" class="btn btn-sm btn-outline-secondary" id="btnFiltrDown">{{ __('site.filters_show') }}</button>
    <button type="button" class="btn btn-sm btn-outline-secondary" id="btnFiltrUp">{{ __('site.filters_hide') }}</button>
    <button type="button" class="btn btn-sm btn-outline-warning" id="btnPodskazkaIn">{{ __('site.hint_show') }}</button>
    <button type="button" class="btn btn-sm btn-outline-warning" id="btnPodskazkaOut">{{ __('site.hint_hide') }}</button>
</div>

<form action="{{ route('home') }}" method="get" id="blokFiltr" class="mt-3 p-3 border border-secondary rounded d-none meme-card-surface">
    <div class="d-flex gap-2 flex-wrap align-items-end">
        <div>
            <label class="form-label small mb-0" for="polePoiska">{{ __('site.search_label') }}</label>
            <input id="polePoiska" name="q" value="{{ request('q') }}" class="form-control form-control-sm bg-body text-body border-secondary" style="max-width:260px;" placeholder="{{ __('site.search_placeholder') }}">
        </div>
        <div>
            <label class="form-label small mb-0" for="kategoriya">{{ __('site.category_label') }}</label>
            <select id="kategoriya" name="category" class="form-select form-select-sm bg-body text-body border-secondary" style="max-width:200px;">
                <option value="">{{ __('site.category_all') }}</option>
                @foreach($categories ?? [] as $cat)
                    <option value="{{ $cat }}" @selected(request('category') === $cat)>{{ $cat }}</option>
                @endforeach
            </select>
        </div>
        <button id="btnNaiti" type="button" class="btn btn-sm btn-secondary">{{ __('site.btn_find') }}</button>
        @if(request()->hasAny(['q', 'category']))
            <a href="{{ route('home') }}" class="btn btn-sm btn-outline-secondary">{{ __('site.btn_reset') }}</a>
        @endif
    </div>
</form>

<div id="blokPodskazka" class="mt-3 p-3 border border-secondary rounded d-none small meme-card-surface">
    {{ __('site.hint_body') }}
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    var outer = document.getElementById('adBannerOuter');
    var vp = document.getElementById('adMarqueeViewport');
    var btnHide = document.getElementById('btnAdHide');
    var btnShow = document.getElementById('btnAdShow');
    var btnStop = document.getElementById('btnAdStop');
    if (outer && btnHide && btnShow) {
        btnHide.addEventListener('click', function () { outer.classList.add('is-hidden'); });
        btnShow.addEventListener('click', function () { outer.classList.remove('is-hidden'); });
    }
    if (vp && btnStop) {
        btnStop.addEventListener('click', function () { vp.classList.toggle('is-stopped'); });
    }
});

$(function () {
    $("#btnFiltrDown").click(function(){ $("#blokFiltr").removeClass('d-none').hide().slideDown(200); });
    $("#btnFiltrUp").click(function(){ $("#blokFiltr").slideUp(200, function(){ $(this).addClass('d-none'); }); });

    $("#btnNaiti").on('click', function(){
        var $f = $("#blokFiltr");
        $f.fadeTo(150, 0.45).delay(120).fadeTo(150, 1, function(){ $f[0].submit(); });
    });
    $("#polePoiska").on('click focus', function(){ $("#blokFiltr").fadeTo(150, 1); });

    $("#btnPodskazkaIn").click(function(){ $("#blokPodskazka").removeClass('d-none').hide().fadeIn(450); });
    $("#btnPodskazkaOut").click(function(){ $("#blokPodskazka").fadeOut(400, function(){ $(this).addClass('d-none'); }); });

    @if(request()->hasAny(['q', 'category']))
        $("#blokFiltr").removeClass('d-none').show();
    @endif
});
</script>
@endpush
