@extends('layouts.public')

@section('title', 'ChunkIQ Enterprise Cloud — Fully Managed Pipeline')

@section('styles')
<style>
    .product-hero {
        background: linear-gradient(135deg, #0a0a1a 0%, #1a0a3e 60%, #2d0080 100%);
        color: #fff; padding: 90px 5% 80px; text-align: center;
    }
    .product-badge {
        display: inline-flex; align-items: center; gap: 0.5rem;
        background: rgba(139,92,246,0.2); border: 1px solid rgba(139,92,246,0.5);
        color: #ddd6fe; font-size: 0.78rem; font-weight: 600; letter-spacing: 0.8px;
        text-transform: uppercase; padding: 0.35rem 1rem; border-radius: 20px; margin-bottom: 1.5rem;
    }
    .product-hero h1 { font-size: clamp(2rem, 4.5vw, 3.2rem); font-weight: 800; line-height: 1.15; letter-spacing: -1px; max-width: 860px; margin: 0 auto 1.2rem; }
    .product-hero h1 em { font-style: normal; color: #a78bfa; }
    .product-hero p { font-size: 1.1rem; color: #94a3b8; max-width: 640px; margin: 0 auto 2.5rem; line-height: 1.7; }
    .hero-cta { display: flex; gap: 1rem; justify-content: center; flex-wrap: wrap; }
    .hero-icon { font-size: 4rem; margin-bottom: 1.5rem; }

    .stat-row { display: flex; gap: 3rem; justify-content: center; margin-top: 3rem; flex-wrap: wrap; }
    .stat-item { text-align: center; }
    .stat-item .num { font-size: 1.8rem; font-weight: 800; color: #fff; }
    .stat-item .lbl { font-size: 0.8rem; color: #64748b; margin-top: 0.2rem; }

    /* Compare cards */
    .compare-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; margin-top: 3rem; }
    @media (max-width: 640px) { .compare-grid { grid-template-columns: 1fr; } }
    .compare-card {
        border-radius: 14px; padding: 2rem; border: 1px solid var(--border);
        transition: box-shadow 0.2s, border-color 0.2s;
    }
    .compare-card:hover { box-shadow: 0 4px 24px rgba(15,98,254,0.1); border-color: #bfdbfe; }
    .compare-card.highlight { background: #faf5ff; border-color: #c4b5fd; }
    .compare-card h3 { font-size: 1rem; font-weight: 700; margin-bottom: 0.5rem; }
    .compare-card .compare-tag { font-size: 0.75rem; font-weight: 700; padding: 0.2rem 0.6rem; border-radius: 20px; display: inline-block; margin-bottom: 1rem; }
    .tag-cloud { background: #ede9fe; color: #6d28d9; }
    .tag-enterprise { background: #eff6ff; color: var(--blue); }
    .compare-card ul { list-style: none; padding: 0; }
    .compare-card ul li { font-size: 0.87rem; color: var(--muted); padding: 0.4rem 0; border-bottom: 1px solid var(--border); display: flex; gap: 0.5rem; align-items: flex-start; }
    .compare-card ul li:last-child { border-bottom: none; }

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
    .pipeline-stage:hover { box-shadow: 0 4px 24px rgba(109,40,217,0.1); border-color: #c4b5fd; }
    .pipeline-stage::after {
        content: '↓'; position: absolute; bottom: -1.1rem; left: 50%;
        transform: translateX(-50%); font-size: 1.2rem; color: var(--muted);
        background: white; padding: 0 0.5rem; z-index: 1;
    }
    .pipeline-stage:last-child::after { display: none; }
    .stage-num { flex-shrink: 0; width: 2.5rem; height: 2.5rem; border-radius: 50%; background: #7c3aed; color: #fff; display: flex; align-items: center; justify-content: center; font-weight: 800; font-size: 0.9rem; }
    .stage-icon { font-size: 2rem; flex-shrink: 0; }
    .stage-content { flex: 1; }
    .stage-content h3 { font-size: 1.05rem; font-weight: 700; margin-bottom: 0.4rem; }
    .stage-content p { font-size: 0.87rem; color: var(--muted); line-height: 1.6; margin-bottom: 0.75rem; }
    .stage-tags { display: flex; flex-wrap: wrap; gap: 0.4rem; }
    .stage-tag { font-size: 0.72rem; font-weight: 600; background: #f5f3ff; color: #6d28d9; border-radius: 4px; padding: 0.2rem 0.5rem; }

    .info-note {
        background: #faf5ff; border: 1px solid #c4b5fd; border-radius: 10px;
        padding: 1.2rem 1.5rem; margin-top: 2rem; display: flex; gap: 0.75rem; align-items: flex-start;
    }
    .info-note-icon { font-size: 1.3rem; flex-shrink: 0; }
    .info-note p { font-size: 0.88rem; color: var(--text); line-height: 1.6; }
    .info-note strong { color: #6d28d9; }
</style>
@endsection

@section('content')

<!-- Hero -->
<section class="product-hero">
    <div class="hero-icon">☁️</div>
    <div class="product-badge">Enterprise Cloud · Fully Managed</div>
    <h1>ChunkIQ <em>Enterprise Cloud</em></h1>
    <p>The complete ChunkIQ pipeline — all ingestion connectors, processing, and hybrid search — hosted and operated on ChunkIQ's own infrastructure. Connect your Microsoft 365, we handle everything else.</p>
    <div class="hero-cta">
        @if (Route::has('register'))
            <a href="{{ route('register') }}" class="btn btn-primary btn-lg">Request access</a>
        @endif
        <a href="{{ url('/products/enterprise') }}" class="btn btn-lg" style="color:#fff;border:1.5px solid rgba(255,255,255,0.4);">Compare with self-hosted</a>
    </div>
    <div class="stat-row">
        <div class="stat-item"><div class="num">4</div><div class="lbl">Ingestion sources</div></div>
        <div class="stat-item"><div class="num">4</div><div class="lbl">Pipeline stages</div></div>
        <div class="stat-item"><div class="num">Zero</div><div class="lbl">Infrastructure to manage</div></div>
        <div class="stat-item"><div class="num">100%</div><div class="lbl">Managed by ChunkIQ</div></div>
    </div>
</section>

<!-- Cloud vs Self-hosted comparison -->
<section>
    <div class="center">
        <div class="section-label">How it's different</div>
        <h2 class="section-title">Same pipeline. Zero infrastructure.</h2>
        <p class="section-sub">Enterprise Cloud delivers the same complete ChunkIQ pipeline as the self-hosted Enterprise product — but runs entirely on ChunkIQ's infrastructure so you never touch Azure Functions, ADLS, or OpenAI deployment configs.</p>
    </div>
    <div class="compare-grid">
        <div class="compare-card">
            <span class="compare-tag tag-enterprise">ChunkIQ Enterprise (Self-Hosted)</span>
            <h3>You deploy, you control</h3>
            <ul>
                <li><span>🏗️</span>Deployed inside your Azure subscription</li>
                <li><span>🔧</span>You manage Azure Functions, ADLS, and OpenAI resources</li>
                <li><span>🔒</span>Your data stays entirely within your tenant</li>
                <li><span>⚙️</span>Full infrastructure access and customisation</li>
                <li><span>📋</span>IaC templates provided for deployment</li>
            </ul>
        </div>
        <div class="compare-card highlight">
            <span class="compare-tag tag-cloud">ChunkIQ Enterprise Cloud</span>
            <h3>We deploy, we operate</h3>
            <ul>
                <li><span>☁️</span>Runs on ChunkIQ's managed Azure infrastructure</li>
                <li><span>🛠️</span>ChunkIQ manages all compute, storage, and AI services</li>
                <li><span>🔑</span>Read-only access to your Microsoft 365 tenant</li>
                <li><span>🚀</span>No infrastructure setup — onboard in hours, not days</li>
                <li><span>📊</span>Managed portal and search endpoint provided</li>
            </ul>
        </div>
    </div>
</section>

<!-- Ingestion sources -->
<section class="how-bg">
    <div class="center">
        <div class="section-label">Ingestion Sources</div>
        <h2 class="section-title">All four sources. Fully managed.</h2>
        <p class="section-sub">Grant ChunkIQ read-only access to your Microsoft 365 tenant and we ingest, process, and index content from every source automatically.</p>
    </div>
    <div class="sources-grid">
        <div class="source-card">
            <div class="source-card-icon">📁</div>
            <div>
                <h3>SharePoint</h3>
                <p>All sites and document libraries. PDF, Word, Excel, PowerPoint, HTML, and plain text — extracted, chunked, and indexed automatically on each run.</p>
            </div>
        </div>
        <div class="source-card">
            <div class="source-card-icon">💬</div>
            <div>
                <h3>Microsoft Teams</h3>
                <p>Channel messages, meeting transcripts, and files shared in Teams. Unified search across Teams content alongside all other Microsoft 365 sources.</p>
            </div>
        </div>
        <div class="source-card">
            <div class="source-card-icon">📓</div>
            <div>
                <h3>OneNote</h3>
                <p>All notebooks, sections, and pages. Section hierarchy is preserved as metadata so searches can be scoped to specific notebooks or sections.</p>
            </div>
        </div>
        <div class="source-card">
            <div class="source-card-icon">☁️</div>
            <div>
                <h3>OneDrive</h3>
                <p>Personal and shared drives across your organisation. Delta tokens track changes so only new or modified files are re-processed on each pipeline run.</p>
            </div>
        </div>
    </div>

    <div class="info-note">
        <div class="info-note-icon">🔑</div>
        <p>ChunkIQ Enterprise Cloud requires a <strong>read-only application registration</strong> in your tenant. We provide the exact permissions manifest — you approve the registration, we do the rest.</p>
    </div>
</section>

<!-- Pipeline stages -->
<section>
    <div class="center">
        <div class="section-label">Managed Pipeline</div>
        <h2 class="section-title">The same four-stage pipeline, run by us</h2>
        <p class="section-sub">The processing logic is identical to ChunkIQ Enterprise — the difference is that every stage runs on ChunkIQ's infrastructure, not yours.</p>
    </div>
    <div style="margin-top: 3.5rem;">

        <div class="pipeline-stage">
            <div class="stage-num">1</div>
            <div class="stage-icon">📥</div>
            <div class="stage-content">
                <h3>Ingest — All Sources</h3>
                <p>Our managed connector authenticates to your Microsoft 365 tenant securely and enumerates files across all four source platforms. Files are transferred to ChunkIQ's secure managed storage with content-hash deduplication.</p>
                <div class="stage-tags">
                    <span class="stage-tag">Microsoft 365 Connector</span>
                    <span class="stage-tag">Managed secure storage</span>
                    <span class="stage-tag">Delta sync</span>
                    <span class="stage-tag">Content hashing</span>
                </div>
            </div>
        </div>

        <div class="pipeline-stage">
            <div class="stage-num">2</div>
            <div class="stage-icon">📄</div>
            <div class="stage-content">
                <h3>Document Extraction</h3>
                <p>Format-aware extractors process every file type in parallel on ChunkIQ's managed compute. No OCR or Document Intelligence services needed — native extraction for minimal cost and maximum portability.</p>
                <div class="stage-tags">
                    <span class="stage-tag">.pdf</span>
                    <span class="stage-tag">.docx</span>
                    <span class="stage-tag">.xlsx</span>
                    <span class="stage-tag">.pptx</span>
                    <span class="stage-tag">.html</span>
                </div>
            </div>
        </div>

        <div class="pipeline-stage">
            <div class="stage-num">3</div>
            <div class="stage-icon">✂️</div>
            <div class="stage-content">
                <h3>Chunk &amp; Embed</h3>
                <p>Text is split into semantic chunks and embedded using our managed Azure OpenAI deployment. Embeddings are generated in batches to keep latency low and costs predictable — all on ChunkIQ's infrastructure.</p>
                <div class="stage-tags">
                    <span class="stage-tag">Hybrid semantic chunker</span>
                    <span class="stage-tag">Token-aware splitting</span>
                    <span class="stage-tag">Managed Azure OpenAI</span>
                    <span class="stage-tag">1,536-dim vectors</span>
                </div>
            </div>
        </div>

        <div class="pipeline-stage">
            <div class="stage-num">4</div>
            <div class="stage-icon">⚡</div>
            <div class="stage-content">
                <h3>Index &amp; Search</h3>
                <p>Chunks are upserted to a managed Azure AI Search index provisioned per customer. You receive a hybrid search endpoint ready to integrate with your applications or RAG pipelines — no index management required.</p>
                <div class="stage-tags">
                    <span class="stage-tag">Managed Azure AI Search</span>
                    <span class="stage-tag">BM25 + vector hybrid</span>
                    <span class="stage-tag">HNSW vector index</span>
                    <span class="stage-tag">Semantic re-ranking</span>
                    <span class="stage-tag">Dedicated search endpoint</span>
                </div>
            </div>
        </div>

    </div>
</section>

<!-- Features -->
<section class="how-bg">
    <div class="center">
        <div class="section-label">Cloud Benefits</div>
        <h2 class="section-title">Everything managed, nothing to operate</h2>
        <p class="section-sub">Enterprise Cloud is designed for organisations that want the power of the full ChunkIQ pipeline without the overhead of managing Azure infrastructure.</p>
    </div>
    <div class="features-grid">
        <div class="feature-card">
            <div class="feature-icon">🚀</div>
            <h3>Rapid Onboarding</h3>
            <p>No Azure subscription setup, no IaC deployment, no OpenAI quota requests. Register your Microsoft 365 app, share the credentials, and your pipeline is running within hours.</p>
        </div>
        <div class="feature-card">
            <div class="feature-icon">🔄</div>
            <h3>Automatic Updates</h3>
            <p>Pipeline improvements, extractor updates, and new features are deployed by ChunkIQ automatically. You always run the latest version with no manual upgrade steps.</p>
        </div>
        <div class="feature-card">
            <div class="feature-icon">📊</div>
            <h3>Managed Portal & Search</h3>
            <p>Access your pipeline dashboard and search portal via the ChunkIQ-hosted web application. Monitor ingestion status, chunk counts, and search your indexed content from day one.</p>
        </div>
        <div class="feature-card">
            <div class="feature-icon">🔁</div>
            <h3>Scheduled & On-Demand Runs</h3>
            <p>Set your pipeline to run on a schedule or trigger it on demand via the portal or API. Incremental processing keeps run times short regardless of tenant size.</p>
        </div>
        <div class="feature-card">
            <div class="feature-icon">🛡️</div>
            <h3>Dedicated Infrastructure</h3>
            <p>Each Enterprise Cloud customer gets a dedicated processing environment and search index — your data is never co-mingled with other customers' content.</p>
        </div>
        <div class="feature-card">
            <div class="feature-icon">📞</div>
            <h3>Fully Managed Support</h3>
            <p>ChunkIQ's team monitors your pipeline, handles infrastructure incidents, and provides dedicated support — so your team can focus on building with the search index, not maintaining it.</p>
        </div>
    </div>
</section>

<!-- CTA -->
<section class="cta-section">
    <h2>The complete pipeline — zero infrastructure overhead</h2>
    <p>ChunkIQ Enterprise Cloud gives you the full power of the end-to-end pipeline without a single Azure resource to provision. Connect your Microsoft 365 and start searching.</p>
    <div style="display:flex;gap:1rem;justify-content:center;flex-wrap:wrap;">
        @if (Route::has('register'))
            <a href="{{ route('register') }}" class="btn btn-white btn-lg">Request access</a>
        @endif
        <a href="{{ url('/products/enterprise') }}" class="btn btn-lg" style="color:#fff;border:1.5px solid rgba(255,255,255,0.5);">See self-hosted option</a>
    </div>
</section>

@endsection
