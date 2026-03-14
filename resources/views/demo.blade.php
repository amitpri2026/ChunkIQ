@extends('layouts.public')

@section('title', 'Book a Demo — ChunkIQ')

@section('styles')
<style>
    .demo-wrap { display: grid; grid-template-columns: 1fr 1fr; gap: 0; min-height: calc(100vh - 64px); }
    @media(max-width:768px) { .demo-wrap { grid-template-columns: 1fr; } }

    .demo-left {
        background: linear-gradient(160deg, var(--navy) 0%, #0d2757 100%);
        color: #fff; padding: 70px 8%;
        display: flex; flex-direction: column; justify-content: center;
    }
    .demo-left h1 { font-size: clamp(1.8rem, 3vw, 2.5rem); font-weight: 800; letter-spacing: -0.5px; line-height: 1.2; margin-bottom: 1.25rem; }
    .demo-left h1 em { font-style: normal; color: #60a5fa; }
    .demo-left p { color: #94a3b8; font-size: 1rem; line-height: 1.7; margin-bottom: 2.5rem; }

    .demo-points { list-style: none; display: flex; flex-direction: column; gap: 1rem; }
    .demo-points li { display: flex; gap: 0.75rem; align-items: flex-start; font-size: 0.92rem; }
    .demo-points li .check { width: 20px; height: 20px; border-radius: 50%; background: rgba(15,98,254,0.3); border: 1.5px solid rgba(15,98,254,0.6); display: flex; align-items: center; justify-content: center; flex-shrink: 0; margin-top: 1px; }
    .demo-points li .check::after { content: '✓'; font-size: 0.7rem; color: #60a5fa; font-weight: 700; }
    .demo-points li span { color: #cbd5e1; line-height: 1.5; }

    .demo-right { padding: 70px 8%; background: #fff; display: flex; flex-direction: column; justify-content: center; }
    .demo-right h2 { font-size: 1.4rem; font-weight: 800; margin-bottom: 0.5rem; }
    .demo-right .sub { font-size: 0.9rem; color: var(--muted); margin-bottom: 2rem; }

    .form-row { display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; }
    @media(max-width:500px) { .form-row { grid-template-columns: 1fr; } }
    .form-group { display: flex; flex-direction: column; gap: 0.4rem; margin-bottom: 1rem; }
    .form-group label { font-size: 0.82rem; font-weight: 600; color: var(--text); }
    .form-group input, .form-group select, .form-group textarea {
        border: 1.5px solid var(--border); border-radius: 8px;
        padding: 0.65rem 0.9rem; font-size: 0.9rem; color: var(--text);
        font-family: inherit; background: #fff; transition: border-color 0.2s;
        width: 100%;
    }
    .form-group input:focus, .form-group select:focus, .form-group textarea:focus {
        outline: none; border-color: var(--blue);
    }
    .form-group textarea { resize: vertical; min-height: 90px; }

    .success-box { background: #f0fdf4; border: 1px solid #bbf7d0; border-radius: 10px; padding: 2rem; text-align: center; }
    .success-box h3 { font-size: 1.2rem; font-weight: 700; color: #15803d; margin-bottom: 0.5rem; }
    .success-box p { font-size: 0.9rem; color: #166534; line-height: 1.6; }
</style>
@endsection

@section('content')

<div class="demo-wrap">

    <!-- Left panel -->
    <div class="demo-left">
        <div class="section-label" style="color:#60a5fa;">Free 30-minute demo</div>
        <h1>See ChunkIQ make your <em>Microsoft 365 data</em> AI-searchable</h1>
        <p>We'll walk through your specific Microsoft 365 environment and show you exactly how ChunkIQ fits — no generic slides, no hard sell.</p>

        <ul class="demo-points">
            <li><div class="check"></div><span>Live walkthrough of SharePoint, Teams, OneDrive, and OneNote ingestion</span></li>
            <li><div class="check"></div><span>See how your Azure tenant stays fully in control of the data</span></li>
            <li><div class="check"></div><span>Understand the pipeline architecture and deployment model</span></li>
            <li><div class="check"></div><span>Get a tailored recommendation for your organisation's data volume</span></li>
            <li><div class="check"></div><span>Q&amp;A with the engineering team — no sales middlemen</span></li>
        </ul>
    </div>

    <!-- Right panel — form -->
    <div class="demo-right">

        @if(session('demo_submitted'))
        <div class="success-box">
            <div style="font-size:2.5rem;margin-bottom:0.75rem;">✅</div>
            <h3>Request received!</h3>
            <p>Thanks {{ session('demo_name') }}. We'll be in touch within one business day to schedule your demo at a time that works for you.</p>
        </div>
        @else

        <h2>Request your demo</h2>
        <p class="sub">We respond within one business day.</p>

        @if($errors->any())
            <div style="background:#fef2f2;border:1px solid #fecaca;border-radius:8px;padding:0.9rem 1rem;margin-bottom:1.25rem;font-size:0.875rem;color:#dc2626;">
                {{ $errors->first() }}
            </div>
        @endif

        <form method="POST" action="{{ url('/demo') }}">
            @csrf
            <div class="form-row">
                <div class="form-group">
                    <label>First name *</label>
                    <input type="text" name="first_name" value="{{ old('first_name') }}" required placeholder="Jane">
                </div>
                <div class="form-group">
                    <label>Last name *</label>
                    <input type="text" name="last_name" value="{{ old('last_name') }}" required placeholder="Smith">
                </div>
            </div>
            <div class="form-group">
                <label>Work email *</label>
                <input type="email" name="email" value="{{ old('email') }}" required placeholder="jane@company.com">
            </div>
            <div class="form-group">
                <label>Company *</label>
                <input type="text" name="company" value="{{ old('company') }}" required placeholder="Contoso Ltd">
            </div>
            <div class="form-group">
                <label>Your role</label>
                <select name="role">
                    <option value="">Select a role</option>
                    <option value="CTO / VP Engineering" {{ old('role') === 'CTO / VP Engineering' ? 'selected' : '' }}>CTO / VP Engineering</option>
                    <option value="IT Director / Manager" {{ old('role') === 'IT Director / Manager' ? 'selected' : '' }}>IT Director / Manager</option>
                    <option value="Chief Data Officer" {{ old('role') === 'Chief Data Officer' ? 'selected' : '' }}>Chief Data Officer</option>
                    <option value="Enterprise Architect" {{ old('role') === 'Enterprise Architect' ? 'selected' : '' }}>Enterprise Architect</option>
                    <option value="AI / Data Engineer" {{ old('role') === 'AI / Data Engineer' ? 'selected' : '' }}>AI / Data Engineer</option>
                    <option value="Other" {{ old('role') === 'Other' ? 'selected' : '' }}>Other</option>
                </select>
            </div>
            <div class="form-group">
                <label>What are you trying to solve?</label>
                <textarea name="message" placeholder="e.g. We have 5 years of SharePoint content and want to build an AI search / Copilot on top of it...">{{ old('message') }}</textarea>
            </div>
            <button type="submit" class="btn btn-primary" style="width:100%;justify-content:center;padding:0.9rem;font-size:1rem;">
                Request Demo →
            </button>
            <p style="font-size:0.78rem;color:var(--muted);margin-top:0.75rem;text-align:center;">No spam. No sales calls without your consent.</p>
        </form>
        @endif
    </div>

</div>

@endsection
