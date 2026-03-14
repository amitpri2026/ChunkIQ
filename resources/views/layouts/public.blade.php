<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'ChunkIQ — Unstructured Data Pipeline')</title>
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
        nav.public-nav {
            display: flex; align-items: center; justify-content: space-between;
            padding: 0 5%; height: 64px;
            border-bottom: 1px solid var(--border);
            position: sticky; top: 0; background: rgba(255,255,255,0.96);
            backdrop-filter: blur(8px); z-index: 100;
        }
        .nav-logo { font-size: 1.15rem; font-weight: 700; color: var(--navy); letter-spacing: -0.5px; }
        .nav-logo span { color: var(--blue); }
        .nav-links { display: flex; gap: 1.75rem; align-items: center; }
        .nav-links > a { font-size: 0.9rem; color: var(--muted); transition: color 0.2s; }
        .nav-links > a:hover { color: var(--blue); }

        /* Products dropdown */
        .nav-dropdown { position: relative; }
        .nav-dropdown-btn {
            display: flex; align-items: center; gap: 0.3rem;
            font-size: 0.9rem; color: var(--muted); background: none; border: none;
            cursor: pointer; padding: 0; font-family: inherit; transition: color 0.2s;
        }
        .nav-dropdown-btn:hover { color: var(--blue); }
        .nav-dropdown-btn svg { width: 14px; height: 14px; transition: transform 0.2s; }
        .nav-dropdown-btn.open svg { transform: rotate(180deg); }
        .dropdown-panel {
            position: absolute; top: calc(100% + 10px); left: 50%; transform: translateX(-50%);
            background: #fff; border: 1px solid var(--border); border-radius: 12px;
            box-shadow: 0 8px 32px rgba(0,0,0,0.12); padding: 0.5rem;
            min-width: 240px; z-index: 200;
        }
        .dropdown-item {
            display: flex; align-items: center; gap: 0.75rem;
            padding: 0.65rem 1rem; border-radius: 8px;
            font-size: 0.875rem; color: var(--text); transition: background 0.15s, color 0.15s;
        }
        .dropdown-item:hover { background: #eff6ff; color: var(--blue); }
        .dropdown-item-icon { font-size: 1.1rem; width: 1.5rem; text-align: center; flex-shrink: 0; }
        .dropdown-item-text { font-weight: 600; }
        .dropdown-divider { height: 1px; background: var(--border); margin: 0.3rem 0.5rem; }

        /* Buttons */
        .btn { display: inline-flex; align-items: center; gap: 0.4rem; padding: 0.55rem 1.2rem; border-radius: 6px; font-size: 0.9rem; font-weight: 600; cursor: pointer; transition: all 0.2s; border: none; }
        .btn-primary { background: var(--blue); color: #fff !important; }
        .btn-primary:hover { background: var(--blue-dark); }
        .btn-outline { border: 1.5px solid var(--blue); color: var(--blue); background: transparent; }
        .btn-outline:hover { background: var(--blue); color: #fff; }
        .btn-lg { padding: 0.85rem 2rem; font-size: 1rem; border-radius: 8px; }
        .btn-white { background: #fff; color: var(--blue); font-weight: 700; }
        .btn-white:hover { background: #f0f4ff; }

        /* Section common */
        section { padding: 90px 5%; }
        .section-label { font-size: 0.78rem; font-weight: 700; color: var(--blue); letter-spacing: 1px; text-transform: uppercase; margin-bottom: 0.75rem; }
        h2.section-title { font-size: clamp(1.8rem, 3vw, 2.5rem); font-weight: 800; letter-spacing: -0.5px; line-height: 1.2; margin-bottom: 1rem; }
        .section-sub { font-size: 1.05rem; color: var(--muted); max-width: 580px; line-height: 1.7; }
        .center { text-align: center; }
        .center .section-sub { margin: 0 auto; }

        /* Features grid */
        .features-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 1.5rem; margin-top: 3.5rem; }
        .feature-card { border: 1px solid var(--border); border-radius: 12px; padding: 1.8rem; transition: box-shadow 0.2s, border-color 0.2s; }
        .feature-card:hover { box-shadow: 0 4px 24px rgba(15,98,254,0.1); border-color: #bfdbfe; }
        .feature-icon { font-size: 1.6rem; margin-bottom: 1rem; }
        .feature-card h3 { font-size: 1rem; font-weight: 700; margin-bottom: 0.4rem; }
        .feature-card p { font-size: 0.87rem; color: var(--muted); line-height: 1.6; }

        /* Steps */
        .how-bg { background: var(--light); }
        .steps { display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 2rem; margin-top: 3.5rem; }
        .step { background: #fff; border-radius: 12px; padding: 2rem 1.8rem; border: 1px solid var(--border); }
        .step-num { font-size: 0.75rem; font-weight: 700; color: var(--blue); letter-spacing: 1px; text-transform: uppercase; margin-bottom: 1rem; }
        .step-icon { font-size: 2rem; margin-bottom: 1rem; }
        .step h3 { font-size: 1.05rem; font-weight: 700; margin-bottom: 0.5rem; }
        .step p { font-size: 0.88rem; color: var(--muted); line-height: 1.6; }

        /* Tech */
        .tech-bg { background: var(--slate); color: #fff; }
        .tech-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1.5rem; margin-top: 3rem; }
        .tech-card { background: rgba(255,255,255,0.06); border: 1px solid rgba(255,255,255,0.1); border-radius: 10px; padding: 1.4rem 1.2rem; }
        .tech-card .label { font-size: 0.72rem; font-weight: 700; letter-spacing: 1px; text-transform: uppercase; color: #60a5fa; margin-bottom: 0.5rem; }
        .tech-card .value { font-size: 0.95rem; color: #e2e8f0; font-weight: 600; }

        /* Tags */
        .tags { display: flex; flex-wrap: wrap; gap: 0.5rem; margin-top: 1rem; }
        .tag { font-size: 0.75rem; font-weight: 600; background: #eff6ff; color: var(--blue); border-radius: 4px; padding: 0.25rem 0.6rem; }

        /* CTA */
        .cta-section { background: linear-gradient(135deg, var(--blue) 0%, #1d4ed8 100%); color: #fff; text-align: center; padding: 80px 5%; }
        .cta-section h2 { font-size: clamp(1.8rem, 3vw, 2.4rem); font-weight: 800; margin-bottom: 1rem; }
        .cta-section p { color: #bfdbfe; max-width: 540px; margin: 0 auto 2rem; line-height: 1.7; }

        /* Footer */
        footer.public-footer { border-top: 1px solid var(--border); padding: 2rem 5%; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem; }
        .footer-logo { font-size: 1rem; font-weight: 700; color: var(--navy); }
        .footer-logo span { color: var(--blue); }
        .public-footer p { font-size: 0.82rem; color: var(--muted); }

        @media (max-width: 640px) {
            .nav-links { display: none; }
        }
    </style>
    @yield('styles')
</head>
<body>

<!-- Navigation -->
<nav class="public-nav" x-data="{ productsOpen: false }">
    <a href="{{ url('/') }}" class="nav-logo">Chunk<span>IQ</span></a>
    <div class="nav-links">
        <a href="{{ url('/') }}#sources">Sources</a>
        <a href="{{ url('/') }}#how-it-works">How it works</a>

        <!-- Products dropdown -->
        <div class="nav-dropdown" x-data="{ open: false }" @mouseenter="open = true" @mouseleave="open = false">
            <button class="nav-dropdown-btn" :class="{ open: open }" @click="open = !open">
                Products
                <svg viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"/></svg>
            </button>
            <div class="dropdown-panel" x-show="open" x-transition:enter="transition ease-out duration-150" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100" x-transition:leave="transition ease-in duration-100" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95" style="display:none;">
                <a href="{{ url('/products/sharepoint') }}" class="dropdown-item">
                    <span class="dropdown-item-icon">📁</span>
                    <span class="dropdown-item-text">SharePoint Extractor</span>
                </a>
                <a href="{{ url('/products/teams') }}" class="dropdown-item">
                    <span class="dropdown-item-icon">💬</span>
                    <span class="dropdown-item-text">Teams & Search Portal</span>
                </a>
                <a href="{{ url('/products/onenote') }}" class="dropdown-item">
                    <span class="dropdown-item-icon">📓</span>
                    <span class="dropdown-item-text">OneNote Extractor</span>
                </a>
                <a href="{{ url('/products/onedrive') }}" class="dropdown-item">
                    <span class="dropdown-item-icon">☁️</span>
                    <span class="dropdown-item-text">OneDrive Extractor</span>
                </a>
                <div class="dropdown-divider"></div>
                <a href="{{ url('/products/pipeline') }}" class="dropdown-item">
                    <span class="dropdown-item-icon">⚡</span>
                    <span class="dropdown-item-text">Processor</span>
                </a>
            </div>
        </div>

        <a href="{{ url('/') }}#tech-stack">Technology</a>

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

@yield('content')

<!-- Footer -->
<footer class="public-footer">
    <div class="footer-logo">Chunk<span>IQ</span></div>
    <p>Python Extraction &middot; Azure AI Search &middot; Microsoft 365 Integration</p>
    <p style="font-size:0.8rem;color:#94a3b8;">&copy; {{ date('Y') }} ChunkIQ. All rights reserved.</p>
</footer>

</body>
</html>
