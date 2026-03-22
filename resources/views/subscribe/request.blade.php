@extends('layouts.public')

@section('title', ($plan === 'enterprise' ? 'Contact Sales' : 'Subscribe to Starter') . ' — ChunkIQ')

@section('styles')
<style>
    .sub-wrap { max-width: 600px; margin: 80px auto; padding: 0 5% 80px; }
    .sub-card { background: #fff; border: 1px solid var(--border); border-radius: 16px; padding: 2.5rem; }
    .sub-plan-badge { display: inline-flex; align-items: center; gap: 0.5rem; font-size: 0.8rem; font-weight: 700; letter-spacing: 0.8px; text-transform: uppercase; padding: 0.35rem 0.9rem; border-radius: 20px; margin-bottom: 1.5rem; }
    .sub-plan-badge.starter  { background: #eff6ff; color: #1d4ed8; }
    .sub-plan-badge.enterprise { background: #f5f3ff; color: #6d28d9; }
    .sub-card h1 { font-size: 1.8rem; font-weight: 800; letter-spacing: -0.5px; margin-bottom: 0.5rem; }
    .sub-card .sub-desc { color: var(--muted); font-size: 0.95rem; line-height: 1.7; margin-bottom: 2rem; }
    .form-row { display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; }
    @media (max-width: 500px) { .form-row { grid-template-columns: 1fr; } }
    .field { margin-bottom: 1.25rem; }
    .field label { display: block; font-size: 0.85rem; font-weight: 600; color: #374151; margin-bottom: 0.4rem; }
    .field input, .field textarea, .field select {
        width: 100%; padding: 0.65rem 0.9rem; border: 1px solid #d1d5db; border-radius: 8px;
        font-size: 0.9rem; color: #111; background: #fff; outline: none; transition: border-color 0.15s;
    }
    .field input:focus, .field textarea:focus, .field select:focus { border-color: var(--blue); box-shadow: 0 0 0 3px rgba(15,98,254,0.08); }
    .field textarea { resize: vertical; min-height: 100px; }
    .field-error { color: #dc2626; font-size: 0.8rem; margin-top: 0.3rem; }
    .plan-summary { background: #f8fafc; border: 1px solid var(--border); border-radius: 10px; padding: 1rem 1.25rem; margin-bottom: 1.5rem; font-size: 0.88rem; color: var(--muted); line-height: 1.7; }
    .plan-summary strong { color: var(--text); }
</style>
@endsection

@section('content')
<div class="sub-wrap">
    <div class="sub-card">

        <div class="sub-plan-badge {{ $plan }}">
            {{ $plan === 'enterprise' ? '🏢 Enterprise' : '⚡ Starter Plan' }}
        </div>

        <h1>{{ $plan === 'enterprise' ? 'Contact our sales team' : 'Subscribe to ChunkIQ Starter' }}</h1>
        <p class="sub-desc">
            @if($plan === 'enterprise')
                Tell us about your organisation and requirements. Our team will reach out within one business day to discuss a dedicated deployment.
            @else
                Fill in your details below. Our team will set up your workspace and send you an onboarding link — no credit card required to start the conversation.
            @endif
        </p>

        @if($plan === 'starter')
        <div class="plan-summary">
            <strong>Starter — $299/month</strong><br>
            1 workspace · All 4 connectors (SharePoint, Teams, OneDrive, OneNote) · Up to 50,000 documents · Scheduled pipeline runs · Email support
        </div>
        @else
        <div class="plan-summary">
            <strong>Enterprise — Custom pricing</strong><br>
            Dedicated Azure installation · Unlimited workspaces & connectors · Unlimited documents · Custom SLA · Dedicated customer success manager
        </div>
        @endif

        @if($errors->any())
        <div style="background:#fef2f2;border:1px solid #fecaca;color:#dc2626;border-radius:8px;padding:0.85rem 1rem;font-size:0.88rem;margin-bottom:1.25rem;">
            @foreach($errors->all() as $error)
                <div>{{ $error }}</div>
            @endforeach
        </div>
        @endif

        <form method="POST" action="{{ route('subscribe.store') }}">
            @csrf
            <input type="hidden" name="plan" value="{{ $plan }}">

            <div class="form-row">
                <div class="field">
                    <label for="name">Full Name <span style="color:#dc2626">*</span></label>
                    <input type="text" id="name" name="name" value="{{ old('name', auth()->user()?->name) }}" required placeholder="Jane Smith">
                </div>
                <div class="field">
                    <label for="email">Work Email <span style="color:#dc2626">*</span></label>
                    <input type="email" id="email" name="email" value="{{ old('email', auth()->user()?->email) }}" required placeholder="jane@company.com">
                </div>
            </div>

            <div class="field">
                <label for="company">Company / Organisation</label>
                <input type="text" id="company" name="company" value="{{ old('company') }}" placeholder="Acme Corp">
            </div>

            <div class="field">
                <label for="message">
                    {{ $plan === 'enterprise' ? 'Tell us about your requirements' : 'Anything else we should know?' }}
                </label>
                <textarea id="message" name="message" placeholder="{{ $plan === 'enterprise' ? 'Number of users, data sources, compliance requirements, timeline...' : 'Your Microsoft 365 data sources, approximate document volume, etc.' }}">{{ old('message') }}</textarea>
            </div>

            <button type="submit" class="btn btn-primary" style="width:100%;justify-content:center;padding:0.9rem;font-size:1rem;">
                {{ $plan === 'enterprise' ? 'Send enquiry' : 'Request subscription' }}
            </button>

            <p style="text-align:center;font-size:0.82rem;color:var(--muted);margin-top:1rem;">
                No payment required now. Our team will reach out to complete setup.
            </p>
        </form>
    </div>
</div>
@endsection
