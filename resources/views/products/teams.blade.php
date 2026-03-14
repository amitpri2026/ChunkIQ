@extends('layouts.public')

@section('title', 'Teams Extractor — ChunkIQ')

@section('styles')
<style>
    .product-hero {
        background: linear-gradient(135deg, #1a0533 0%, #2d1054 60%, #3b1f72 100%);
        color: #fff; padding: 90px 5% 80px; text-align: center;
    }
    .product-badge {
        display: inline-flex; align-items: center; gap: 0.5rem;
        background: rgba(139,92,246,0.25); border: 1px solid rgba(139,92,246,0.5);
        color: #c4b5fd; font-size: 0.78rem; font-weight: 600; letter-spacing: 0.8px;
        text-transform: uppercase; padding: 0.35rem 1rem; border-radius: 20px; margin-bottom: 1.5rem;
    }
    .product-hero h1 { font-size: clamp(2rem, 4.5vw, 3.2rem); font-weight: 800; line-height: 1.15; letter-spacing: -1px; max-width: 760px; margin: 0 auto 1.2rem; }
    .product-hero h1 em { font-style: normal; color: #a78bfa; }
    .product-hero p { font-size: 1.1rem; color: #94a3b8; max-width: 580px; margin: 0 auto 2.5rem; line-height: 1.7; }
    .hero-cta { display: flex; gap: 1rem; justify-content: center; flex-wrap: wrap; }
    .hero-icon { font-size: 4rem; margin-bottom: 1.5rem; }

    .stat-row { display: flex; gap: 3rem; justify-content: center; margin-top: 3rem; flex-wrap: wrap; }
    .stat-item { text-align: center; }
    .stat-item .num { font-size: 1.8rem; font-weight: 800; color: #fff; }
    .stat-item .lbl { font-size: 0.8rem; color: #64748b; margin-top: 0.2rem; }

    .channel-types { display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 1.25rem; margin-top: 2.5rem; }
    .channel-card { background: #fff; border: 1px solid var(--border); border-radius: 12px; padding: 1.5rem; display: flex; align-items: flex-start; gap: 1rem; }
    .channel-card-icon { font-size: 1.8rem; flex-shrink: 0; }
    .channel-card h3 { font-size: 0.95rem; font-weight: 700; margin-bottom: 0.3rem; }
    .channel-card p { font-size: 0.83rem; color: var(--muted); line-height: 1.55; }
</style>
@endsection

@section('content')

<!-- Hero -->
<section class="product-hero">
    <div class="hero-icon">💬</div>
    <div class="product-badge">Microsoft 365 Connector</div>
    <h1>Teams <em>Extractor</em></h1>
    <p>Automatically discover and extract every file shared across Microsoft Teams channels, team sites, and private chats — without any manual configuration per team.</p>
    <div class="hero-cta">
        @if (Route::has('register'))
            <a href="{{ route('register') }}" class="btn btn-primary btn-lg">Get started free</a>
        @endif
        <a href="{{ url('/') }}#how-it-works" class="btn btn-lg" style="color:#fff;border:1.5px solid rgba(255,255,255,0.4);">See the pipeline</a>
    </div>
    <div class="stat-row">
        <div class="stat-item"><div class="num">Auto</div><div class="lbl">Team discovery</div></div>
        <div class="stat-item"><div class="num">3</div><div class="lbl">Channel types</div></div>
        <div class="stat-item"><div class="num">Graph API</div><div class="lbl">Authentication</div></div>
        <div class="stat-item"><div class="num">100%</div><div class="lbl">Python extraction</div></div>
    </div>
</section>

<!-- Channel types -->
<section class="how-bg">
    <div class="center">
        <div class="section-label">Coverage</div>
        <h2 class="section-title">Every Teams file surface, covered</h2>
        <p class="section-sub">ChunkIQ discovers all teams in your tenant and indexes files across all channel types automatically.</p>
    </div>
    <div class="channel-types">
        <div class="channel-card">
            <div class="channel-card-icon">📢</div>
            <div>
                <h3>Standard Channels</h3>
                <p>Files posted or shared in public channels. Automatically maps the underlying SharePoint team site and crawls the Files tab library.</p>
            </div>
        </div>
        <div class="channel-card">
            <div class="channel-card-icon">🔒</div>
            <div>
                <h3>Private Channels</h3>
                <p>Private channels with dedicated SharePoint sites. Each is discovered and crawled separately with appropriate permission scopes.</p>
            </div>
        </div>
        <div class="channel-card">
            <div class="channel-card-icon">🌐</div>
            <div>
                <h3>Shared Channels</h3>
                <p>Cross-tenant shared channels and their associated document libraries are enumerated and included in the extraction run.</p>
            </div>
        </div>
        <div class="channel-card">
            <div class="channel-card-icon">🏢</div>
            <div>
                <h3>Team Sites (SharePoint)</h3>
                <p>Every Microsoft Team has a backing SharePoint site. ChunkIQ crawls all document libraries on these sites, not just the default Files tab.</p>
            </div>
        </div>
    </div>
</section>

<!-- Features -->
<section>
    <div class="center">
        <div class="section-label">Capabilities</div>
        <h2 class="section-title">Built for large, complex Teams environments</h2>
        <p class="section-sub">No per-team configuration needed. Add ChunkIQ once and every current and future team is covered.</p>
    </div>
    <div class="features-grid">
        <div class="feature-card">
            <div class="feature-icon">🔭</div>
            <h3>Automatic Team Discovery</h3>
            <p>Uses the Microsoft Graph API to enumerate every team in the tenant. New teams added after initial setup are automatically included on the next run.</p>
        </div>
        <div class="feature-card">
            <div class="feature-icon">📂</div>
            <h3>Deep Library Crawl</h3>
            <p>Goes beyond the default Files tab to discover custom document libraries, wiki content libraries, and any other SharePoint libraries on the team site.</p>
        </div>
        <div class="feature-card">
            <div class="feature-icon">📎</div>
            <h3>Attachment Extraction</h3>
            <p>Extracts and processes files embedded inside Word, Excel, and PowerPoint documents found in Teams, tracking lineage back to the parent file and channel.</p>
        </div>
        <div class="feature-card">
            <div class="feature-icon">🗺️</div>
            <h3>Teams-Aware Metadata</h3>
            <p>Each chunk is tagged with team name, channel name, site URL, library, file path, and modification date — enabling fine-grained filtering in search results.</p>
        </div>
        <div class="feature-card">
            <div class="feature-icon">🔄</div>
            <h3>Incremental Updates</h3>
            <p>Content hash deduplication means only new or changed files are re-extracted. Large tenants with thousands of files process efficiently on every run.</p>
        </div>
        <div class="feature-card">
            <div class="feature-icon">🔒</div>
            <h3>Secure by Design</h3>
            <p>Runs entirely within your Azure tenant. Uses managed identity or service principal with the minimum required Graph API permissions.</p>
        </div>
    </div>
</section>

<!-- How it works -->
<section class="how-bg" id="how-it-works">
    <div class="center">
        <div class="section-label">How it works</div>
        <h2 class="section-title">From Teams to searchable index in 4 steps</h2>
    </div>
    <div class="steps">
        <div class="step">
            <div class="step-num">Step 01</div>
            <div class="step-icon">🔑</div>
            <h3>Authenticate</h3>
            <p>Authenticates via Azure AD with Group.Read.All and Files.Read.All permissions to access all teams and their underlying SharePoint sites.</p>
        </div>
        <div class="step">
            <div class="step-num">Step 02</div>
            <div class="step-icon">🔍</div>
            <h3>Discover Teams & Sites</h3>
            <p>Enumerates all teams, resolves their SharePoint team sites, and lists every document library — standard, private, and shared channels included.</p>
        </div>
        <div class="step">
            <div class="step-num">Step 03</div>
            <div class="step-icon">🐍</div>
            <h3>Extract & Chunk</h3>
            <p>Python extractors process each file format. Text is cleaned, split into semantic chunks, and enriched with Teams-specific provenance metadata.</p>
        </div>
        <div class="step">
            <div class="step-num">Step 04</div>
            <div class="step-icon">⚡</div>
            <h3>Embed & Index</h3>
            <p>Chunks are embedded with Azure OpenAI and pushed to Azure AI Search for hybrid BM25 + vector + semantic search across all Teams content.</p>
        </div>
    </div>
</section>

<!-- Tech Stack -->
<section class="tech-bg">
    <div class="center" style="color:#fff;">
        <div class="section-label" style="color:#a78bfa;">Under the hood</div>
        <h2 class="section-title">Built on Microsoft Graph + Azure</h2>
    </div>
    <div class="tech-grid">
        <div class="tech-card"><div class="label">Teams API</div><div class="value">Microsoft Graph — /teams endpoint</div></div>
        <div class="tech-card"><div class="label">Auth Scope</div><div class="value">Group.Read.All · Files.Read.All</div></div>
        <div class="tech-card"><div class="label">Site Resolution</div><div class="value">Graph /groups/{id}/sites/root</div></div>
        <div class="tech-card"><div class="label">Storage</div><div class="value">Azure Data Lake Storage Gen2</div></div>
        <div class="tech-card"><div class="label">Extraction</div><div class="value">python-docx · pypdf · openpyxl · python-pptx</div></div>
        <div class="tech-card"><div class="label">Chunking</div><div class="value">Hybrid chunker + tiktoken</div></div>
        <div class="tech-card"><div class="label">Embeddings</div><div class="value">Azure OpenAI text-embedding-3-small</div></div>
        <div class="tech-card"><div class="label">Search</div><div class="value">Azure AI Search (Hybrid + Semantic)</div></div>
    </div>
</section>

<!-- CTA -->
<section class="cta-section">
    <h2>Index your entire Teams workspace</h2>
    <p>One setup. Every team. Every channel. Every file — automatically extracted and ready for AI-powered search.</p>
    <div style="display:flex;gap:1rem;justify-content:center;flex-wrap:wrap;">
        @if (Route::has('register'))
            <a href="{{ route('register') }}" class="btn btn-white btn-lg">Create your account</a>
        @endif
        <a href="{{ url('/products/pipeline') }}" class="btn btn-lg" style="color:#fff;border:1.5px solid rgba(255,255,255,0.5);">View full pipeline →</a>
    </div>
</section>

@endsection
