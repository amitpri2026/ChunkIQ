<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ChunkIQ — Unstructured Data Pipeline</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        :root {
            --blue:      #0f62fe;
            --blue-dark: #0043ce;
            --navy:      #001141;
            --slate:     #1a2332;
            --light:     #f4f6fb;
            --text:      #1c2438;
            --muted:     #5a6478;
            --border:    #e2e8f0;
        }
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: 'Segoe UI', system-ui, sans-serif; color: var(--text); background: #fff; }
        a { color: inherit; text-decoration: none; }

        /* Nav */
        nav {
            display: flex; align-items: center; justify-content: space-between;
            padding: 0 5%; height: 64px;
            border-bottom: 1px solid var(--border);
            position: sticky; top: 0; background: rgba(255,255,255,0.96);
            backdrop-filter: blur(8px); z-index: 100;
        }
        .nav-logo { font-size: 1.15rem; font-weight: 700; color: var(--navy); letter-spacing: -0.5px; }
        .nav-logo span { color: var(--blue); }
        .nav-links { display: flex; gap: 2rem; align-items: center; }
        .nav-links a { font-size: 0.9rem; color: var(--muted); transition: color 0.2s; }
        .nav-links a:hover { color: var(--blue); }
        .btn { display: inline-flex; align-items: center; gap: 0.4rem; padding: 0.55rem 1.2rem; border-radius: 6px; font-size: 0.9rem; font-weight: 600; cursor: pointer; transition: all 0.2s; border: none; }
        .btn-primary { background: var(--blue); color: #fff !important; }
        .btn-primary:hover { background: var(--blue-dark); }
        .btn-outline { border: 1.5px solid var(--blue); color: var(--blue); background: transparent; }
        .btn-outline:hover { background: var(--blue); color: #fff; }
        .btn-lg { padding: 0.85rem 2rem; font-size: 1rem; border-radius: 8px; }

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

        /* Section common */
        section { padding: 90px 5%; }
        .section-label { font-size: 0.78rem; font-weight: 700; color: var(--blue); letter-spacing: 1px; text-transform: uppercase; margin-bottom: 0.75rem; }
        h2.section-title { font-size: clamp(1.8rem, 3vw, 2.5rem); font-weight: 800; letter-spacing: -0.5px; line-height: 1.2; margin-bottom: 1rem; }
        .section-sub { font-size: 1.05rem; color: var(--muted); max-width: 580px; line-height: 1.7; }
        .center { text-align: center; }
        .center .section-sub { margin: 0 auto; }

        /* Sources section */
        .how-bg { background: var(--light); }
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

        /* How it works */
        .steps { display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 2rem; margin-top: 3.5rem; }
        .step { background: #fff; border-radius: 12px; padding: 2rem 1.8rem; border: 1px solid var(--border); }
        .step-num { font-size: 0.75rem; font-weight: 700; color: var(--blue); letter-spacing: 1px; text-transform: uppercase; margin-bottom: 1rem; }
        .step-icon { font-size: 2rem; margin-bottom: 1rem; }
        .step h3 { font-size: 1.05rem; font-weight: 700; margin-bottom: 0.5rem; }
        .step p { font-size: 0.88rem; color: var(--muted); line-height: 1.6; }

        /* File types */
        .filetypes-grid { display: flex; flex-wrap: wrap; gap: 0.75rem; justify-content: center; margin-top: 2.5rem; }
        .filetype-pill {
            display: flex; align-items: center; gap: 0.5rem;
            background: #fff; border: 1px solid var(--border); border-radius: 100px;
            padding: 0.5rem 1.1rem; font-size: 0.85rem; font-weight: 600;
            color: var(--text); box-shadow: 0 1px 4px rgba(0,0,0,0.05);
        }

        /* Features */
        .features-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 1.5rem; margin-top: 3.5rem; }
        .feature-card { border: 1px solid var(--border); border-radius: 12px; padding: 1.8rem; transition: box-shadow 0.2s, border-color 0.2s; }
        .feature-card:hover { box-shadow: 0 4px 24px rgba(15,98,254,0.1); border-color: #bfdbfe; }
        .feature-icon { font-size: 1.6rem; margin-bottom: 1rem; }
        .feature-card h3 { font-size: 1rem; font-weight: 700; margin-bottom: 0.4rem; }
        .feature-card p { font-size: 0.87rem; color: var(--muted); line-height: 1.6; }

        /* Tech */
        .tech-bg { background: var(--slate); color: #fff; }
        .tech-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1.5rem; margin-top: 3rem; }
        .tech-card { background: rgba(255,255,255,0.06); border: 1px solid rgba(255,255,255,0.1); border-radius: 10px; padding: 1.4rem 1.2rem; }
        .tech-card .label { font-size: 0.72rem; font-weight: 700; letter-spacing: 1px; text-transform: uppercase; color: #60a5fa; margin-bottom: 0.5rem; }
        .tech-card .value { font-size: 0.95rem; color: #e2e8f0; font-weight: 600; }

        /* CTA */
        .cta-section { background: linear-gradient(135deg, var(--blue) 0%, #1d4ed8 100%); color: #fff; text-align: center; padding: 80px 5%; }
        .cta-section h2 { font-size: clamp(1.8rem, 3vw, 2.4rem); font-weight: 800; margin-bottom: 1rem; }
        .cta-section p { color: #bfdbfe; max-width: 540px; margin: 0 auto 2rem; line-height: 1.7; }
        .btn-white { background: #fff; color: var(--blue); font-weight: 700; }
        .btn-white:hover { background: #f0f4ff; }

        /* Footer */
        footer { border-top: 1px solid var(--border); padding: 2rem 5%; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem; }
        .footer-logo { font-size: 1rem; font-weight: 700; color: var(--navy); }
        .footer-logo span { color: var(--blue); }
        footer p { font-size: 0.82rem; color: var(--muted); }

        @media (max-width: 640px) {
            .nav-links { display: none; }
            .hero-stats { gap: 1.5rem; }
        }
    </style>
</head>
<body>

<!-- Navigation -->
<nav>
    <div class="nav-logo">Chunk<span>IQ</span></div>
    <div class="nav-links">
        <a href="#sources">Sources</a>
        <a href="#how-it-works">How it works</a>
        <a href="#features">Features</a>
        <a href="#tech-stack">Technology</a>
        @if (Route::has('login'))
            @auth
                <a href="{{ url('/dashboard') }}" class="btn btn-primary">Dashboard</a>
            @else
                <a href="{{ route('login') }}" class="btn btn-outline">Sign in</a>
                @if (Route::has('register'))
                    <a href="{{ route('register') }}" class="btn btn-primary">Get started</a>
                @endif
            @endauth
        @endif
    </div>
</nav>

<!-- Hero -->
<section class="hero">
    <div class="hero-badge">Unstructured Data Processing Pipeline</div>
    <h1>Extract &amp; Index <em>Unstructured Data</em> from Across Your Organisation</h1>
    <p>Automatically ingest files from SharePoint, Teams, OneNote, and OneDrive — extract text with Python, chunk, embed, and index for AI-powered semantic search.</p>
    <div class="hero-cta">
        @if (Route::has('register'))
            <a href="{{ route('register') }}" class="btn btn-primary btn-lg">Get started free</a>
        @endif
        <a href="#how-it-works" class="btn btn-lg" style="color:#fff;border:1.5px solid rgba(255,255,255,0.4);">See how it works</a>
    </div>
    <div class="hero-stats">
        <div class="stat"><div class="stat-num">4</div><div class="stat-label">Source platforms</div></div>
        <div class="stat"><div class="stat-num">916+</div><div class="stat-label">Chunks indexed</div></div>
        <div class="stat"><div class="stat-num">1,536</div><div class="stat-label">Vector dimensions</div></div>
        <div class="stat"><div class="stat-num">&lt;5 min</div><div class="stat-label">Full pipeline run</div></div>
    </div>

    <!-- Source bar inside hero -->
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
            <p>Reads files shared in Teams channels and private chats. Discovers all team sites and libraries via Microsoft Graph API automatically.</p>
            <div class="source-types">
                <span class="source-type">Channel Files</span>
                <span class="source-type">Team Sites</span>
                <span class="source-type">Private Channels</span>
            </div>
        </div>

        <div class="source-card">
            <div class="source-icon">📓</div>
            <h3>OneNote</h3>
            <p>Extracts notebook pages as HTML, parses clean text via BeautifulSoup, and processes any Office attachments embedded in notes.</p>
            <div class="source-types">
                <span class="source-type">Notebooks</span>
                <span class="source-type">Sections</span>
                <span class="source-type">Pages (HTML)</span>
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
        <p class="section-sub">Python-based extraction — no external OCR service required. Each format has a dedicated extractor.</p>
        <div class="filetypes-grid">
            <div class="filetype-pill">📄 PDF <small style="color:var(--muted);font-weight:400;">pypdf</small></div>
            <div class="filetype-pill">📝 Word (.docx) <small style="color:var(--muted);font-weight:400;">python-docx</small></div>
            <div class="filetype-pill">📊 Excel (.xlsx/.xls) <small style="color:var(--muted);font-weight:400;">openpyxl</small></div>
            <div class="filetype-pill">📽️ PowerPoint (.pptx) <small style="color:var(--muted);font-weight:400;">python-pptx</small></div>
            <div class="filetype-pill">📓 OneNote HTML <small style="color:var(--muted);font-weight:400;">BeautifulSoup</small></div>
            <div class="filetype-pill">📋 CSV <small style="color:var(--muted);font-weight:400;">csv module</small></div>
            <div class="filetype-pill">🔧 JSON <small style="color:var(--muted);font-weight:400;">json module</small></div>
            <div class="filetype-pill">📃 Plain Text / Markdown <small style="color:var(--muted);font-weight:400;">utf-8 decode</small></div>
            <div class="filetype-pill">🧮 Macro Excel (.xlsm) <small style="color:var(--muted);font-weight:400;">openpyxl + strip VBA</small></div>
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
        <div class="tech-card"><div class="label">PDF Extraction</div><div class="value">pypdf (Python)</div></div>
        <div class="tech-card"><div class="label">Word / PowerPoint</div><div class="value">python-docx · python-pptx</div></div>
        <div class="tech-card"><div class="label">Excel</div><div class="value">openpyxl (sheets + tables)</div></div>
        <div class="tech-card"><div class="label">HTML (OneNote)</div><div class="value">BeautifulSoup4</div></div>
        <div class="tech-card"><div class="label">Embeddings</div><div class="value">Azure OpenAI text-embedding-3-small</div></div>
        <div class="tech-card"><div class="label">Search</div><div class="value">Azure AI Search (Hybrid + Semantic)</div></div>
        <div class="tech-card"><div class="label">Ingest Sources</div><div class="value">Microsoft Graph API</div></div>
        <div class="tech-card"><div class="label">Pipeline Runtime</div><div class="value">Python · Azure Functions</div></div>
        <div class="tech-card"><div class="label">Vector Dimensions</div><div class="value">1,536-dim HNSW Index</div></div>
        <div class="tech-card"><div class="label">Chunking</div><div class="value">Hybrid chunker · tiktoken</div></div>
        <div class="tech-card"><div class="label">Portal</div><div class="value">Laravel 12 · Blade · Tailwind CSS</div></div>
    </div>
</section>

<!-- CTA -->
<section class="cta-section">
    <h2>Ready to unlock your unstructured data?</h2>
    <p>Connect your Microsoft 365 tenant and have your first documents extracted, chunked, and indexed in minutes.</p>
    <div style="display:flex;gap:1rem;justify-content:center;flex-wrap:wrap;">
        @if (Route::has('register'))
            <a href="{{ route('register') }}" class="btn btn-white btn-lg">Create your account</a>
        @endif
        @if (Route::has('login'))
            <a href="{{ route('login') }}" class="btn btn-lg" style="color:#fff;border:1.5px solid rgba(255,255,255,0.5);">Sign in</a>
        @endif
    </div>
</section>

<!-- Footer -->
<footer>
    <div class="footer-logo">Chunk<span>IQ</span></div>
    <p>Python Extraction &middot; Azure AI Search &middot; Microsoft 365 Integration</p>
    <p style="font-size:0.8rem;color:#94a3b8;">&copy; {{ date('Y') }} ChunkIQ. All rights reserved.</p>
</footer>

</body>
</html>
