@extends('layouts.public')

@section('title', 'Pricing — ChunkIQ')

@section('styles')
<style>
    .pricing-hero { padding: 80px 5% 60px; text-align: center; background: var(--light); border-bottom: 1px solid var(--border); }
    .pricing-hero h1 { font-size: clamp(2rem, 4vw, 3rem); font-weight: 800; letter-spacing: -0.5px; margin-bottom: 1rem; }
    .pricing-hero p { font-size: 1.1rem; color: var(--muted); max-width: 540px; margin: 0 auto; line-height: 1.7; }

    .pricing-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 2rem; padding: 80px 5%; max-width: 1100px; margin: 0 auto; }
    .plan { background: #fff; border: 1px solid var(--border); border-radius: 16px; padding: 2.5rem; display: flex; flex-direction: column; }
    .plan.featured { border: 2px solid var(--blue); position: relative; box-shadow: 0 8px 40px rgba(15,98,254,0.12); }
    .plan-badge { position: absolute; top: -14px; left: 50%; transform: translateX(-50%); background: var(--blue); color: #fff; font-size: 0.72rem; font-weight: 700; letter-spacing: 0.8px; text-transform: uppercase; padding: 0.3rem 1rem; border-radius: 20px; white-space: nowrap; }
    .plan-name { font-size: 0.82rem; font-weight: 700; color: var(--blue); letter-spacing: 1px; text-transform: uppercase; margin-bottom: 0.75rem; }
    .plan-price { font-size: 2.8rem; font-weight: 800; letter-spacing: -1px; line-height: 1; margin-bottom: 0.4rem; }
    .plan-price span { font-size: 1rem; font-weight: 500; color: var(--muted); }
    .plan-desc { font-size: 0.9rem; color: var(--muted); margin-bottom: 2rem; line-height: 1.6; padding-bottom: 2rem; border-bottom: 1px solid var(--border); }
    .plan-features { list-style: none; flex: 1; margin-bottom: 2rem; }
    .plan-features li { font-size: 0.9rem; color: var(--text); padding: 0.5rem 0; display: flex; gap: 0.6rem; align-items: flex-start; }
    .plan-features li::before { content: '✓'; color: var(--blue); font-weight: 700; flex-shrink: 0; }
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

    <!-- Starter -->
    <div class="plan">
        <div class="plan-name">Starter</div>
        <div class="plan-price">$299<span>/mo</span></div>
        <p class="plan-desc">For small teams getting started with AI search on their Microsoft 365 data. Self-serve, no contract.</p>
        <ul class="plan-features">
            <li>1 workspace</li>
            <li>Up to 3 connectors (SharePoint, Teams, OneDrive, OneNote)</li>
            <li>Up to 50,000 documents indexed</li>
            <li>Daily scheduled pipeline runs</li>
            <li>Azure AI Search integration</li>
            <li>Azure OpenAI embeddings</li>
            <li>Email support</li>
            <li class="muted">Custom pipeline schedules</li>
            <li class="muted">Dedicated onboarding</li>
            <li class="muted">SLA guarantee</li>
        </ul>
        <div class="plan-cta">
            <a href="{{ route('register') }}" class="btn btn-outline" style="width:100%;justify-content:center;padding:0.85rem;">Get started</a>
        </div>
    </div>

    <!-- Professional (featured) -->
    <div class="plan featured">
        <div class="plan-badge">Most Popular</div>
        <div class="plan-name">Professional</div>
        <div class="plan-price">$999<span>/mo</span></div>
        <p class="plan-desc">For growing organisations that need multiple workspaces, higher volume, and priority support.</p>
        <ul class="plan-features">
            <li>Up to 5 workspaces</li>
            <li>Unlimited connectors</li>
            <li>Up to 500,000 documents indexed</li>
            <li>Custom pipeline schedules</li>
            <li>Azure AI Search + hybrid semantic re-ranking</li>
            <li>Azure OpenAI embeddings (bring your own key)</li>
            <li>Full metadata & provenance tracking</li>
            <li>Priority email & chat support</li>
            <li>Dedicated onboarding session</li>
            <li class="muted">SLA guarantee</li>
        </ul>
        <div class="plan-cta">
            <a href="{{ url('/demo') }}" class="btn btn-primary" style="width:100%;justify-content:center;padding:0.85rem;">Book a Demo</a>
        </div>
    </div>

    <!-- Enterprise -->
    <div class="plan">
        <div class="plan-name">Enterprise</div>
        <div class="plan-price" style="font-size:2rem;">Custom</div>
        <p class="plan-desc">For large organisations with complex data environments, compliance requirements, and dedicated support needs.</p>
        <ul class="plan-features">
            <li>Unlimited workspaces & connectors</li>
            <li>Unlimited documents</li>
            <li>Custom deployment within your Azure subscription</li>
            <li>Private Azure Function App deployment</li>
            <li>SOC 2 / ISO 27001 compliance support</li>
            <li>Custom SLA (99.9% uptime)</li>
            <li>Dedicated customer success manager</li>
            <li>Custom integrations & connectors</li>
            <li>On-premise deployment option</li>
            <li>Azure Marketplace billing available</li>
        </ul>
        <div class="plan-cta">
            <a href="{{ url('/demo') }}" class="btn btn-outline" style="width:100%;justify-content:center;padding:0.85rem;">Contact Sales</a>
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
        <p class="faq-a">You need an Azure subscription with: Azure Data Lake Storage Gen2, Azure Functions (Consumption plan), Azure OpenAI (or bring your own embeddings endpoint), and Azure AI Search. We provide a setup guide and can assist with provisioning during onboarding.</p>
    </div>

    <div class="faq-item">
        <p class="faq-q">Do I need to change anything in Microsoft 365?</p>
        <p class="faq-a">You need to create an Azure AD App Registration with the appropriate permissions (read access to SharePoint, Teams, OneDrive, OneNote). Our onboarding guide walks you through this in under 10 minutes.</p>
    </div>

    <div class="faq-item">
        <p class="faq-q">How long does setup take?</p>
        <p class="faq-a">Most customers complete setup and run their first pipeline within 30–60 minutes. Enterprise deployments with custom configurations typically take 1–2 days with our team's assistance.</p>
    </div>

    <div class="faq-item">
        <p class="faq-q">Can I upgrade or cancel anytime?</p>
        <p class="faq-a">Starter and Professional plans are month-to-month with no contracts. You can upgrade, downgrade, or cancel at any time from your account settings. Enterprise plans are annual contracts with custom terms.</p>
    </div>
</div>

<!-- CTA -->
<section class="cta-section">
    <h2>Not sure which plan is right for you?</h2>
    <p>Book a 30-minute call with our team. We'll assess your data environment and recommend the right fit.</p>
    <a href="{{ url('/demo') }}" class="btn btn-white btn-lg">Book a free consultation</a>
</section>

@endsection
