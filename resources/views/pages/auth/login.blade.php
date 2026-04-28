@extends('layouts.app')

@section('title', __('site.auth_login'))

@section('content')
    <div class="mx-auto" style="max-width:420px">
        <h1 class="h4 mb-3">{{ __('site.auth_login') }}</h1>

        @if(!empty($message))
            <div class="alert alert-warning py-2">{{ $message }}</div>
        @endif

        <form action="{{ route('login') }}" method="post" class="p-3 border border-secondary rounded meme-card-surface">
            @csrf

            <div class="mb-2">
                <label class="form-label small">{{ __('site.auth_email') }}</label>
                <input type="email" name="login"
                       class="form-control bg-body text-body border-secondary"
                       placeholder="email@example.com"
                       required value="{{ old('login') }}"
                       autocomplete="username">
            </div>

            <div class="mb-3">
                <label class="form-label small">{{ __('site.auth_password') }}</label>
                <div class="input-group">
                    <input type="password" name="password" id="loginPassword"
                           class="form-control bg-body text-body border-secondary"
                           required autocomplete="current-password">

                    <button type="button" class="btn btn-outline-secondary"
                            data-password-toggle="#loginPassword"
                            title="{{ __('site.auth_show_password') }}">👁</button>
                </div>
            </div>

            <button type="submit" class="btn btn-warning w-100">
                {{ __('site.auth_sign_in') }}
            </button>
        </form>

        <p class="mt-3 small">
            <a href="{{ route('register') }}" class="link-warning">
                {{ __('site.auth_no_account') }}
            </a>
            ·
            <a href="{{ route('home') }}" class="link-secondary">
                {{ __('site.auth_back_home') }}
            </a>
        </p>
    </div>
@endsection