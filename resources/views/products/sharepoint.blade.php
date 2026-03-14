@extends('layouts.public')

@section('title', 'SharePoint Extractor — ChunkIQ')

@section('styles')
<style>
    .product-hero {
        background: linear-gradient(135deg, #001141 0%, #0d2757 60%, #163a6e 100%);
        color: #fff; padding: 90px 5% 80px; text-align: center;
    }
    .product-badge {
        display: inline-flex; align-items: center; gap: 0.5rem;
        background: rgba(15,98,254,0.25); border: 1px solid rgba(15,98,254,0.5);
        color: #93c5fd; font-size: 0.78rem; font-weight: 600; letter-spacing: 0.8px;
        text-transform: uppercase; padding: 0.35rem 1rem; border-radius: 20px; margin-bottom: 1.5rem;
    }
    .product-hero h1 { font-size: clamp(2rem, 4.5vw, 3.2rem); font-weight: 800; line-height: 1.15; letter-spacing: -1px; max-width: 760px; margin: 0 auto 1.2rem; }
    .product-hero h1 em { font-style: normal; color: #60a5fa; }
    .product-hero p { font-size: 1.1rem; color: #94a3b8; max-width: 580px; margin: 0 auto 2.5rem; line-height: 1.7; }
    .hero-cta { display: flex; gap: 1rem; justify-content: center; flex-wrap: wrap; }
    .hero-icon { font-size: 4rem; margin-bottom: 1.5rem; }

    .stat-row { display: flex; gap: 3rem; justify-content: center; margin-top: 3rem; flex-wrap: wrap; }
    .stat-item { text-align: center; }
    .stat-item .num { font-size: 1.8rem; font-weight: 800; color: #fff; }
    .stat-item .lbl { font-size: 0.8rem; color: #64748b; margin-top: 0.2rem; }

    .formats-section { padding: 70px 5%; background: var(--light); }
    .formats-grid { display: flex; flex-wrap: wrap; gap: 0.75rem; margin-top: 2rem; justify-content: center; }
    .format-pill {
        display: flex; align-items: center; gap: 0.5rem;
        background: #fff; border: 1px solid var(--border); border-radius: 100px;
        padding: 0.5rem 1.1rem; font-size: 0.85rem; font-weight: 700;
        color: var(--blue); box-shadow: 0 1px 4px rgba(0,0,0,0.05);
    }
    .format-pill small { font-weight: 400; color: var(--muted); }

    .sources-detail { display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 1.5rem; margin-top: 3rem; }
    .detail-card { background: #fff; border: 1px solid var(--border); border-radius: 12px; padding: 1.8rem; }
    .detail-card h3 { font-size: 1rem; font-weight: 700; margin-bottom: 0.75rem; display: flex; align-items: center; gap: 0.5rem; }
    .detail-card ul { list-style: none; display: flex; flex-direction: column; gap: 0.5rem; }
    .detail-card ul li { font-size: 0.875rem; color: var(--muted); display: flex; align-items: flex-start; gap: 0.5rem; }
    .detail-card ul li::before { content: '✓'; color: var(--blue); font-weight: 700; flex-shrink: 0; }
</style>
@endsection

@section('content')

<!-- Hero -->
<section class="product-hero">
    <div class="hero-icon">📁</div>
    <div class="product-badge">Microsoft 365 Connector</div>
    <h1>SharePoint <em>Extractor</em></h1>
    <p>Connect to any SharePoint site, crawl document libraries, and extract every file — PDFs, Word docs, Excel sheets, PowerPoints, and more — directly into the ChunkIQ pipeline.</p>
    <div class="hero-cta">
        @if (Route::has('register'))
            <a href="{{ route('register') }}" class="btn btn-primary btn-lg">Get started free</a>
        @endif
        <a href="{{ url('/') }}#how-it-works" class="btn btn-lg" style="color:#fff;border:1.5px solid rgba(255,255,255,0.4);">See the pipeline</a>
    </div>
    <div class="stat-row">
        <div class="stat-item"><div class="num">7+</div><div class="lbl">File formats</div></div>
        <div class="stat-item"><div class="num">∞</div><div class="lbl">Sites & libraries</div></div>
        <div class="stat-item"><div class="num">Graph API</div><div class="lbl">Authentication</div></div>
        <div class="stat-item"><div class="num">100%</div><div class="lbl">Python extraction</div></div>
    </div>
</section>

<!-- Features -->
<section>
    <div class="center">
        <div class="section-label">Capabilities</div>
        <h2 class="section-title">Everything from every SharePoint library</h2>
        <p class="section-sub">Automatic discovery, recursive crawling, and format-specific extraction — all without leaving your Azure tenant.</p>
    </div>
    <div class="features-grid">
        <div class="feature-card">
            <div class="feature-icon">🔌</div>
            <h3>Microsoft Graph API Integration</h3>
            <p>Authenticates via Azure AD app registration using client credentials. Discovers all document libraries across every site collection automatically.</p>
        </div>
        <div class="feature-card">
            <div class="feature-icon">📂</div>
            <h3>Recursive Library Crawling</h3>
            <p>Traverses nested folder structures of any depth. Captures full file paths, modification dates, and author metadata for every item.</p>
        </div>
        <div class="feature-card">
            <div class="feature-icon">📎</div>
            <h3>Embedded Attachment Extraction</h3>
            <p>Detects and extracts files embedded inside Word, Excel, and PowerPoint documents. Each attachment is processed and indexed with lineage back to its parent file.</p>
        </div>
        <div class="feature-card">
            <div class="feature-icon">🔄</div>
            <h3>Incremental Sync</h3>
            <p>Content hashing ensures only new or modified files are re-processed on subsequent runs. Keeps the index fresh without reprocessing unchanged content.</p>
        </div>
        <div class="feature-card">
            <div class="feature-icon">🗺️</div>
            <h3>Rich Provenance Metadata</h3>
            <p>Every chunk is tagged with site URL, library name, folder path, file name, page number, chunk index, content type, and last-modified timestamp.</p>
        </div>
        <div class="feature-card">
            <div class="feature-icon">🔒</div>
            <h3>Stays in Your Tenant</h3>
            <p>Files are ingested directly to Azure Data Lake Storage Gen2 within your own subscription. No data leaves your Azure environment at any point.</p>
        </div>
    </div>
</section>

<!-- Supported Formats -->
<section class="formats-section">
    <div class="center">
        <div class="section-label">Supported Formats</div>
        <h2 class="section-title">Every Office format, natively extracted</h2>
        <p class="section-sub">Pure Python extraction — no Azure Document Intelligence or OCR service required.</p>
        <div class="formats-grid">
            <div class="format-pill">📄 .pdf <small>pypdf</small></div>
            <div class="format-pill">📝 .docx <small>python-docx</small></div>
            <div class="format-pill">📊 .xlsx <small>openpyxl</small></div>
            <div class="format-pill">📽️ .pptx <small>python-pptx</small></div>
            <div class="format-pill">🧮 .xlsm <small>openpyxl + VBA strip</small></div>
            <div class="format-pill">📊 .xls <small>openpyxl</small></div>
            <div class="format-pill">📋 .csv <small>csv module</small></div>
            <div class="format-pill">📃 .txt / .md <small>utf-8 decode</small></div>
        </div>
    </div>
</section>

<!-- How it works -->
<section id="how-it-works">
    <div class="center">
        <div class="section-label">How it works</div>
        <h2 class="section-title">From SharePoint to searchable index in 4 steps</h2>
    </div>
    <div class="steps">
        <div class="step">
            <div class="step-num">Step 01</div>
            <div class="step-icon">🔑</div>
            <h3>Authenticate</h3>
            <p>ChunkIQ authenticates to your Microsoft 365 tenant via an Azure AD app registration with the required SharePoint and Files.Read permissions.</p>
        </div>
        <div class="step">
            <div class="step-num">Step 02</div>
            <div class="step-icon">🔍</div>
            <h3>Discover & Crawl</h3>
            <p>The Graph API enumerates all site collections, document libraries, and folder hierarchies. Files are downloaded to Azure Data Lake Storage Gen2.</p>
        </div>
        <div class="step">
            <div class="step-num">Step 03</div>
            <div class="step-icon">🐍</div>
            <h3>Extract & Chunk</h3>
            <p>Python extractors parse each file format, clean the text, and split it into semantic chunks with tiktoken-based length control.</p>
        </div>
        <div class="step">
            <div class="step-num">Step 04</div>
            <div class="step-icon">⚡</div>
            <h3>Embed & Index</h3>
            <p>Each chunk is embedded with Azure OpenAI and pushed to Azure AI Search for hybrid BM25 + vector + semantic retrieval.</p>
        </div>
    </div>
</section>

<!-- Tech Stack -->
<section class="tech-bg">
    <div class="center" style="color:#fff;">
        <div class="section-label" style="color:#60a5fa;">Under the hood</div>
        <h2 class="section-title">Built on Microsoft Graph + Azure</h2>
    </div>
    <div class="tech-grid">
        <div class="tech-card"><div class="label">Ingest API</div><div class="value">Microsoft Graph API v1.0</div></div>
        <div class="tech-card"><div class="label">Auth</div><div class="value">Azure AD — Client Credentials</div></div>
        <div class="tech-card"><div class="label">Storage</div><div class="value">Azure Data Lake Storage Gen2</div></div>
        <div class="tech-card"><div class="label">Word Extraction</div><div class="value">python-docx</div></div>
        <div class="tech-card"><div class="label">Excel Extraction</div><div class="value">openpyxl</div></div>
        <div class="tech-card"><div class="label">PDF Extraction</div><div class="value">pypdf</div></div>
        <div class="tech-card"><div class="label">PowerPoint</div><div class="value">python-pptx</div></div>
        <div class="tech-card"><div class="label">Chunking</div><div class="value">Hybrid chunker + tiktoken</div></div>
    </div>
</section>

<!-- CTA -->
<section class="cta-section">
    <h2>Start extracting your SharePoint content</h2>
    <p>Connect your Microsoft 365 tenant and have your SharePoint documents extracted, chunked, and indexed in minutes.</p>
    <div style="display:flex;gap:1rem;justify-content:center;flex-wrap:wrap;">
        @if (Route::has('register'))
            <a href="{{ route('register') }}" class="btn btn-white btn-lg">Create your account</a>
        @endif
        <a href="{{ url('/products/pipeline') }}" class="btn btn-lg" style="color:#fff;border:1.5px solid rgba(255,255,255,0.5);">View full pipeline →</a>
    </div>
</section>

@endsection
