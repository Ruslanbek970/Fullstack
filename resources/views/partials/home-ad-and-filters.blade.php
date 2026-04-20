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
});
</script>
@endpush
