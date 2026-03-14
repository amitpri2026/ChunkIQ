@extends('layouts.public')

@section('title', 'ChunkIQ Enterprise — Full End-to-End Pipeline')

@section('styles')
<style>
    .product-hero {
        background: linear-gradient(135deg, #0a0a1a 0%, #0d1b3e 60%, #001141 100%);
        color: #fff; padding: 90px 5% 80px; text-align: center;
    }
    .product-badge {
        display: inline-flex; align-items: center; gap: 0.5rem;
        background: rgba(15,98,254,0.2); border: 1px solid rgba(15,98,254,0.5);
        color: #bfdbfe; font-size: 0.78rem; font-weight: 600; letter-spacing: 0.8px;
        text-transform: uppercase; padding: 0.35rem 1rem; border-radius: 20px; margin-bottom: 1.5rem;
    }
    .product-hero h1 { font-size: clamp(2rem, 4.5vw, 3.2rem); font-weight: 800; line-height: 1.15; letter-spacing: -1px; max-width: 860px; margin: 0 auto 1.2rem; }
    .product-hero h1 em { font-style: normal; color: #60a5fa; }
    .product-hero p { font-size: 1.1rem; color: #94a3b8; max-width: 640px; margin: 0 auto 2.5rem; line-height: 1.7; }
    .hero-cta { display: flex; gap: 1rem; justify-content: center; flex-wrap: wrap; }
    .hero-icon { font-size: 4rem; margin-bottom: 1.5rem; }

    .stat-row { display: flex; gap: 3rem; justify-content: center; margin-top: 3rem; flex-wrap: wrap; }
    .stat-item { text-align: center; }
    .stat-item .num { font-size: 1.8rem; font-weight: 800; color: #fff; }
    .stat-item .lbl { font-size: 0.8rem; color: #64748b; margin-top: 0.2rem; }

    /* Ingestion sources grid */
    .sources-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(260px, 1fr)); gap: 1.25rem; margin-top: 3rem; }
    .source-card {
        background: #fff; border: 1px solid var(--border); border-radius: 14px;
        padding: 1.6rem; display: flex; align-items: flex-start; gap: 1rem;
        transition: box-shadow 0.2s, border-color 0.2s;
    }
    .source-card:hover { box-shadow: 0 4px 24px rgba(15,98,254,0.1); border-color: #bfdbfe; }
    .source-card-icon { font-size: 2rem; flex-shrink: 0; }
    .source-card h3 { font-size: 0.98rem; font-weight: 700; margin-bottom: 0.3rem; }
    .source-card p { font-size: 0.84rem; color: var(--muted); line-height: 1.55; }

    /* Pipeline stages */
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

    .deployment-note {
        background: #eff6ff; border: 1px solid #bfdbfe; border-radius: 10px;
        padding: 1.2rem 1.5rem; margin-top: 2rem; display: flex; gap: 0.75rem; align-items: flex-start;
    }
    .deployment-note-icon { font-size: 1.3rem; flex-shrink: 0; }
    .deployment-note p { font-size: 0.88rem; color: var(--text); line-height: 1.6; }
    .deployment-note strong { color: var(--blue); }
</style>
@endsection

@section('content')

<!-- Hero -->
<section class="product-hero">
    <div class="hero-icon">🏢</div>
    <div class="product-badge">Enterprise · Self-Hosted</div>
    <h1>ChunkIQ <em>Enterprise</em></h1>
    <p>The complete end-to-end ChunkIQ platform — all ingestion connectors, full pipeline processing, hybrid search, and the management portal — deployed entirely within your own Azure subscription.</p>
    <div class="hero-cta">
        @if (Route::has('register'))
            <a href="{{ route('register') }}" class="btn btn-primary btn-lg">Request access</a>
        @endif
        <a href="{{ url('/products/pipeline') }}" class="btn btn-lg" style="color:#fff;border:1.5px solid rgba(255,255,255,0.4);">See the pipeline</a>
    </div>
    <div class="stat-row">
        <div class="stat-item"><div class="num">4</div><div class="lbl">Ingestion sources</div></div>
        <div class="stat-item"><div class="num">4</div><div class="lbl">Pipeline stages</div></div>
        <div class="stat-item"><div class="num">100%</div><div class="lbl">Your Azure tenant</div></div>
        <div class="stat-item"><div class="num">Zero</div><div class="lbl">Data egress</div></div>
    </div>
</section>

<!-- What's included — ingestion sources -->
<section>
    <div class="center">
        <div class="section-label">Ingestion Sources</div>
        <h2 class="section-title">Every Microsoft 365 source, included</h2>
        <p class="section-sub">ChunkIQ Enterprise ships with all four ingestion connectors pre-configured and ready to activate against your Microsoft 365 tenant.</p>
    </div>
    <div class="sources-grid">
        <div class="source-card">
            <div class="source-card-icon">📁</div>
            <div>
                <h3>SharePoint Extractor</h3>
                <p>Enumerates all sites and document libraries. Extracts content from every file format including PDF, Word, Excel, PowerPoint, HTML, and plain text with full provenance metadata.</p>
            </div>
        </div>
        <div class="source-card">
            <div class="source-card-icon">💬</div>
            <div>
                <h3>Teams & Search Portal</h3>
                <p>Indexes Teams channel messages, meeting transcripts, and shared files. Exposes a unified search portal for querying across all ingested Microsoft 365 content.</p>
            </div>
        </div>
        <div class="source-card">
            <div class="source-card-icon">📓</div>
            <div>
                <h3>OneNote Extractor</h3>
                <p>Traverses all notebooks, sections, and pages via the Graph API. Extracts structured text and preserves section hierarchy as metadata for precise retrieval.</p>
            </div>
        </div>
        <div class="source-card">
            <div class="source-card-icon">☁️</div>
            <div>
                <h3>OneDrive Extractor</h3>
                <p>Scans personal and shared OneDrive drives across your organisation. Delta query tokens ensure only new or modified files are re-processed on subsequent runs.</p>
            </div>
        </div>
    </div>

    <div class="deployment-note">
        <div class="deployment-note-icon">🔒</div>
        <p>All connectors authenticate via <strong>Microsoft Graph API with Managed Identity</strong> — no passwords or API keys stored in code or configuration. Your data never leaves your Azure subscription.</p>
    </div>
</section>

<!-- Pipeline stages -->
<section class="how-bg">
    <div class="center">
        <div class="section-label">End-to-End Pipeline</div>
        <h2 class="section-title">Four stages. Fully automated.</h2>
        <p class="section-sub">Ingest → Extract → Chunk & Embed → Index. Each stage runs on Azure Functions inside your subscription and can be triggered on demand or on a schedule.</p>
    </div>
    <div style="margin-top: 3.5rem;">

        <div class="pipeline-stage">
            <div class="stage-num">1</div>
            <div class="stage-icon">📥</div>
            <div class="stage-content">
                <h3>Ingest — All Sources</h3>
                <p>The Graph API connector authenticates to your Microsoft 365 tenant and enumerates files across all four source platforms simultaneously. Files are downloaded to Azure Data Lake Storage Gen2 with content-hash deduplication so unchanged files are never re-processed.</p>
                <div class="stage-tags">
                    <span class="stage-tag">Microsoft Graph API</span>
                    <span class="stage-tag">ADLS Gen2</span>
                    <span class="stage-tag">Delta sync</span>
                    <span class="stage-tag">Content hashing</span>
                    <span class="stage-tag">SharePoint · Teams · OneNote · OneDrive</span>
                </div>
            </div>
        </div>

        <div class="pipeline-stage">
            <div class="stage-num">2</div>
            <div class="stage-icon">🐍</div>
            <div class="stage-content">
                <h3>Python Extraction</h3>
                <p>A format-aware dispatcher routes each file to the correct Python library. All extraction runs with pure Python — no external OCR or Document Intelligence services required, keeping costs minimal and the pipeline fully portable within your subscription.</p>
                <div class="stage-tags">
                    <span class="stage-tag">pypdf → .pdf</span>
                    <span class="stage-tag">python-docx → .docx</span>
                    <span class="stage-tag">openpyxl → .xlsx / .xlsm</span>
                    <span class="stage-tag">xlrd → .xls</span>
                    <span class="stage-tag">python-pptx → .pptx</span>
                    <span class="stage-tag">BeautifulSoup → .html</span>
                </div>
            </div>
        </div>

        <div class="pipeline-stage">
            <div class="stage-num">3</div>
            <div class="stage-icon">✂️</div>
            <div class="stage-content">
                <h3>Chunk &amp; Embed</h3>
                <p>Extracted text is split into semantic chunks using a hybrid chunking strategy that respects paragraph boundaries while staying within the tiktoken token budget. Each chunk is embedded using Azure OpenAI's text-embedding-3-small model.</p>
                <div class="stage-tags">
                    <span class="stage-tag">Hybrid semantic chunker</span>
                    <span class="stage-tag">tiktoken (cl100k_base)</span>
                    <span class="stage-tag">Azure OpenAI Embeddings</span>
                    <span class="stage-tag">1,536-dim vectors</span>
                </div>
            </div>
        </div>

        <div class="pipeline-stage">
            <div class="stage-num">4</div>
            <div class="stage-icon">⚡</div>
            <div class="stage-content">
                <h3>Index &amp; Search</h3>
                <p>Chunks are upserted to Azure AI Search with embedding vectors and all metadata fields. The index supports hybrid BM25 + vector search with semantic re-ranking via Reciprocal Rank Fusion — best-in-class retrieval accuracy for RAG pipelines and enterprise search.</p>
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

<!-- Enterprise features -->
<section>
    <div class="center">
        <div class="section-label">Enterprise Capabilities</div>
        <h2 class="section-title">Built for your organisation</h2>
        <p class="section-sub">Everything in the individual products, plus enterprise-grade deployment, governance, and control — all inside your Azure perimeter.</p>
    </div>
    <div class="features-grid">
        <div class="feature-card">
            <div class="feature-icon">🏗️</div>
            <h3>Full Azure Deployment</h3>
            <p>Every component — Azure Functions, ADLS Gen2, Azure OpenAI, Azure AI Search, and the Laravel portal — is provisioned inside your Azure subscription via IaC templates.</p>
        </div>
        <div class="feature-card">
            <div class="feature-icon">🔒</div>
            <h3>Zero Data Egress</h3>
            <p>Your documents, extracted text, embeddings, and search index never leave your Azure environment. Managed Identity authentication throughout — no API keys in code.</p>
        </div>
        <div class="feature-card">
            <div class="feature-icon">📊</div>
            <h3>Unified Dashboard</h3>
            <p>The Laravel management portal provides a single view across all four ingestion sources — chunks indexed, last run time, active sources, and pipeline health at a glance.</p>
        </div>
        <div class="feature-card">
            <div class="feature-icon">🔁</div>
            <h3>Incremental Processing</h3>
            <p>Content hashing and Graph API delta tokens ensure only changed files are re-processed. Large tenants stay fast — run times scale with changes, not total corpus size.</p>
        </div>
        <div class="feature-card">
            <div class="feature-icon">⚙️</div>
            <h3>Azure Functions Runtime</h3>
            <p>Each pipeline stage runs as an independent Azure Function. Trigger the full end-to-end pipeline on a timer, on demand via HTTP, or from the management portal.</p>
        </div>
        <div class="feature-card">
            <div class="feature-icon">🛡️</div>
            <h3>Enterprise Support</h3>
            <p>Dedicated onboarding, deployment assistance, and priority support. We help you configure your Microsoft 365 app registration and get the pipeline running in your tenant.</p>
        </div>
    </div>
</section>

<!-- Tech stack -->
<section class="tech-bg">
    <div class="center" style="color:#fff;">
        <div class="section-label" style="color:#60a5fa;">Full stack</div>
        <h2 class="section-title">Every component, at a glance</h2>
    </div>
    <div class="tech-grid">
        <div class="tech-card"><div class="label">Ingest</div><div class="value">Microsoft Graph API</div></div>
        <div class="tech-card"><div class="label">Sources</div><div class="value">SharePoint · Teams · OneNote · OneDrive</div></div>
        <div class="tech-card"><div class="label">Storage</div><div class="value">Azure Data Lake Storage Gen2</div></div>
        <div class="tech-card"><div class="label">Runtime</div><div class="value">Python · Azure Functions</div></div>
        <div class="tech-card"><div class="label">PDF</div><div class="value">pypdf</div></div>
        <div class="tech-card"><div class="label">Word / PowerPoint</div><div class="value">python-docx · python-pptx</div></div>
        <div class="tech-card"><div class="label">Excel</div><div class="value">openpyxl (.xlsx) · xlrd (.xls)</div></div>
        <div class="tech-card"><div class="label">Chunking</div><div class="value">Hybrid chunker + tiktoken</div></div>
        <div class="tech-card"><div class="label">Embeddings</div><div class="value">Azure OpenAI text-embedding-3-small</div></div>
        <div class="tech-card"><div class="label">Search</div><div class="value">Azure AI Search (Hybrid + Semantic)</div></div>
        <div class="tech-card"><div class="label">Vector Index</div><div class="value">1,536-dim HNSW Index</div></div>
        <div class="tech-card"><div class="label">Portal</div><div class="value">Laravel 12 · Blade · Tailwind CSS</div></div>
        <div class="tech-card"><div class="label">Auth</div><div class="value">Azure Managed Identity</div></div>
        <div class="tech-card"><div class="label">Deployment</div><div class="value">Your Azure Subscription</div></div>
    </div>
</section>

<!-- CTA -->
<section class="cta-section">
    <h2>The complete pipeline — in your Azure environment</h2>
    <p>ChunkIQ Enterprise gives your organisation full ownership of the pipeline. All sources, all processing, all search — deployed in your tenant, on your terms.</p>
    <div style="display:flex;gap:1rem;justify-content:center;flex-wrap:wrap;">
        @if (Route::has('register'))
            <a href="{{ route('register') }}" class="btn btn-white btn-lg">Request access</a>
        @endif
        @if (Route::has('login'))
            <a href="{{ route('login') }}" class="btn btn-lg" style="color:#fff;border:1.5px solid rgba(255,255,255,0.5);">Sign in</a>
        @endif
    </div>
</section>

@endsection
