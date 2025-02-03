@extends('layouts.app')

@section('content')
<div class="crypto-auth-container">
    <div class="crypto-card-3d">
        <h2 class="crypto-auth-title">Create Crypto Portfolio</h2>
        <form method="POST" action="{{ route('register') }}">
            @csrf

            <div class="crypto-form-group">
                <label for="name">Full Name</label>
                <input id="name" type="text" class="form-control" name="name" required autofocus>
            </div>

            <div class="crypto-form-group">
                <label for="username">Trader ID</label>
                <input id="username" type="text" class="form-control" name="username" required>
            </div>

            <div class="crypto-form-group">
                <label for="email">Email Address</label>
                <input id="email" type="email" class="form-control" name="email" required>
            </div>

            <div class="crypto-form-group">
                <label for="password">Password</label>
                <input id="password" type="password" class="form-control" name="password" required>
            </div>

            <div class="crypto-form-group">
                <label for="password-confirm">Confirm Password</label>
                <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required>
            </div>

            <button type="submit" class="crypto-auth-btn">
                Start Tracking
            </button>
        </form>
    </div>
    <p class="crypto-auth-switch">
        Already have an account? <a href="{{ route('login') }}">Log in</a>
    </p>
</div>
@endsection