@extends('layouts.app')

@section('title', __('site.nav_about'))

@section('content')
    <article class="mx-auto" style="max-width:760px">
        <div class="p-4 p-md-5 border border-secondary rounded meme-card-surface">
            <h1 class="h3 mb-2">{{ __('site.about_title') }}</h1>
            <p class="text-secondary mb-4">{{ __('site.about_lead') }}</p>
            <p class="small text-secondary mb-4">
                {{ __('MEME HUB demonstrates JSON translations for long paragraphs: with this approach, the original sentence acts as the translation key, so large text blocks in Blade remain easier to read and maintain.') }}
            </p>

            <div class="row g-3">
                <div class="col-md-6">
                    <div class="p-3 border border-secondary rounded meme-card-surface h-100">
                        <div class="fw-semibold mb-1">{{ __('site.about_feature_upload_title') }}</div>
                        <div class="small text-secondary">{{ __('site.about_feature_upload_body') }}</div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="p-3 border border-secondary rounded meme-card-surface h-100">
                        <div class="fw-semibold mb-1">{{ __('site.about_feature_moderation_title') }}</div>
                        <div class="small text-secondary">{{ __('site.about_feature_moderation_body') }}</div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="p-3 border border-secondary rounded meme-card-surface h-100">
                        <div class="fw-semibold mb-1">{{ __('site.about_feature_reactions_title') }}</div>
                        <div class="small text-secondary">{{ __('site.about_feature_reactions_body') }}</div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="p-3 border border-secondary rounded meme-card-surface h-100">
                        <div class="fw-semibold mb-1">{{ __('site.about_feature_roles_title') }}</div>
                        <div class="small text-secondary">{{ __('site.about_feature_roles_body') }}</div>
                    </div>
                </div>
            </div>

            <hr class="border-secondary my-4">

            <div class="d-flex flex-wrap gap-2">
                <a href="{{ route('home') }}" class="btn btn-warning">{{ __('site.nav_home') }}</a>
                @auth
                    <a href="{{ route('memes.create') }}" class="btn btn-outline-warning">{{ __('site.nav_create_meme') }}</a>
                @else
                    <a href="{{ route('register') }}" class="btn btn-outline-warning">{{ __('site.nav_register') }}</a>
                @endauth
            </div>
        </div>
    </article>
@endsection
