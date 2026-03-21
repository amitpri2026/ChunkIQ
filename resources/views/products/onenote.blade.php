@extends('layouts.public')

@section('title', 'OneNote Extractor — ChunkIQ')

@section('styles')
<style>
    .product-hero {
        background: linear-gradient(135deg, #0f1e0a 0%, #1a3a10 60%, #1f4a14 100%);
        color: #fff; padding: 90px 5% 80px; text-align: center;
    }
    .product-badge {
        display: inline-flex; align-items: center; gap: 0.5rem;
        background: rgba(34,197,94,0.2); border: 1px solid rgba(34,197,94,0.4);
        color: #86efac; font-size: 0.78rem; font-weight: 600; letter-spacing: 0.8px;
        text-transform: uppercase; padding: 0.35rem 1rem; border-radius: 20px; margin-bottom: 1.5rem;
    }
    .product-hero h1 { font-size: clamp(2rem, 4.5vw, 3.2rem); font-weight: 800; line-height: 1.15; letter-spacing: -1px; max-width: 760px; margin: 0 auto 1.2rem; }
    .product-hero h1 em { font-style: normal; color: #86efac; }
    .product-hero p { font-size: 1.1rem; color: #94a3b8; max-width: 580px; margin: 0 auto 2.5rem; line-height: 1.7; }
    .hero-cta { display: flex; gap: 1rem; justify-content: center; flex-wrap: wrap; }
    .hero-icon { font-size: 4rem; margin-bottom: 1.5rem; }

    .stat-row { display: flex; gap: 3rem; justify-content: center; margin-top: 3rem; flex-wrap: wrap; }
    .stat-item { text-align: center; }
    .stat-item .num { font-size: 1.8rem; font-weight: 800; color: #fff; }
    .stat-item .lbl { font-size: 0.8rem; color: #64748b; margin-top: 0.2rem; }

    .hierarchy-row { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 0; margin-top: 3rem; }
    .hier-item { text-align: center; padding: 2rem 1.5rem; border: 1px solid var(--border); background: #fff; position: relative; }
    .hier-item:not(:last-child)::after { content: '→'; position: absolute; right: -0.75rem; top: 50%; transform: translateY(-50%); font-size: 1.2rem; color: var(--muted); z-index: 1; }
    .hier-item:first-child { border-radius: 12px 0 0 12px; }
    .hier-item:last-child { border-radius: 0 12px 12px 0; }
    .hier-icon { font-size: 2rem; margin-bottom: 0.75rem; }
    .hier-item h3 { font-size: 0.95rem; font-weight: 700; margin-bottom: 0.3rem; }
    .hier-item p { font-size: 0.8rem; color: var(--muted); }
    @media (max-width: 640px) {
        .hierarchy-row { grid-template-columns: 1fr; }
        .hier-item:not(:last-child)::after { display: none; }
        .hier-item { border-radius: 0; }
        .hier-item:first-child { border-radius: 12px 12px 0 0; }
        .hier-item:last-child { border-radius: 0 0 12px 12px; }
    }
</style>
@endsection

@section('content')

<!-- Hero -->
<section class="product-hero">
    <div class="hero-icon">📓</div>
    <div class="product-badge">Microsoft 365 Connector</div>
    <h1>OneNote <em>Extractor</em></h1>
    <p>Extract every notebook, section, and page from OneNote — converting rich HTML content to clean searchable text and processing any embedded Office attachments alongside it.</p>
    <div class="hero-cta">
        @if (Route::has('register'))
            <a href="{{ route('register') }}" class="btn btn-primary btn-lg">Get started free</a>
        @endif
        <a href="{{ url('/') }}#how-it-works" class="btn btn-lg" style="color:#fff;border:1.5px solid rgba(255,255,255,0.4);">See the pipeline</a>
    </div>
    <div class="stat-row">
        <div class="stat-item"><div class="num">HTML</div><div class="lbl">Page format</div></div>
        <div class="stat-item"><div class="num">BS4</div><div class="lbl">Text parser</div></div>
        <div class="stat-item"><div class="num">Secure Auth</div><div class="lbl">Authentication</div></div>
        <div class="stat-item"><div class="num">100%</div><div class="lbl">Native extraction</div></div>
    </div>
</section>

<!-- Notebook hierarchy -->
<section class="how-bg">
    <div class="center">
        <div class="section-label">Structure</div>
        <h2 class="section-title">Full notebook hierarchy, fully extracted</h2>
        <p class="section-sub">ChunkIQ traverses the complete OneNote structure — from top-level notebooks all the way to individual pages and their attachments.</p>
    </div>
    <div class="hierarchy-row">
        <div class="hier-item">
            <div class="hier-icon">📚</div>
            <h3>Notebooks</h3>
            <p>All notebooks in the user's account</p>
        </div>
        <div class="hier-item">
            <div class="hier-icon">📑</div>
            <h3>Section Groups</h3>
            <p>Nested section group folders</p>
        </div>
        <div class="hier-item">
            <div class="hier-icon">📋</div>
            <h3>Sections</h3>
            <p>Individual sections within notebooks</p>
        </div>
        <div class="hier-item">
            <div class="hier-icon">📄</div>
            <h3>Pages</h3>
            <p>HTML-rendered page content</p>
        </div>
        <div class="hier-item">
            <div class="hier-icon">📎</div>
            <h3>Attachments</h3>
            <p>Office files embedded in pages</p>
        </div>
    </div>
</section>

<!-- Features -->
<section>
    <div class="center">
        <div class="section-label">Capabilities</div>
        <h2 class="section-title">OneNote content, made searchable</h2>
        <p class="section-sub">OneNote stores pages as HTML — ChunkIQ parses that HTML into clean text while preserving headings, lists, and table structure.</p>
    </div>
    <div class="features-grid">
        <div class="feature-card">
            <div class="feature-icon">🌐</div>
            <h3>HTML-to-Text Parsing</h3>
            <p>OneNote pages are exported as HTML. ChunkIQ strips tags, decodes entities, and extracts clean, readable text with structural context preserved.</p>
        </div>
        <div class="feature-card">
            <div class="feature-icon">📎</div>
            <h3>Embedded Attachment Extraction</h3>
            <p>OneNote pages often contain attached .docx, .xlsx, or .pdf files. ChunkIQ downloads and processes these attachments, linking them back to the parent page.</p>
        </div>
        <div class="feature-card">
            <div class="feature-icon">🗺️</div>
            <h3>Page-Level Metadata</h3>
            <p>Every chunk is tagged with notebook name, section group, section name, page title, creation date, and last-modified timestamp for precise filtering.</p>
        </div>
        <div class="feature-card">
            <div class="feature-icon">🔌</div>
            <h3>Microsoft 365 Integration</h3>
            <p>Uses the OneNote-specific Microsoft Graph endpoints (/me/onenote/notebooks) with Notes.Read permissions for complete, authorised access.</p>
        </div>
        <div class="feature-card">
            <div class="feature-icon">🔄</div>
            <h3>Incremental Sync</h3>
            <p>Pages are identified by their unique Graph ID. On subsequent runs, only pages modified since the last extraction are re-processed.</p>
        </div>
        <div class="feature-card">
            <div class="feature-icon">🔒</div>
            <h3>Works With Class & Personal Notebooks</h3>
            <p>Extracts both personal notebooks and shared notebooks from Microsoft Teams. All content stays within your Azure tenant throughout.</p>
        </div>
    </div>
</section>

<!-- How it works -->
<section class="how-bg" id="how-it-works">
    <div class="center">
        <div class="section-label">How it works</div>
        <h2 class="section-title">From OneNote page to searchable chunk</h2>
    </div>
    <div class="steps">
        <div class="step">
            <div class="step-num">Step 01</div>
            <div class="step-icon">🔑</div>
            <h3>Authenticate</h3>
            <p>Authenticates via Azure AD with Notes.Read (or Notes.Read.All for admin access) to enumerate notebooks across the tenant.</p>
        </div>
        <div class="step">
            <div class="step-num">Step 02</div>
            <div class="step-icon">📚</div>
            <h3>Traverse Hierarchy</h3>
            <p>Enumerates all notebooks → section groups → sections → pages recursively. Downloads each page's HTML content and any file attachments.</p>
        </div>
        <div class="step">
            <div class="step-num">Step 03</div>
            <div class="step-icon">📄</div>
            <h3>Parse & Chunk</h3>
            <p>HTML is parsed into clean text. Embedded attachments are processed by dedicated extractors. All output is chunked and tagged.</p>
        </div>
        <div class="step">
            <div class="step-num">Step 04</div>
            <div class="step-icon">⚡</div>
            <h3>Embed & Index</h3>
            <p>Each chunk is embedded with Azure OpenAI and upserted to Azure AI Search, ready for hybrid semantic search.</p>
        </div>
    </div>
</section>

<!-- Tech Stack -->
<section class="tech-bg">
    <div class="center" style="color:#fff;">
        <div class="section-label" style="color:#86efac;">Under the hood</div>
        <h2 class="section-title">Built for OneNote at scale</h2>
    </div>
    <div class="tech-grid">
        <div class="tech-card"><div class="label">OneNote API</div><div class="value">Graph /me/onenote/notebooks</div></div>
        <div class="tech-card"><div class="label">Auth Scope</div><div class="value">Notes.Read · Notes.Read.All</div></div>
        <div class="tech-card"><div class="label">Page Parser</div><div class="value">Native HTML parser</div></div>
        <div class="tech-card"><div class="label">Attachment Extraction</div><div class="value">Native format parsers</div></div>
        <div class="tech-card"><div class="label">Storage</div><div class="value">Azure Data Lake Storage Gen2</div></div>
        <div class="tech-card"><div class="label">Search</div><div class="value">Azure AI Search (Hybrid + Semantic)</div></div>
    </div>
</section>

<!-- CTA -->
<section class="cta-section">
    <h2>Make your OneNote knowledge searchable</h2>
    <p>Every notebook, every section, every page — automatically extracted and indexed for AI-powered retrieval.</p>
    <div style="display:flex;gap:1rem;justify-content:center;flex-wrap:wrap;">
        @if (Route::has('register'))
            <a href="{{ route('register') }}" class="btn btn-white btn-lg">Create your account</a>
        @endif
        <a href="{{ url('/products/pipeline') }}" class="btn btn-lg" style="color:#fff;border:1.5px solid rgba(255,255,255,0.5);">View full pipeline →</a>
    </div>
</section>

@endsection
