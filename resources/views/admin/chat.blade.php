@extends('layouts.app')

@section('content')
<div class="section">
    <div class="container">
        <h1>Admin Chat Dashboard</h1>
        @if(!auth()->user() || !auth()->user()->is_admin)
            <div class="error-message">Access denied.</div>
        @else
            <div style="margin-bottom:1rem;">
                <input type="text" id="chatSearchInput" placeholder="Search messages by name..." style="padding:0.75rem; width:100%; max-width:400px; border-radius:0.5rem; border:1px solid var(--border);">
            </div>
            <div id="adminChatRoot"></div>
        @endif
    </div>
</div>
@endsection
