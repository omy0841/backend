@extends('layouts.app')

@section('content')
<div class="section" style="min-height: 80vh; display: flex; align-items: center; justify-content: center;">
    <div class="card" style="max-width: 520px; width: 100%;">
        <h1>Create your account</h1>
        <p style="color: #64748b; margin-bottom: 1.5rem;">Register to place seafood orders for your hotel or restaurant.</p>

        @if($errors->any())
            <div class="error-message" style="margin-bottom: 1.5rem; text-align: left;">
                <ul style="margin: 0; padding-left: 1.25rem;">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('register') }}" class="form-card">
            @csrf

            <div class="form-group">
                <label>Full name</label>
                <input type="text" name="name" value="{{ old('name') }}" required class="form-control">
            </div>

            <div class="form-group">
                <label>Email address</label>
                <input type="email" name="email" value="{{ old('email') }}" required class="form-control">
            </div>

            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" required class="form-control">
            </div>

            <div class="form-group">
                <label>Confirm password</label>
                <input type="password" name="password_confirmation" required class="form-control">
            </div>

            <button type="submit" class="btn btn-primary">Create account</button>
        </form>

        <p style="margin-top: 1.5rem; color: #64748b;">Already registered? <a href="{{ route('login') }}" style="color: var(--primary); font-weight: 600;">Log in</a>.</p>
    </div>
</div>
@endsection
