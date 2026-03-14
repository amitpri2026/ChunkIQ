@extends('layouts.public')

@section('title', 'Semantic Search — ChunkIQ')

@section('styles')
<style>
    .product-hero {
        background: linear-gradient(135deg, #0f062e 0%, #1a0c4f 60%, #251270 100%);
        color: #fff; padding: 90px 5% 80px; text-align: center;
    }
    .product-badge {
        display: inline-flex; align-items: center; gap: 0.5rem;
        background: rgba(251,191,36,0.15); border: 1px solid rgba(251,191,36,0.35);
        color: #fde68a; font-size: 0.78rem; font-weight: 600; letter-spacing: 0.8px;
        text-transform: uppercase; padding: 0.35rem 1rem; border-radius: 20px; margin-bottom: 1.5rem;
    }
    .product-hero h1 { font-size: clamp(2rem, 4.5vw, 3.2rem); font-weight: 800; line-height: 1.15; letter-spacing: -1px; max-width: 820px; margin: 0 auto 1.2rem; }
    .product-hero h1 em { font-style: normal; color: #fbbf24; }
    .product-hero p { font-size: 1.1rem; color: #94a3b8; max-width: 600px; margin: 0 auto 2.5rem; line-height: 1.7; }
    .hero-cta { display: flex; gap: 1rem; justify-content: center; flex-wrap: wrap; }
    .hero-icon { font-size: 4rem; margin-bottom: 1.5rem; }

    .stat-row { display: flex; gap: 3rem; justify-content: center; margin-top: 3rem; flex-wrap: wrap; }
    .stat-item { text-align: center; }
    .stat-item .num { font-size: 1.8rem; font-weight: 800; color: #fff; }
    .stat-item .lbl { font-size: 0.8rem; color: #64748b; margin-top: 0.2rem; }

    .search-modes { display: grid; grid-template-columns: repeat(auto-fit, minmax(260px, 1fr)); gap: 1.5rem; margin-top: 3rem; }
    .search-mode-card { background: #fff; border-radius: 14px; border: 1px solid var(--border); padding: 2rem 1.8rem; text-align: center; transition: box-shadow 0.2s, transform 0.2s; }
    .search-mode-card:hover { box-shadow: 0 8px 32px rgba(15,98,254,0.1); transform: translateY(-3px); }
    .mode-icon { font-size: 2.5rem; margin-bottom: 1rem; }
    .search-mode-card h3 { font-size: 1.05rem; font-weight: 700; margin-bottom: 0.5rem; }
    .search-mode-card p { font-size: 0.87rem; color: var(--muted); line-height: 1.6; }
    .mode-badge { display: inline-block; margin-top: 1rem; font-size: 0.72rem; font-weight: 700; background: #eff6ff; color: var(--blue); border-radius: 4px; padding: 0.2rem 0.5rem; }

    .score-explainer { display: flex; align-items: center; gap: 1rem; background: var(--light); border-radius: 12px; padding: 1.5rem 2rem; margin-top: 2rem; flex-wrap: wrap; }
    .score-step { display: flex; align-items: center; gap: 0.75rem; flex: 1; min-width: 160px; }
    .score-step-icon { font-size: 1.5rem; flex-shrink: 0; }
    .score-step p { font-size: 0.85rem; color: var(--text); font-weight: 600; }
    .score-arrow { font-size: 1.2rem; color: var(--muted); flex-shrink: 0; }
</style>
@endsection

@section('content')

<!-- Hero -->
<section class="product-hero">
    <div class="hero-icon">🔍</div>
    <div class="product-badge">Azure AI Search</div>
    <h1>Hybrid <em>Semantic Search</em></h1>
    <p>Combine BM25 keyword search with 1,536-dimensional vector embeddings and Azure AI semantic re-ranking to deliver the most relevant results across all your indexed documents.</p>
    <div class="hero-cta">
        @if (Route::has('register'))
            <a href="{{ route('register') }}" class="btn btn-primary btn-lg">Get started free</a>
        @endif
        <a href="{{ url('/') }}#tech-stack" class="btn btn-lg" style="color:#fff;border:1.5px solid rgba(255,255,255,0.4);">See the tech stack</a>
    </div>
    <div class="stat-row">
        <div class="stat-item"><div class="num">1,536</div><div class="lbl">Vector dimensions</div></div>
        <div class="stat-item"><div class="num">HNSW</div><div class="lbl">Vector index type</div></div>
        <div class="stat-item"><div class="num">Hybrid</div><div class="lbl">BM25 + Vector</div></div>
        <div class="stat-item"><div class="num">Semantic</div><div class="lbl">Re-ranking</div></div>
    </div>
</section>

<!-- Search modes -->
<section class="how-bg">
    <div class="center">
        <div class="section-label">Search Modes</div>
        <h2 class="section-title">Three modes. One unified score.</h2>
        <p class="section-sub">ChunkIQ doesn't choose between keyword and vector search — it runs all three simultaneously and combines their scores for the best results.</p>
    </div>
    <div class="search-modes">
        <div class="search-mode-card">
            <div class="mode-icon">📝</div>
            <h3>BM25 Full-Text Search</h3>
            <p>Classic keyword search with TF-IDF scoring. Excellent for exact term matches, product names, codes, and acronyms that semantic search might miss.</p>
            <span class="mode-badge">Azure AI Search</span>
        </div>
        <div class="search-mode-card">
            <div class="mode-icon">🧠</div>
            <h3>Vector Search</h3>
            <p>1,536-dimensional embeddings from Azure OpenAI text-embedding-3-small. Finds conceptually similar content even when exact keywords don't match.</p>
            <span class="mode-badge">HNSW Index</span>
        </div>
        <div class="search-mode-card">
            <div class="mode-icon">🎯</div>
            <h3>Semantic Re-Ranking</h3>
            <p>Azure AI Search's semantic ranker re-scores the top results using a deep language model, promoting the most contextually relevant chunks to the top.</p>
            <span class="mode-badge">Azure Semantic Ranker</span>
        </div>
    </div>

    <div class="score-explainer">
        <div class="score-step"><div class="score-step-icon">📝</div><p>BM25 score</p></div>
        <div class="score-arrow">+</div>
        <div class="score-step"><div class="score-step-icon">🧠</div><p>Vector score</p></div>
        <div class="score-arrow">→</div>
        <div class="score-step"><div class="score-step-icon">🔀</div><p>RRF fusion</p></div>
        <div class="score-arrow">→</div>
        <div class="score-step"><div class="score-step-icon">🎯</div><p>Semantic re-rank</p></div>
        <div class="score-arrow">→</div>
        <div class="score-step"><div class="score-step-icon">✅</div><p>Final ranked results</p></div>
    </div>
</section>

<!-- Features -->
<section>
    <div class="center">
        <div class="section-label">Capabilities</div>
        <h2 class="section-title">Enterprise search, built for unstructured data</h2>
        <p class="section-sub">Every chunk carries rich metadata — filter by source platform, file type, date range, or site to narrow results precisely.</p>
    </div>
    <div class="features-grid">
        <div class="feature-card">
            <div class="feature-icon">⚡</div>
            <h3>Reciprocal Rank Fusion (RRF)</h3>
            <p>BM25 and vector scores are combined using RRF — a robust fusion algorithm that consistently outperforms single-mode ranking across diverse query types.</p>
        </div>
        <div class="feature-card">
            <div class="feature-icon">🏷️</div>
            <h3>Metadata Filtering</h3>
            <p>Filter search results by source platform (SharePoint, Teams, OneNote, OneDrive), site URL, file type, content type, or date range using Azure AI Search filters.</p>
        </div>
        <div class="feature-card">
            <div class="feature-icon">✂️</div>
            <h3>Chunk-Level Retrieval</h3>
            <p>Documents are split into semantic chunks before indexing. Search returns the exact passage most relevant to the query, not just the document it's in.</p>
        </div>
        <div class="feature-card">
            <div class="feature-icon">🗺️</div>
            <h3>Provenance in Every Result</h3>
            <p>Every search result includes the source platform, site, file path, page number, chunk index, and modification date — so you always know exactly where the information came from.</p>
        </div>
        <div class="feature-card">
            <div class="feature-icon">🔢</div>
            <h3>1,536-dim HNSW Vector Index</h3>
            <p>Vectors are indexed using Hierarchical Navigable Small World (HNSW) graphs within Azure AI Search — delivering fast approximate nearest-neighbour search at scale.</p>
        </div>
        <div class="feature-card">
            <div class="feature-icon">🔌</div>
            <h3>RAG-Ready Output</h3>
            <p>Search results are formatted for direct use as context in Retrieval-Augmented Generation (RAG) pipelines with Azure OpenAI or any LLM of your choice.</p>
        </div>
    </div>
</section>

<!-- How it works -->
<section class="how-bg" id="how-it-works">
    <div class="center">
        <div class="section-label">How it works</div>
        <h2 class="section-title">From query to ranked results</h2>
    </div>
    <div class="steps">
        <div class="step">
            <div class="step-num">Step 01</div>
            <div class="step-icon">✍️</div>
            <h3>Query Embedding</h3>
            <p>The user's query is vectorised using the same Azure OpenAI text-embedding-3-small model used at index time, producing a 1,536-dim query vector.</p>
        </div>
        <div class="step">
            <div class="step-num">Step 02</div>
            <div class="step-icon">🔍</div>
            <h3>Parallel Retrieval</h3>
            <p>Azure AI Search runs BM25 keyword search and HNSW vector search simultaneously, returning candidate chunks from each mode.</p>
        </div>
        <div class="step">
            <div class="step-num">Step 03</div>
            <div class="step-icon">🔀</div>
            <h3>RRF Score Fusion</h3>
            <p>Reciprocal Rank Fusion merges the BM25 and vector candidate lists into a single ranked list, balancing the strengths of both retrieval modes.</p>
        </div>
        <div class="step">
            <div class="step-num">Step 04</div>
            <div class="step-icon">🎯</div>
            <h3>Semantic Re-Ranking</h3>
            <p>The top N fused results are re-scored by the Azure AI semantic ranker, which uses a cross-encoder model to promote the most contextually relevant chunks.</p>
        </div>
    </div>
</section>

<!-- Tech Stack -->
<section class="tech-bg">
    <div class="center" style="color:#fff;">
        <div class="section-label" style="color:#fbbf24;">Under the hood</div>
        <h2 class="section-title">Built on Azure AI Search + Azure OpenAI</h2>
    </div>
    <div class="tech-grid">
        <div class="tech-card"><div class="label">Search Service</div><div class="value">Azure AI Search</div></div>
        <div class="tech-card"><div class="label">Keyword Search</div><div class="value">BM25 (Okapi BM25)</div></div>
        <div class="tech-card"><div class="label">Vector Index</div><div class="value">HNSW — 1,536 dimensions</div></div>
        <div class="tech-card"><div class="label">Embedding Model</div><div class="value">Azure OpenAI text-embedding-3-small</div></div>
        <div class="tech-card"><div class="label">Score Fusion</div><div class="value">Reciprocal Rank Fusion (RRF)</div></div>
        <div class="tech-card"><div class="label">Re-Ranking</div><div class="value">Azure AI Semantic Ranker</div></div>
        <div class="tech-card"><div class="label">Chunking</div><div class="value">Hybrid chunker + tiktoken</div></div>
        <div class="tech-card"><div class="label">Portal</div><div class="value">Laravel 12 · Blade · Tailwind CSS</div></div>
    </div>
</section>

<!-- CTA -->
<section class="cta-section">
    <h2>Unlock the knowledge in your documents</h2>
    <p>Hybrid semantic search over all your Microsoft 365 content — SharePoint, Teams, OneNote, and OneDrive — in a single query.</p>
    <div style="display:flex;gap:1rem;justify-content:center;flex-wrap:wrap;">
        @if (Route::has('register'))
            <a href="{{ route('register') }}" class="btn btn-white btn-lg">Create your account</a>
        @endif
        <a href="{{ url('/products/pipeline') }}" class="btn btn-lg" style="color:#fff;border:1.5px solid rgba(255,255,255,0.5);">View full pipeline →</a>
    </div>
</section>

@endsection
