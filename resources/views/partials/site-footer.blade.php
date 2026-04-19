<footer class="border-top mt-auto py-4 bg-body text-secondary">
    <div class="container">
        <div class="row g-3 small">
            <div class="col-md-4">
                <div class="fw-bold text-body-emphasis mb-2">{{ __('site.footer_brand') }}</div>
                <p class="mb-0">{{ __('site.footer_tagline') }}</p>
            </div>
            <div class="col-md-4">
                <div class="fw-bold text-body-emphasis mb-2">{{ __('site.footer_sections') }}</div>
                <ul class="list-unstyled mb-0">
                    <li><a href="{{ route('home') }}" class="link-body-emphasis link-underline-opacity-0">{{ __('site.nav_home') }}</a></li>
                    <li><a href="{{ route('pages.about') }}" class="link-body-emphasis link-underline-opacity-0">{{ __('site.nav_about') }}</a></li>
                    @auth
                        <li><a href="{{ route('memes.mine') }}" class="link-body-emphasis link-underline-opacity-0">{{ __('site.nav_my_memes') }}</a></li>
                    @endauth
                </ul>
            </div>
            <div class="col-md-4">
                <div class="fw-bold text-body-emphasis mb-2">{{ __('site.footer_contacts') }}</div>
                <p class="mb-0">demo@local.test</p>
            </div>
        </div>
    </div>
</footer>
