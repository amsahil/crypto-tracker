@extends('layouts.app')

@section('content')
<div class="crypto-auth-container">
    <div class="crypto-card-3d">
        <h2 class="crypto-auth-title">Access Your Portfolio</h2>
        <form method="POST" action="{{ route('login') }}">
            @csrf

            <div class="crypto-form-group">
                <label for="email">Email Address</label>
                <input id="email" type="email" class="form-control" name="email" required autofocus>
            </div>

            <div class="crypto-form-group">
                <label for="password">Password</label>
                <input id="password" type="password" class="form-control" name="password" required>
            </div>

            <div class="crypto-form-group">
                <label class="crypto-remember">
                    <input type="checkbox" name="remember"> Remember Me
                </label>
            </div>

            <button type="submit" class="crypto-auth-btn">
                Login
            </button>

            <div class="crypto-social-login">
                <a href="{{ route('login.google') }}" class="crypto-google-btn">
                    <i class="bi bi-google"></i> Continue with Google
                </a>
            </div>
        </form>
    </div>
    <p class="crypto-auth-switch">
        New to crypto? <a href="{{ route('register') }}">Create Account</a>
    </p>
</div>
@endsection