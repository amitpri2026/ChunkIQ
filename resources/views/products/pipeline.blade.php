@extends('layouts.public')

@section('title', 'Pipeline Orchestrator — ChunkIQ')

@section('styles')
<style>
    .product-hero {
        background: linear-gradient(135deg, #0a0a0a 0%, #1a1a2e 60%, #16213e 100%);
        color: #fff; padding: 90px 5% 80px; text-align: center;
    }
    .product-badge {
        display: inline-flex; align-items: center; gap: 0.5rem;
        background: rgba(249,115,22,0.2); border: 1px solid rgba(249,115,22,0.4);
        color: #fed7aa; font-size: 0.78rem; font-weight: 600; letter-spacing: 0.8px;
        text-transform: uppercase; padding: 0.35rem 1rem; border-radius: 20px; margin-bottom: 1.5rem;
    }
    .product-hero h1 { font-size: clamp(2rem, 4.5vw, 3.2rem); font-weight: 800; line-height: 1.15; letter-spacing: -1px; max-width: 820px; margin: 0 auto 1.2rem; }
    .product-hero h1 em { font-style: normal; color: #fb923c; }
    .product-hero p { font-size: 1.1rem; color: #94a3b8; max-width: 600px; margin: 0 auto 2.5rem; line-height: 1.7; }
    .hero-cta { display: flex; gap: 1rem; justify-content: center; flex-wrap: wrap; }
    .hero-icon { font-size: 4rem; margin-bottom: 1.5rem; }

    .stat-row { display: flex; gap: 3rem; justify-content: center; margin-top: 3rem; flex-wrap: wrap; }
    .stat-item { text-align: center; }
    .stat-item .num { font-size: 1.8rem; font-weight: 800; color: #fff; }
    .stat-item .lbl { font-size: 0.8rem; color: #64748b; margin-top: 0.2rem; }

    /* Full pipeline diagram */
    .pipeline-diagram { margin-top: 3.5rem; }
    .pipeline-stage {
        display: flex; align-items: flex-start; gap: 2rem;
        background: #fff; border: 1px solid var(--border); border-radius: 14px;
        padding: 2rem; margin-bottom: 1rem; position: relative;
        transition: box-shadow 0.2s, border-color 0.2s;
    }
    .pipeline-stage:hover { box-shadow: 0 4px 24px rgba(15,98,254,0.1); border-color: #bfdbfe; }
    .pipeline-stage::after {
        content: '↓'; position: absolute; bottom: -1.1rem; left: 50%;
        transform: translateX(-50%); font-size: 1.2rem; color: var(--muted);
        background: white; padding: 0 0.5rem; z-index: 1;
    }
    .pipeline-stage:last-child::after { display: none; }
    .stage-num { flex-shrink: 0; width: 2.5rem; height: 2.5rem; border-radius: 50%; background: var(--blue); color: #fff; display: flex; align-items: center; justify-content: center; font-weight: 800; font-size: 0.9rem; }
    .stage-icon { font-size: 2rem; flex-shrink: 0; }
    .stage-content { flex: 1; }
    .stage-content h3 { font-size: 1.05rem; font-weight: 700; margin-bottom: 0.4rem; }
    .stage-content p { font-size: 0.87rem; color: var(--muted); line-height: 1.6; margin-bottom: 0.75rem; }
    .stage-tags { display: flex; flex-wrap: wrap; gap: 0.4rem; }
    .stage-tag { font-size: 0.72rem; font-weight: 600; background: #eff6ff; color: var(--blue); border-radius: 4px; padding: 0.2rem 0.5rem; }

    .sources-row { display: flex; flex-wrap: wrap; gap: 0.6rem; margin-bottom: 0.75rem; }
    .source-chip { display: inline-flex; align-items: center; gap: 0.35rem; background: #f8faff; border: 1px solid #dbeafe; border-radius: 100px; padding: 0.3rem 0.8rem; font-size: 0.8rem; font-weight: 600; color: var(--text); }
</style>
@endsection

@section('content')

<!-- Hero -->
<section class="product-hero">
    <div class="hero-icon">⚡</div>
    <div class="product-badge">End-to-End Automation</div>
    <h1>Pipeline <em>Orchestrator</em></h1>
    <p>The complete ChunkIQ pipeline — from Microsoft 365 ingestion through Python extraction, semantic chunking, vector embedding, and Azure AI Search indexing — fully automated and monitored.</p>
    <div class="hero-cta">
        @if (Route::has('register'))
            <a href="{{ route('register') }}" class="btn btn-primary btn-lg">Get started free</a>
        @endif
        <a href="{{ url('/') }}#tech-stack" class="btn btn-lg" style="color:#fff;border:1.5px solid rgba(255,255,255,0.4);">See the tech stack</a>
    </div>
    <div class="stat-row">
        <div class="stat-item"><div class="num">4</div><div class="lbl">Pipeline stages</div></div>
        <div class="stat-item"><div class="num">&lt;5 min</div><div class="lbl">Full run time</div></div>
        <div class="stat-item"><div class="num">4</div><div class="lbl">Source platforms</div></div>
        <div class="stat-item"><div class="num">Zero</div><div class="lbl">Manual steps</div></div>
    </div>
</section>

<!-- Pipeline stages -->
<section>
    <div class="center">
        <div class="section-label">Pipeline Stages</div>
        <h2 class="section-title">Four stages. Fully automated.</h2>
        <p class="section-sub">Each stage is independent, observable, and runs on Azure Functions — trigger the full pipeline on demand or on a schedule.</p>
    </div>
    <div class="pipeline-diagram">

        <div class="pipeline-stage">
            <div class="stage-num">1</div>
            <div class="stage-icon">📥</div>
            <div class="stage-content">
                <h3>Ingest</h3>
                <p>The Microsoft Graph API connector authenticates to your Microsoft 365 tenant and enumerates all files across the configured source platforms. Files are downloaded to Azure Data Lake Storage Gen2 with full provenance metadata and content-hash deduplication to skip unchanged files.</p>
                <div class="sources-row">
                    <span class="source-chip">📁 SharePoint</span>
                    <span class="source-chip">💬 Microsoft Teams</span>
                    <span class="source-chip">📓 OneNote</span>
                    <span class="source-chip">☁️ OneDrive</span>
                </div>
                <div class="stage-tags">
                    <span class="stage-tag">Microsoft Graph API</span>
                    <span class="stage-tag">ADLS Gen2</span>
                    <span class="stage-tag">Content hashing</span>
                    <span class="stage-tag">Delta sync</span>
                </div>
            </div>
        </div>

        <div class="pipeline-stage">
            <div class="stage-num">2</div>
            <div class="stage-icon">🐍</div>
            <div class="stage-content">
                <h3>Python Extraction</h3>
                <p>A format-aware dispatcher routes each file to the appropriate Python extractor. No external OCR or Document Intelligence service is required — all parsing runs with pure Python libraries, keeping costs minimal and extraction fully portable.</p>
                <div class="stage-tags">
                    <span class="stage-tag">pypdf → .pdf</span>
                    <span class="stage-tag">python-docx → .docx</span>
                    <span class="stage-tag">openpyxl → .xlsx / .xlsm</span>
                    <span class="stage-tag">python-pptx → .pptx</span>
                    <span class="stage-tag">BeautifulSoup → .html</span>
                    <span class="stage-tag">csv / json / utf-8 → structured</span>
                </div>
            </div>
        </div>

        <div class="pipeline-stage">
            <div class="stage-num">3</div>
            <div class="stage-icon">✂️</div>
            <div class="stage-content">
                <h3>Chunk &amp; Embed</h3>
                <p>Extracted text is split into semantic chunks using a hybrid chunking strategy — respecting paragraph boundaries while keeping chunk sizes within the tiktoken token budget. Each chunk is then embedded using Azure OpenAI's text-embedding-3-small model, producing a 1,536-dimensional vector per chunk.</p>
                <div class="stage-tags">
                    <span class="stage-tag">Hybrid semantic chunker</span>
                    <span class="stage-tag">tiktoken (cl100k_base)</span>
                    <span class="stage-tag">Azure OpenAI Embeddings</span>
                    <span class="stage-tag">1,536-dim vectors</span>
                    <span class="stage-tag">Provenance metadata</span>
                </div>
            </div>
        </div>

        <div class="pipeline-stage">
            <div class="stage-num">4</div>
            <div class="stage-icon">⚡</div>
            <div class="stage-content">
                <h3>Index &amp; Search</h3>
                <p>Chunks are upserted to Azure AI Search with their embedding vectors and all metadata fields. The index supports hybrid BM25 + vector search with semantic re-ranking via Reciprocal Rank Fusion — delivering best-in-class retrieval accuracy for RAG pipelines and search applications.</p>
                <div class="stage-tags">
                    <span class="stage-tag">Azure AI Search</span>
                    <span class="stage-tag">BM25 keyword search</span>
                    <span class="stage-tag">HNSW vector index</span>
                    <span class="stage-tag">RRF score fusion</span>
                    <span class="stage-tag">Semantic re-ranking</span>
                </div>
            </div>
        </div>

    </div>
</section>

<!-- Features -->
<section class="how-bg">
    <div class="center">
        <div class="section-label">Orchestration</div>
        <h2 class="section-title">Built for reliability at scale</h2>
        <p class="section-sub">Azure Functions runtime with full observability, error handling, and incremental processing — designed to run reliably on large tenants.</p>
    </div>
    <div class="features-grid">
        <div class="feature-card">
            <div class="feature-icon">⚙️</div>
            <h3>Azure Functions Runtime</h3>
            <p>Each pipeline stage is a separate Azure Function. Stages can be triggered individually or run end-to-end on a timer trigger or HTTP call from the Laravel portal.</p>
        </div>
        <div class="feature-card">
            <div class="feature-icon">🔁</div>
            <h3>Incremental Processing</h3>
            <p>Content hashing on ingest and delta query tokens on OneDrive/SharePoint ensure only changed content is re-processed, keeping run times short on large tenants.</p>
        </div>
        <div class="feature-card">
            <div class="feature-icon">📊</div>
            <h3>Pipeline Monitoring</h3>
            <p>The Laravel dashboard shows live status of each source platform, chunks indexed, active sources, and last pipeline run time — all in one place.</p>
        </div>
        <div class="feature-card">
            <div class="feature-icon">🔀</div>
            <h3>Parallel Extraction</h3>
            <p>Files are processed in parallel across Azure Functions workers. Large batches of documents are extracted concurrently to minimise total pipeline run time.</p>
        </div>
        <div class="feature-card">
            <div class="feature-icon">🛡️</div>
            <h3>Error Isolation</h3>
            <p>Each file is processed in an isolated try/except block. A malformed document causes a logged warning and skips to the next file — it never halts the pipeline.</p>
        </div>
        <div class="feature-card">
            <div class="feature-icon">🔒</div>
            <h3>Zero Data Egress</h3>
            <p>All compute and storage runs within your Azure subscription. Managed identity authentication throughout — no API keys stored in code or config files.</p>
        </div>
    </div>
</section>

<!-- How it works (end to end timing) -->
<section id="how-it-works">
    <div class="center">
        <div class="section-label">End-to-End</div>
        <h2 class="section-title">From file to searchable in under 5 minutes</h2>
        <p class="section-sub">Typical run times on a mid-size Microsoft 365 tenant.</p>
    </div>
    <div class="steps">
        <div class="step">
            <div class="step-num">~60s</div>
            <div class="step-icon">📥</div>
            <h3>Ingest</h3>
            <p>Graph API enumeration and file download to ADLS Gen2. Time scales with number of new/changed files, not total tenant size.</p>
        </div>
        <div class="step">
            <div class="step-num">~90s</div>
            <div class="step-icon">🐍</div>
            <h3>Extraction</h3>
            <p>Parallel Python extraction across all file types. PDF and PowerPoint files are typically the most time-intensive format to parse.</p>
        </div>
        <div class="step">
            <div class="step-num">~60s</div>
            <div class="step-icon">✂️</div>
            <h3>Chunking & Embedding</h3>
            <p>Chunking is near-instant. Embedding time is proportional to the number of new chunks — batched calls to Azure OpenAI keep latency low.</p>
        </div>
        <div class="step">
            <div class="step-num">~30s</div>
            <div class="step-icon">⚡</div>
            <h3>Indexing</h3>
            <p>Batched upsert to Azure AI Search. The index is updated incrementally — live search continues to work throughout the upsert.</p>
        </div>
    </div>
</section>

<!-- Tech Stack -->
<section class="tech-bg">
    <div class="center" style="color:#fff;">
        <div class="section-label" style="color:#fb923c;">Full stack</div>
        <h2 class="section-title">Every component, at a glance</h2>
    </div>
    <div class="tech-grid">
        <div class="tech-card"><div class="label">Ingest</div><div class="value">Microsoft Graph API</div></div>
        <div class="tech-card"><div class="label">Storage</div><div class="value">Azure Data Lake Storage Gen2</div></div>
        <div class="tech-card"><div class="label">Runtime</div><div class="value">Python · Azure Functions</div></div>
        <div class="tech-card"><div class="label">PDF</div><div class="value">pypdf</div></div>
        <div class="tech-card"><div class="label">Word / PowerPoint</div><div class="value">python-docx · python-pptx</div></div>
        <div class="tech-card"><div class="label">Excel</div><div class="value">openpyxl</div></div>
        <div class="tech-card"><div class="label">HTML</div><div class="value">BeautifulSoup4</div></div>
        <div class="tech-card"><div class="label">Chunking</div><div class="value">Hybrid chunker + tiktoken</div></div>
        <div class="tech-card"><div class="label">Embeddings</div><div class="value">Azure OpenAI text-embedding-3-small</div></div>
        <div class="tech-card"><div class="label">Search</div><div class="value">Azure AI Search (Hybrid + Semantic)</div></div>
        <div class="tech-card"><div class="label">Vector Index</div><div class="value">1,536-dim HNSW Index</div></div>
        <div class="tech-card"><div class="label">Portal</div><div class="value">Laravel 12 · Blade · Tailwind CSS</div></div>
    </div>
</section>

<!-- CTA -->
<section class="cta-section">
    <h2>Run the full pipeline in minutes</h2>
    <p>Connect your Microsoft 365 tenant, configure your Azure resources, and trigger your first full extraction-to-search pipeline run today.</p>
    <div style="display:flex;gap:1rem;justify-content:center;flex-wrap:wrap;">
        @if (Route::has('register'))
            <a href="{{ route('register') }}" class="btn btn-white btn-lg">Create your account</a>
        @endif
        @if (Route::has('login'))
            <a href="{{ route('login') }}" class="btn btn-lg" style="color:#fff;border:1.5px solid rgba(255,255,255,0.5);">Sign in</a>
        @endif
    </div>
</section>

@endsection
