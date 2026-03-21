@extends('layouts.public')

@section('title', 'ChunkIQ — Unstructured Data Pipeline')

@section('styles')
<style>
    /* Hero */
    .hero {
        background: linear-gradient(135deg, var(--navy) 0%, #0d2757 60%, #163a6e 100%);
        color: #fff; padding: 100px 5% 90px; text-align: center;
    }
    .hero-badge {
        display: inline-block; background: rgba(15,98,254,0.25); border: 1px solid rgba(15,98,254,0.5);
        color: #93c5fd; font-size: 0.78rem; font-weight: 600; letter-spacing: 0.8px;
        text-transform: uppercase; padding: 0.35rem 1rem; border-radius: 20px; margin-bottom: 1.5rem;
    }
    .hero h1 { font-size: clamp(2.2rem, 5vw, 3.6rem); font-weight: 800; line-height: 1.15; letter-spacing: -1px; max-width: 820px; margin: 0 auto 1.2rem; }
    .hero h1 em { font-style: normal; color: #60a5fa; }
    .hero p { font-size: 1.15rem; color: #94a3b8; max-width: 600px; margin: 0 auto 2.5rem; line-height: 1.7; }
    .hero-cta { display: flex; gap: 1rem; justify-content: center; flex-wrap: wrap; }
    .hero-stats { display: flex; gap: 3rem; justify-content: center; margin-top: 4rem; flex-wrap: wrap; }
    .stat { text-align: center; }
    .stat-num { font-size: 2rem; font-weight: 800; color: #fff; }
    .stat-label { font-size: 0.82rem; color: #64748b; margin-top: 0.2rem; }

    /* Sources hero bar */
    .source-bar {
        background: rgba(255,255,255,0.05);
        border-top: 1px solid rgba(255,255,255,0.08);
        padding: 1.5rem 5%;
        display: flex; gap: 1rem; justify-content: center; flex-wrap: wrap;
    }
    .source-badge {
        display: flex; align-items: center; gap: 0.5rem;
        background: rgba(255,255,255,0.08); border: 1px solid rgba(255,255,255,0.12);
        color: #cbd5e1; font-size: 0.82rem; font-weight: 600;
        padding: 0.4rem 1rem; border-radius: 100px;
    }

    /* Sources section */
    .sources-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
        gap: 1.5rem; margin-top: 3rem;
    }
    .source-card {
        background: #fff; border-radius: 14px; border: 1px solid var(--border);
        padding: 2rem 1.8rem;
        transition: box-shadow 0.2s, border-color 0.2s, transform 0.2s;
    }
    .source-card:hover { box-shadow: 0 8px 32px rgba(15,98,254,0.10); border-color: #bfdbfe; transform: translateY(-3px); }
    .source-icon { font-size: 2.4rem; margin-bottom: 1rem; }
    .source-card h3 { font-size: 1.1rem; font-weight: 700; margin-bottom: 0.5rem; }
    .source-card p { font-size: 0.87rem; color: var(--muted); line-height: 1.6; margin-bottom: 1rem; }
    .source-types { display: flex; flex-wrap: wrap; gap: 0.4rem; margin-top: 0.75rem; }
    .source-type { font-size: 0.72rem; font-weight: 600; background: #eff6ff; color: var(--blue); border-radius: 4px; padding: 0.2rem 0.5rem; letter-spacing: 0.3px; }
    .source-badge-new { font-size: 0.68rem; font-weight: 700; background: #f0fdf4; color: #16a34a; border: 1px solid #bbf7d0; border-radius: 4px; padding: 0.15rem 0.5rem; }

    /* File types */
    .filetypes-grid { display: flex; flex-wrap: wrap; gap: 0.75rem; justify-content: center; margin-top: 2.5rem; }
    .filetype-pill {
        display: flex; align-items: center; gap: 0.5rem;
        background: #fff; border: 1px solid var(--border); border-radius: 100px;
        padding: 0.5rem 1.1rem; font-size: 0.85rem; font-weight: 600;
        color: var(--text); box-shadow: 0 1px 4px rgba(0,0,0,0.05);
    }
</style>
@endsection

@section('content')

<!-- Hero -->
<section class="hero">
    <div class="hero-badge">Microsoft 365 · Azure AI · Enterprise Ready</div>
    <h1>Turn Your <em>Microsoft 365 Content</em> Into AI-Ready Knowledge</h1>
    <p>Automatically ingest SharePoint, Teams, OneNote, and OneDrive — extract, chunk, embed, and index for AI-powered semantic search. All within your own Azure tenant.</p>
    <div class="hero-cta">
        <a href="{{ url('/demo') }}" class="btn btn-primary btn-lg">Book a Demo</a>
        <a href="#how-it-works" class="btn btn-lg" style="color:#fff;border:1.5px solid rgba(255,255,255,0.4);">See how it works</a>
    </div>
    <div class="hero-stats">
        <div class="stat"><div class="stat-num">4</div><div class="stat-label">M365 sources connected</div></div>
        <div class="stat"><div class="stat-num">9</div><div class="stat-label">File formats supported</div></div>
        <div class="stat"><div class="stat-num">100%</div><div class="stat-label">Your Azure tenant — your data</div></div>
        <div class="stat"><div class="stat-num">&lt;5 min</div><div class="stat-label">First pipeline run</div></div>
    </div>

    <div class="source-bar">
        <div class="source-badge">📁 SharePoint</div>
        <div class="source-badge">💬 Microsoft Teams</div>
        <div class="source-badge">📓 OneNote</div>
        <div class="source-badge">☁️ OneDrive</div>
        <div class="source-badge" style="color:#64748b;border-style:dashed;">＋ More coming</div>
    </div>
</section>

<!-- Sources Section -->
<section id="sources" class="how-bg">
    <div class="center">
        <div class="section-label">Data Sources</div>
        <h2 class="section-title">Four platforms. One unified pipeline.</h2>
        <p class="section-sub">Connect to your Microsoft 365 environment and let the pipeline handle the rest — files of every format, automatically extracted and indexed.</p>
    </div>
    <div class="sources-grid">

        <div class="source-card">
            <div class="source-icon">📁</div>
            <h3>SharePoint</h3>
            <p>Ingest documents from SharePoint document libraries across any site. Handles all Office formats, PDFs, and embedded attachments with full metadata.</p>
            <div class="source-types">
                <span class="source-type">.docx</span>
                <span class="source-type">.xlsx</span>
                <span class="source-type">.pptx</span>
                <span class="source-type">.pdf</span>
                <span class="source-type">.xlsm</span>
                <span class="source-type">.doc</span>
                <span class="source-type">.ppt</span>
            </div>
        </div>

        <div class="source-card">
            <div class="source-icon">💬</div>
            <h3>Microsoft Teams</h3>
            <p>Reads files shared in Teams channels and private chats. Discovers all team sites and libraries automatically.</p>
            <div class="source-types">
                <span class="source-type">Channel Files</span>
                <span class="source-type">Team Sites</span>
                <span class="source-type">Private Channels</span>
            </div>
        </div>

        <div class="source-card">
            <div class="source-icon">📓</div>
            <h3>OneNote</h3>
            <p>Extracts notebook pages, parses clean text, and processes any Office attachments embedded in notes.</p>
            <div class="source-types">
                <span class="source-type">Notebooks</span>
                <span class="source-type">Sections</span>
                <span class="source-type">Pages</span>
                <span class="source-type">Attachments</span>
            </div>
        </div>

        <div class="source-card">
            <div class="source-icon">☁️</div>
            <h3>OneDrive</h3>
            <p>Connects to personal and shared OneDrive drives. Crawls folders recursively and processes all supported document types.</p>
            <div class="source-types">
                <span class="source-type">Personal Drive</span>
                <span class="source-type">Shared Drives</span>
                <span class="source-type">Nested Folders</span>
            </div>
            <span class="source-badge-new">✓ Live</span>
        </div>

    </div>
</section>

<!-- File types -->
<section style="padding: 60px 5%; background: #fff; border-top: 1px solid var(--border);">
    <div class="center">
        <div class="section-label">File Formats</div>
        <h2 class="section-title">Every unstructured format, handled natively</h2>
        <p class="section-sub">No external OCR service required. Each format has a dedicated extractor built into the pipeline.</p>
        <div class="filetypes-grid">
            <div class="filetype-pill">📄 PDF</div>
            <div class="filetype-pill">📝 Word (.docx)</div>
            <div class="filetype-pill">📊 Excel (.xlsx/.xls)</div>
            <div class="filetype-pill">📽️ PowerPoint (.pptx)</div>
            <div class="filetype-pill">📓 OneNote</div>
            <div class="filetype-pill">📋 CSV</div>
            <div class="filetype-pill">🔧 JSON</div>
            <div class="filetype-pill">📃 Plain Text / Markdown</div>
            <div class="filetype-pill">🧮 Macro Excel (.xlsm)</div>
        </div>
    </div>
</section>

<!-- How it works -->
<section class="how-bg" id="how-it-works">
    <div class="center">
        <div class="section-label">Process</div>
        <h2 class="section-title">From raw file to searchable knowledge in minutes</h2>
        <p class="section-sub">A fully automated pipeline — ingest, extract, chunk, embed, and index — with no manual steps.</p>
    </div>
    <div class="steps">
        <div class="step">
            <div class="step-num">Step 01</div>
            <div class="step-icon">📥</div>
            <h3>Ingest</h3>
            <p>Microsoft Graph API pulls files from SharePoint, Teams, OneNote, and OneDrive into Azure Data Lake Storage Gen2 with full provenance metadata.</p>
        </div>
        <div class="step">
            <div class="step-num">Step 02</div>
            <div class="step-icon">🐍</div>
            <h3>Python Extraction</h3>
            <p>Python-native extractors parse every file type — pypdf for PDFs, python-docx for Word, openpyxl for Excel, BeautifulSoup for OneNote HTML. No external OCR service needed.</p>
        </div>
        <div class="step">
            <div class="step-num">Step 03</div>
            <div class="step-icon">✂️</div>
            <h3>Chunk &amp; Embed</h3>
            <p>Extracted text is split into semantic chunks with full provenance metadata. Each chunk is embedded using Azure OpenAI (1,536-dim vectors).</p>
        </div>
        <div class="step">
            <div class="step-num">Step 04</div>
            <div class="step-icon">⚡</div>
            <h3>Index &amp; Search</h3>
            <p>Chunks are pushed to Azure AI Search with hybrid BM25 + vector search and semantic re-ranking for best-in-class retrieval accuracy.</p>
        </div>
    </div>
</section>

<!-- Features -->
<section id="features">
    <div class="center">
        <div class="section-label">Capabilities</div>
        <h2 class="section-title">Everything you need for enterprise document intelligence</h2>
        <p class="section-sub">Built on Python and Azure with a fully configurable processing pipeline — no proprietary extraction service lock-in.</p>
    </div>
    <div class="features-grid">
        <div class="feature-card">
            <div class="feature-icon">🐍</div>
            <h3>Python-Only Extraction</h3>
            <p>All file parsing uses pure Python libraries (pypdf, python-docx, python-pptx, openpyxl, BeautifulSoup). Fast, cost-free, and fully portable — no Azure DI dependency.</p>
        </div>
        <div class="feature-card">
            <div class="feature-icon">🧠</div>
            <h3>Semantic + Vector Search</h3>
            <p>Hybrid BM25 full-text search combined with 1,536-dim vector embeddings and Azure AI semantic re-ranking for highly accurate retrieval.</p>
        </div>
        <div class="feature-card">
            <div class="feature-icon">📎</div>
            <h3>Attachment Processing</h3>
            <p>Automatically extracts and indexes files embedded inside Word, Excel, and PowerPoint documents alongside their parent with full lineage tracking.</p>
        </div>
        <div class="feature-card">
            <div class="feature-icon">🔄</div>
            <h3>Incremental Updates</h3>
            <p>Smart deduplication via content hashes ensures only new or changed files are re-processed, keeping costs low and the index fresh.</p>
        </div>
        <div class="feature-card">
            <div class="feature-icon">🗺️</div>
            <h3>Rich Provenance Metadata</h3>
            <p>Every chunk carries source platform, site, library, file path, page number, chunk index, block type, modification dates, and more.</p>
        </div>
        <div class="feature-card">
            <div class="feature-icon">🔒</div>
            <h3>Enterprise Security</h3>
            <p>All data stays within your Azure tenant. Managed identity auth, ADLS Gen2 encryption at rest, and role-based access throughout.</p>
        </div>
    </div>
</section>

<!-- Tech Stack -->
<section class="tech-bg" id="tech-stack">
    <div class="center" style="color:#fff;">
        <div class="section-label" style="color:#60a5fa;">Built on Azure + Python</div>
        <h2 class="section-title">Enterprise-grade infrastructure, Python-first extraction</h2>
        <p class="section-sub" style="color:#94a3b8;margin:0 auto;">Managed Azure services for storage, search, and embeddings — open-source Python for all document parsing.</p>
    </div>
    <div class="tech-grid">
        <div class="tech-card"><div class="label">Storage</div><div class="value">Azure Data Lake Storage Gen2</div></div>
        <div class="tech-card"><div class="label">Document Extraction</div><div class="value">Native Python parsers</div></div>
        <div class="tech-card"><div class="label">Embeddings</div><div class="value">Azure OpenAI text-embedding-3-small</div></div>
        <div class="tech-card"><div class="label">Search</div><div class="value">Azure AI Search (Hybrid + Semantic)</div></div>
        <div class="tech-card"><div class="label">Ingest Sources</div><div class="value">Microsoft 365 Connectors</div></div>
        <div class="tech-card"><div class="label">Pipeline Runtime</div><div class="value">Python · Azure Functions</div></div>
        <div class="tech-card"><div class="label">Vector Dimensions</div><div class="value">1,536-dim HNSW Index</div></div>
        <div class="tech-card"><div class="label">Chunking</div><div class="value">Hybrid chunker</div></div>
        <div class="tech-card"><div class="label">Portal</div><div class="value">Laravel 12 · Blade · Tailwind CSS</div></div>
    </div>
</section>

<!-- Use Cases -->
<section style="padding: 90px 5%; background: #fff; border-top: 1px solid var(--border);">
    <div class="center">
        <div class="section-label">Who it's for</div>
        <h2 class="section-title">Built for enterprises already invested in Microsoft 365 &amp; Azure</h2>
        <p class="section-sub">If your organisation's knowledge lives in SharePoint and Teams but your AI systems can't access it — ChunkIQ closes that gap.</p>
    </div>
    <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(280px,1fr));gap:1.5rem;margin-top:3.5rem;">
        <div class="feature-card" style="border-left:3px solid var(--blue);">
            <div class="feature-icon">🏛️</div>
            <h3>Knowledge Management Teams</h3>
            <p>Thousands of documents scattered across SharePoint sites and Teams channels — impossible to search manually. ChunkIQ indexes all of it into a single AI-searchable knowledge base.</p>
        </div>
        <div class="feature-card" style="border-left:3px solid var(--blue);">
            <div class="feature-icon">🤖</div>
            <h3>AI / Copilot Teams</h3>
            <p>Building a RAG pipeline or Copilot extension on top of enterprise data? ChunkIQ handles the entire ingestion and chunking layer so your team can focus on the AI application layer.</p>
        </div>
        <div class="feature-card" style="border-left:3px solid var(--blue);">
            <div class="feature-icon">📋</div>
            <h3>Compliance &amp; Legal Teams</h3>
            <p>Need to make contracts, policies, and audit trails searchable and auditable? ChunkIQ processes every document with full provenance metadata — source, file, page, modification date.</p>
        </div>
        <div class="feature-card" style="border-left:3px solid var(--blue);">
            <div class="feature-icon">🏗️</div>
            <h3>Enterprise Architects</h3>
            <p>Evaluating unstructured data pipelines for your Azure data platform? ChunkIQ deploys entirely within your Azure subscription — no data leaves your tenant.</p>
        </div>
    </div>
</section>

<!-- Social proof / trust bar -->
<section style="padding:50px 5%;background:var(--light);border-top:1px solid var(--border);text-align:center;">
    <p style="font-size:0.82rem;font-weight:700;letter-spacing:1px;text-transform:uppercase;color:var(--muted);margin-bottom:2rem;">Built on the enterprise stack you already trust</p>
    <div style="display:flex;flex-wrap:wrap;gap:2.5rem;justify-content:center;align-items:center;">
        <div style="font-size:0.95rem;font-weight:700;color:#005a9e;">Microsoft Azure</div>
        <div style="width:1px;height:24px;background:var(--border);"></div>
        <div style="font-size:0.95rem;font-weight:700;color:#005a9e;">Microsoft 365</div>
        <div style="width:1px;height:24px;background:var(--border);"></div>
        <div style="font-size:0.95rem;font-weight:700;color:#005a9e;">Azure OpenAI</div>
        <div style="width:1px;height:24px;background:var(--border);"></div>
        <div style="font-size:0.95rem;font-weight:700;color:#005a9e;">Azure AI Search</div>
        <div style="width:1px;height:24px;background:var(--border);"></div>
        <div style="font-size:0.95rem;font-weight:700;color:#005a9e;">Azure Data Lake</div>
    </div>
</section>

<!-- CTA -->
<section class="cta-section">
    <h2>Ready to make your Microsoft 365 data AI-ready?</h2>
    <p>Talk to us about your data environment. We'll show you how ChunkIQ fits into your Azure tenant in 30 minutes.</p>
    <div style="display:flex;gap:1rem;justify-content:center;flex-wrap:wrap;">
        <a href="{{ url('/demo') }}" class="btn btn-white btn-lg">Book a Demo</a>
        <a href="{{ url('/pricing') }}" class="btn btn-lg" style="color:#fff;border:1.5px solid rgba(255,255,255,0.5);">View Pricing</a>
    </div>
</section>

@endsection
