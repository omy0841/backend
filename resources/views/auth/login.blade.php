@extends('layouts.app')

@section('content')
<div class="section" style="min-height: 80vh; display: flex; align-items: center; justify-content: center;">
    <div class="card" style="max-width: 520px; width: 100%;">
        <h1>Log in to SeaFresh Zanzibar</h1>
        <p style="color: #64748b; margin-bottom: 1.5rem;">Access your seafood ordering dashboard and place a new request.</p>

        @if($errors->any())
            <div class="error-message" style="margin-bottom: 1.5rem; text-align: left;">
                <ul style="margin: 0; padding-left: 1.25rem;">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}" class="form-card">
            @csrf
            <div class="form-group">
                <label>Email address</label>
                <input type="email" name="email" value="{{ old('email') }}" required class="form-control">
            </div>
            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" required class="form-control">
            </div>
            <div class="form-group" style="display: flex; justify-content: space-between; align-items: center; gap: 1rem;">
                <label class="checkbox-label">
                    <input type="checkbox" name="remember">
                    Remember me
                </label>
                <a href="#" style="color: var(--primary); font-weight: 600;">Forgot password?</a>
            </div>
            <button type="submit" class="btn btn-primary">Log In</button>
        </form>

        <p style="margin-top: 1.5rem; color: #64748b;">Don’t have an account yet? <a href="{{ route('register') }}" style="color: var(--primary); font-weight: 600;">Register now</a>.</p>
    </div>
</div>
@endsection
