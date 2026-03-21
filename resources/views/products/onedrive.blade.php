@extends('layouts.public')

@section('title', 'OneDrive Extractor — ChunkIQ')

@section('styles')
<style>
    .product-hero {
        background: linear-gradient(135deg, #001a33 0%, #002f5c 60%, #003d75 100%);
        color: #fff; padding: 90px 5% 80px; text-align: center;
    }
    .product-badge {
        display: inline-flex; align-items: center; gap: 0.5rem;
        background: rgba(56,189,248,0.2); border: 1px solid rgba(56,189,248,0.4);
        color: #7dd3fc; font-size: 0.78rem; font-weight: 600; letter-spacing: 0.8px;
        text-transform: uppercase; padding: 0.35rem 1rem; border-radius: 20px; margin-bottom: 1.5rem;
    }
    .product-hero h1 { font-size: clamp(2rem, 4.5vw, 3.2rem); font-weight: 800; line-height: 1.15; letter-spacing: -1px; max-width: 760px; margin: 0 auto 1.2rem; }
    .product-hero h1 em { font-style: normal; color: #7dd3fc; }
    .product-hero p { font-size: 1.1rem; color: #94a3b8; max-width: 580px; margin: 0 auto 2.5rem; line-height: 1.7; }
    .hero-cta { display: flex; gap: 1rem; justify-content: center; flex-wrap: wrap; }
    .hero-icon { font-size: 4rem; margin-bottom: 1.5rem; }

    .stat-row { display: flex; gap: 3rem; justify-content: center; margin-top: 3rem; flex-wrap: wrap; }
    .stat-item { text-align: center; }
    .stat-item .num { font-size: 1.8rem; font-weight: 800; color: #fff; }
    .stat-item .lbl { font-size: 0.8rem; color: #64748b; margin-top: 0.2rem; }

    .drive-types { display: grid; grid-template-columns: repeat(auto-fit, minmax(260px, 1fr)); gap: 1.5rem; margin-top: 3rem; }
    .drive-card { background: #fff; border: 1px solid var(--border); border-radius: 14px; padding: 2rem 1.8rem; transition: box-shadow 0.2s, transform 0.2s; }
    .drive-card:hover { box-shadow: 0 8px 32px rgba(15,98,254,0.1); transform: translateY(-3px); }
    .drive-card-icon { font-size: 2.2rem; margin-bottom: 1rem; }
    .drive-card h3 { font-size: 1.05rem; font-weight: 700; margin-bottom: 0.5rem; }
    .drive-card p { font-size: 0.87rem; color: var(--muted); line-height: 1.6; }
    .live-badge { display: inline-block; margin-top: 0.75rem; font-size: 0.72rem; font-weight: 700; background: #f0fdf4; color: #16a34a; border: 1px solid #bbf7d0; border-radius: 4px; padding: 0.15rem 0.5rem; }
</style>
@endsection

@section('content')

<!-- Hero -->
<section class="product-hero">
    <div class="hero-icon">☁️</div>
    <div class="product-badge">Microsoft 365 Connector</div>
    <h1>OneDrive <em>Extractor</em></h1>
    <p>Connect to personal and shared OneDrive drives across your organisation. Recursively crawl every folder, extract every supported document, and index it for AI-powered search.</p>
    <div class="hero-cta">
        @if (Route::has('register'))
            <a href="{{ route('register') }}" class="btn btn-primary btn-lg">Get started free</a>
        @endif
        <a href="{{ url('/') }}#how-it-works" class="btn btn-lg" style="color:#fff;border:1.5px solid rgba(255,255,255,0.4);">See the pipeline</a>
    </div>
    <div class="stat-row">
        <div class="stat-item"><div class="num">Live</div><div class="lbl">Status</div></div>
        <div class="stat-item"><div class="num">∞</div><div class="lbl">Folder depth</div></div>
        <div class="stat-item"><div class="num">Secure Auth</div><div class="lbl">Authentication</div></div>
        <div class="stat-item"><div class="num">100%</div><div class="lbl">Native extraction</div></div>
    </div>
</section>

<!-- Drive types -->
<section class="how-bg">
    <div class="center">
        <div class="section-label">Drive Coverage</div>
        <h2 class="section-title">Personal drives, shared drives — all covered</h2>
        <p class="section-sub">ChunkIQ accesses every OneDrive drive type securely, with no manual configuration per user.</p>
    </div>
    <div class="drive-types">
        <div class="drive-card">
            <div class="drive-card-icon">👤</div>
            <h3>Personal Drive</h3>
            <p>Each user's personal OneDrive for Business drive. Files stored directly in My Files, including nested folders of any depth, are fully crawled and extracted.</p>
            <span class="live-badge">✓ Live</span>
        </div>
        <div class="drive-card">
            <div class="drive-card-icon">👥</div>
            <h3>Shared Drives</h3>
            <p>Shared drives and document libraries shared with the user. ChunkIQ enumerates all accessible drives and includes them in the extraction run.</p>
            <span class="live-badge">✓ Live</span>
        </div>
        <div class="drive-card">
            <div class="drive-card-icon">📁</div>
            <h3>Nested Folder Structures</h3>
            <p>Traverses folders recursively regardless of nesting depth. Captures the full folder path in metadata so results can be filtered by directory in search.</p>
            <span class="live-badge">✓ Live</span>
        </div>
        <div class="drive-card">
            <div class="drive-card-icon">🔗</div>
            <h3>Shared With Me</h3>
            <p>Files shared with the authenticated user from other drives. ChunkIQ resolves the remote item references and includes them in the extraction queue.</p>
            <span class="live-badge">✓ Live</span>
        </div>
    </div>
</section>

<!-- Features -->
<section>
    <div class="center">
        <div class="section-label">Capabilities</div>
        <h2 class="section-title">Complete OneDrive coverage out of the box</h2>
    </div>
    <div class="features-grid">
        <div class="feature-card">
            <div class="feature-icon">🔄</div>
            <h3>Recursive Folder Crawl</h3>
            <p>ChunkIQ traverses folder hierarchies of unlimited depth using efficient delta queries, capturing every file regardless of where it's stored.</p>
        </div>
        <div class="feature-card">
            <div class="feature-icon">📎</div>
            <h3>Attachment Extraction</h3>
            <p>Files embedded inside Word, Excel, and PowerPoint documents are extracted and indexed separately, each with lineage metadata back to the parent document.</p>
        </div>
        <div class="feature-card">
            <div class="feature-icon">🔁</div>
            <h3>Delta Sync</h3>
            <p>Uses OneDrive delta tokens to track changes since the last run. Only new, modified, or deleted items are processed — making large drives efficient to keep fresh.</p>
        </div>
        <div class="feature-card">
            <div class="feature-icon">🗺️</div>
            <h3>Drive-Aware Metadata</h3>
            <p>Every chunk records the drive ID, drive type, owner, folder path, file name, file size, and last modified date for precise filtering and attribution.</p>
        </div>
        <div class="feature-card">
            <div class="feature-icon">📄</div>
            <h3>All Supported File Types</h3>
            <p>Processes .pdf, .docx, .xlsx, .pptx, .xlsm, .csv, .json, .txt, and .md files found anywhere in the drive structure.</p>
        </div>
        <div class="feature-card">
            <div class="feature-icon">🔒</div>
            <h3>Tenant-Isolated</h3>
            <p>All data is written to Azure Data Lake Storage Gen2 within your own subscription. Managed identity auth, no external data transfers.</p>
        </div>
    </div>
</section>

<!-- How it works -->
<section class="how-bg" id="how-it-works">
    <div class="center">
        <div class="section-label">How it works</div>
        <h2 class="section-title">From OneDrive to searchable index in 4 steps</h2>
    </div>
    <div class="steps">
        <div class="step">
            <div class="step-num">Step 01</div>
            <div class="step-icon">🔑</div>
            <h3>Authenticate</h3>
            <p>Authenticates via Azure AD with Files.Read.All to access all OneDrive drives in the tenant, including personal and shared drives.</p>
        </div>
        <div class="step">
            <div class="step-num">Step 02</div>
            <div class="step-icon">🔍</div>
            <h3>Enumerate Drives & Files</h3>
            <p>Lists all drives for each user, then recursively enumerates folders and files. Delta tokens are stored for efficient subsequent runs.</p>
        </div>
        <div class="step">
            <div class="step-num">Step 03</div>
            <div class="step-icon">📄</div>
            <h3>Extract & Chunk</h3>
            <p>Dedicated extractors process each file type. Text is cleaned, split into semantic chunks, and tagged with full drive/folder provenance metadata.</p>
        </div>
        <div class="step">
            <div class="step-num">Step 04</div>
            <div class="step-icon">⚡</div>
            <h3>Embed & Index</h3>
            <p>Chunks are vectorised with Azure OpenAI and pushed to Azure AI Search for hybrid BM25 + vector + semantic retrieval.</p>
        </div>
    </div>
</section>

<!-- Tech Stack -->
<section class="tech-bg">
    <div class="center" style="color:#fff;">
        <div class="section-label" style="color:#7dd3fc;">Under the hood</div>
        <h2 class="section-title">Built on Microsoft Graph + Azure</h2>
    </div>
    <div class="tech-grid">
        <div class="tech-card"><div class="label">OneDrive API</div><div class="value">Graph /me/drive · /drives endpoints</div></div>
        <div class="tech-card"><div class="label">Auth Scope</div><div class="value">Files.Read.All</div></div>
        <div class="tech-card"><div class="label">Change Tracking</div><div class="value">Graph Delta Query + delta tokens</div></div>
        <div class="tech-card"><div class="label">Storage</div><div class="value">Azure Data Lake Storage Gen2</div></div>
        <div class="tech-card"><div class="label">Document Extraction</div><div class="value">Native format parsers</div></div>
        <div class="tech-card"><div class="label">Chunking</div><div class="value">Hybrid chunker</div></div>
        <div class="tech-card"><div class="label">Search</div><div class="value">Azure AI Search (Hybrid + Semantic)</div></div>
    </div>
</section>

<!-- CTA -->
<section class="cta-section">
    <h2>Index your entire OneDrive estate</h2>
    <p>Personal drives, shared drives, nested folders — all automatically extracted and ready for AI-powered search.</p>
    <div style="display:flex;gap:1rem;justify-content:center;flex-wrap:wrap;">
        @if (Route::has('register'))
            <a href="{{ route('register') }}" class="btn btn-white btn-lg">Create your account</a>
        @endif
        <a href="{{ url('/products/pipeline') }}" class="btn btn-lg" style="color:#fff;border:1.5px solid rgba(255,255,255,0.5);">View full pipeline →</a>
    </div>
</section>

@endsection
