@extends('layouts.public')

@section('title', 'Request Received — ChunkIQ')

@section('content')
<div style="max-width:520px;margin:100px auto;padding:0 5%;text-align:center;">
    <div style="font-size:3.5rem;margin-bottom:1.5rem;">✅</div>
    <h1 style="font-size:2rem;font-weight:800;letter-spacing:-0.5px;margin-bottom:0.75rem;">We got your request!</h1>
    <p style="color:var(--muted);font-size:1rem;line-height:1.7;margin-bottom:2.5rem;">
        Our team will review your details and reach out within one business day to get you set up. Check your inbox for a confirmation.
    </p>
    <div style="display:flex;gap:1rem;justify-content:center;flex-wrap:wrap;">
        <a href="{{ url('/') }}" class="btn btn-outline">Back to home</a>
        @auth
        <a href="{{ route('dashboard') }}" class="btn btn-primary">Go to dashboard</a>
        @else
        <a href="{{ route('register') }}" class="btn btn-primary">Create an account</a>
        @endauth
    </div>
</div>
@endsection
