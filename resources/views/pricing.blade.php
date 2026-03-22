@extends('layouts.public')

@section('title', 'Pricing — ChunkIQ')

@section('styles')
<style>
    .pricing-hero { padding: 80px 5% 60px; text-align: center; background: var(--light); border-bottom: 1px solid var(--border); }
    .pricing-hero h1 { font-size: clamp(2rem, 4vw, 3rem); font-weight: 800; letter-spacing: -0.5px; margin-bottom: 1rem; }
    .pricing-hero p { font-size: 1.1rem; color: var(--muted); max-width: 560px; margin: 0 auto; line-height: 1.7; }

    .pricing-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 2rem;
        padding: 80px 5%;
        max-width: 1100px;
        margin: 0 auto;
    }
    @media (max-width: 860px) {
        .pricing-grid { grid-template-columns: 1fr; max-width: 480px; }
    }

    .plan { background: #fff; border: 1px solid var(--border); border-radius: 16px; padding: 2.5rem; display: flex; flex-direction: column; position: relative; }
    .plan.featured { border: 2px solid var(--blue); box-shadow: 0 8px 40px rgba(15,98,254,0.12); }
    .plan-badge { position: absolute; top: -14px; left: 50%; transform: translateX(-50%); background: var(--blue); color: #fff; font-size: 0.72rem; font-weight: 700; letter-spacing: 0.8px; text-transform: uppercase; padding: 0.3rem 1rem; border-radius: 20px; white-space: nowrap; }
    .plan-name { font-size: 0.82rem; font-weight: 700; letter-spacing: 1px; text-transform: uppercase; margin-bottom: 0.75rem; }
    .plan-name.free     { color: #64748b; }
    .plan-name.starter  { color: var(--blue); }
    .plan-name.enterprise { color: #7c3aed; }
    .plan-price { font-size: 2.8rem; font-weight: 800; letter-spacing: -1px; line-height: 1; margin-bottom: 0.4rem; }
    .plan-price span { font-size: 1rem; font-weight: 500; color: var(--muted); }
    .plan-desc { font-size: 0.9rem; color: var(--muted); margin-bottom: 2rem; line-height: 1.6; padding-bottom: 2rem; border-bottom: 1px solid var(--border); }
    .plan-features { list-style: none; flex: 1; margin-bottom: 2rem; }
    .plan-features li { font-size: 0.9rem; color: var(--text); padding: 0.5rem 0; display: flex; gap: 0.6rem; align-items: flex-start; }
    .plan-features li::before { content: '✓'; font-weight: 700; flex-shrink: 0; }
    .plan-features li.free-check::before    { color: #64748b; }
    .plan-features li.starter-check::before { color: var(--blue); }
    .plan-features li.ent-check::before     { color: #7c3aed; }
    .plan-features li.muted { color: var(--muted); }
    .plan-features li.muted::before { content: '—'; color: #cbd5e1; }
    .plan-cta { margin-top: auto; }

    .faq { max-width: 720px; margin: 0 auto; padding: 0 5% 80px; }
    .faq h2 { font-size: 1.8rem; font-weight: 800; margin-bottom: 2rem; text-align: center; }
    .faq-item { border-bottom: 1px solid var(--border); padding: 1.5rem 0; }
    .faq-q { font-weight: 700; margin-bottom: 0.5rem; }
    .faq-a { font-size: 0.92rem; color: var(--muted); line-height: 1.7; }
</style>
@endsection

@section('content')

<div class="pricing-hero">
    <div class="section-label">Pricing</div>
    <h1>Simple, transparent pricing</h1>
    <p>All plans run entirely within your own Azure tenant. You own your data — ChunkIQ never stores or processes your content on our infrastructure.</p>
</div>

<div class="pricing-grid">

    <!-- Free -->
    <div class="plan">
        <div class="plan-name free">Free</div>
        <div class="plan-price">$0<span>/mo</span></div>
        <p class="plan-desc">Get started with no commitment. Perfect for individuals or small teams exploring AI search on their Microsoft 365 data.</p>
        <ul class="plan-features">
            <li class="free-check">1 workspace</li>
            <li class="free-check">1 connector (SharePoint, Teams, OneDrive, or OneNote)</li>
            <li class="free-check">Up to 100 documents indexed</li>
            <li class="free-check">Manual pipeline runs</li>
            <li class="free-check">Azure AI Search integration</li>
            <li class="muted">Scheduled pipeline runs</li>
            <li class="muted">Multiple connectors</li>
            <li class="muted">Priority support</li>
        </ul>
        <div class="plan-cta">
            <a href="{{ route('register') }}" class="btn btn-outline" style="width:100%;justify-content:center;padding:0.85rem;">
                Get started free
            </a>
        </div>
    </div>

    <!-- Starter (featured) -->
    <div class="plan featured">
        <div class="plan-badge">Most Popular</div>
        <div class="plan-name starter">Starter</div>
        <div class="plan-price">$299<span>/mo</span></div>
        <p class="plan-desc">For teams ready to scale. All four connectors, high document volume, and scheduled pipelines — no contracts.</p>
        <ul class="plan-features">
            <li class="starter-check">1 workspace</li>
            <li class="starter-check">All 4 connectors (SharePoint, Teams, OneDrive, OneNote)</li>
            <li class="starter-check">Up to 50,000 documents indexed</li>
            <li class="starter-check">Scheduled pipeline runs</li>
            <li class="starter-check">Azure AI Search integration</li>
            <li class="starter-check">Azure OpenAI embeddings</li>
            <li class="starter-check">Email support</li>
            <li class="muted">Dedicated onboarding</li>
        </ul>
        <div class="plan-cta">
            <a href="{{ route('subscribe.show', ['plan' => 'starter']) }}" class="btn btn-primary" style="width:100%;justify-content:center;padding:0.85rem;">
                Subscribe — $299/mo
            </a>
        </div>
    </div>

    <!-- Enterprise -->
    <div class="plan">
        <div class="plan-name enterprise">Enterprise</div>
        <div class="plan-price" style="font-size:2rem;">Custom</div>
        <p class="plan-desc">Dedicated installation within your own Azure subscription. Built for large organisations with compliance and scale requirements.</p>
        <ul class="plan-features">
            <li class="ent-check">Dedicated Azure deployment</li>
            <li class="ent-check">Unlimited workspaces &amp; connectors</li>
            <li class="ent-check">Unlimited documents</li>
            <li class="ent-check">Custom pipeline schedules &amp; triggers</li>
            <li class="ent-check">Private Azure Function App</li>
            <li class="ent-check">SOC 2 / ISO 27001 compliance support</li>
            <li class="ent-check">Custom SLA (99.9% uptime)</li>
            <li class="ent-check">Dedicated customer success manager</li>
        </ul>
        <div class="plan-cta">
            <a href="{{ route('subscribe.show', ['plan' => 'enterprise']) }}" class="btn btn-outline" style="width:100%;justify-content:center;padding:0.85rem;border-color:#7c3aed;color:#7c3aed;">
                Contact Sales
            </a>
        </div>
    </div>

</div>

<!-- FAQ -->
<div class="faq">
    <h2>Frequently asked questions</h2>

    <div class="faq-item">
        <p class="faq-q">Does ChunkIQ store my data?</p>
        <p class="faq-a">No. ChunkIQ deploys entirely within your Azure subscription. Your documents are ingested from Microsoft 365 into your own Azure Data Lake Storage, processed by your Azure Function App, and indexed into your Azure AI Search instance. ChunkIQ never has access to your content.</p>
    </div>

    <div class="faq-item">
        <p class="faq-q">What Azure services do I need?</p>
        <p class="faq-a">You need an Azure subscription with: Azure Data Lake Storage Gen2, Azure Functions (Consumption plan), Azure OpenAI (or your own embeddings endpoint), and Azure AI Search. We provide a setup guide and can assist with provisioning during onboarding.</p>
    </div>

    <div class="faq-item">
        <p class="faq-q">What counts as a "document"?</p>
        <p class="faq-a">Any file ingested and indexed by ChunkIQ counts as one document — PDFs, Word files, PowerPoints, Excel sheets, HTML, plain text, and more. The count reflects total documents processed across your workspace.</p>
    </div>

    <div class="faq-item">
        <p class="faq-q">Can I upgrade or cancel anytime?</p>
        <p class="faq-a">Yes. The Starter plan is month-to-month with no contracts. Contact us to upgrade, downgrade, or cancel. Enterprise plans have custom terms agreed at signup.</p>
    </div>

    <div class="faq-item">
        <p class="faq-q">How long does setup take?</p>
        <p class="faq-a">Most customers complete setup and run their first pipeline within 30–60 minutes. Enterprise deployments typically take 1–2 days with our team's assistance.</p>
    </div>

    <div class="faq-item">
        <p class="faq-q">What is a dedicated installation?</p>
        <p class="faq-a">Enterprise customers get their own isolated deployment of ChunkIQ's Function App and processing infrastructure inside their Azure subscription — no shared compute, complete network isolation, and full control over updates and configuration.</p>
    </div>
</div>

<!-- CTA -->
<section class="cta-section">
    <h2>Not sure which plan is right for you?</h2>
    <p>Book a 30-minute call with our team. We'll assess your data environment and recommend the right fit.</p>
    <a href="{{ url('/demo') }}" class="btn btn-white btn-lg">Book a free consultation</a>
</section>

@endsection
