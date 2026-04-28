@extends('layouts.app')

@section('title', __('site.auth_register'))

@section('content')
    <div class="mx-auto" style="max-width:420px">
        <h1 class="h4 mb-3">{{ __('site.auth_register') }}</h1>

        @if ($errors->any())
            <div class="alert alert-danger py-2 small">{{ $errors->first() }}</div>
        @endif

        <form action="{{ route('register') }}" method="post" class="p-3 border border-secondary rounded meme-card-surface">
            @csrf

            <div class="mb-2">
                <label class="form-label small">{{ __('site.auth_name') }}</label>
                <input type="text" name="name" class="form-control bg-body text-body border-secondary"
                       value="{{ old('name') }}" required autocomplete="name">
            </div>

            <div class="mb-2">
                <label class="form-label small">{{ __('site.auth_email') }}</label>
                <input type="email" name="email" class="form-control bg-body text-body border-secondary"
                       value="{{ old('email') }}" required autocomplete="email">
            </div>

            <div class="mb-3">
                <label class="form-label small">{{ __('site.auth_password') }}</label>
                <div class="input-group">
                    <input type="password" name="password" id="regPassword"
                           class="form-control bg-body text-body border-secondary"
                           required minlength="6" autocomplete="new-password">

                    <button type="button" class="btn btn-outline-secondary"
                            data-password-toggle="#regPassword"
                            title="{{ __('site.auth_show_password') }}">👁</button>
                </div>
            </div>

            <button type="submit" class="btn btn-warning w-100">
                {{ __('site.auth_create_account') }}
            </button>
        </form>

        <p class="mt-3 small">
            <a href="{{ route('login') }}" class="link-warning">
                {{ __('site.auth_already_have_account') }}
            </a>
        </p>
    </div>
@endsection