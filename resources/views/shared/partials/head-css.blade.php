<!-- App css (Mandatory in All Pages) -->
@vite(['resources/css/app.css'])

<!-- Google Fonts: Cairo & Plus Jakarta Sans -->
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;500;600;700;800;900&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

<!-- Tailwind Play CDN Configuration & Warning Filter -->
<script>
    (function() {
        const origWarn = console.warn;
        console.warn = function(...args) {
            if (args[0] && typeof args[0] === 'string' && (args[0].includes('cdn.tailwindcss.com') || args[0].includes('spacing.topbar'))) {
                return;
            }
            origWarn.apply(console, args);
        };
    })();
    tailwind = {
        config: {
            corePlugins: {
                preflight: false,
            },
            theme: {
                extend: {
                    spacing: {
                        topbar: '70px',
                        sidenav: '220px',
                    },
                    colors: {
                        primary: {
                            DEFAULT: '#4E8F35',
                            50: '#F5F9F2',
                            100: '#EBF4E8',
                            200: '#DCE8D4',
                            500: '#4E8F35',
                            600: '#3F742B',
                            700: '#325C22',
                        },
                        ddt: {
                            green: '#4E8F35',
                            sage: '#EBF4E8',
                            charcoal: '#303334',
                            gray: '#73777A',
                            border: '#E5E2DC',
                            canvas: '#F8F7F4',
                        }
                    }
                }
            }
        }
    };
</script>
<script src="https://cdn.tailwindcss.com"></script>

<!-- Iconify SVG Library (Deferred to prevent render blocking) -->
<script src="https://code.iconify.design/3/3.1.1/iconify.min.js" defer></script>

<!-- ApexCharts (Deferred to prevent render blocking) -->
<script src="https://cdn.jsdelivr.net/npm/apexcharts" defer></script>

<style>
 body, h1, h2, h3, h4, h5, h6, p, span, a, input, button, select, textarea {
 font-family: 'Cairo', 'Plus Jakarta Sans', system-ui, -apple-system, sans-serif !important;
 }
 
 /* Prevent iconify plugin from drawing solid currentColor blocks */
 .iconify {
 background-color: transparent !important;
 mask: none !important;
 -webkit-mask: none !important;
 }
 
 svg {
 display: inline-block;
 vertical-align: middle;
 flex-shrink: 0;
 max-width: 100%;
 }
 svg:not([width]) {
 width: 1.25rem;
 height: 1.25rem;
 }

 /* ─── Robust DT-System Layout & Responsive Design System ─── */

 /* Page Header & Top Actions */
 .page-header-container {
 display: flex;
 flex-direction: column;
 gap: 1rem;
 margin-bottom: 1.5rem;
 }
 @media (min-width: 768px) {
 .page-header-container {
 flex-direction: row;
 align-items: center;
 justify-content: space-between;
 }
 }
 .page-header-actions {
 display: flex;
 align-items: center;
 gap: 0.625rem;
 flex-wrap: wrap;
 }
 .page-header-actions a,
 .page-header-actions button {
 width: auto !important;
 max-width: fit-content !important;
 display: inline-flex !important;
 align-items: center;
 white-space: nowrap;
 cursor: pointer;
 }

 /* Responsive Grids (Guaranteed in RTL and across all screen sizes) */
 .dt-grid-4 {
 display: grid;
 grid-template-columns: repeat(1, minmax(0, 1fr));
 gap: 1.25rem;
 margin-bottom: 1.5rem;
 }
 @media (min-width: 640px) {
 .dt-grid-4 { grid-template-columns: repeat(2, minmax(0, 1fr)); }
 }
 @media (min-width: 1024px) {
 .dt-grid-4 { grid-template-columns: repeat(4, minmax(0, 1fr)); }
 }

 .dt-grid-3 {
 display: grid;
 grid-template-columns: repeat(1, minmax(0, 1fr));
 gap: 1.25rem;
 margin-bottom: 1.5rem;
 }
 @media (min-width: 640px) {
 .dt-grid-3 { grid-template-columns: repeat(2, minmax(0, 1fr)); }
 }
 @media (min-width: 1024px) {
 .dt-grid-3 { grid-template-columns: repeat(3, minmax(0, 1fr)); }
 }

 .dt-grid-2 {
 display: grid;
 grid-template-columns: repeat(1, minmax(0, 1fr));
 gap: 1.25rem;
 margin-bottom: 1.5rem;
 }
 @media (min-width: 768px) {
 .dt-grid-2 { grid-template-columns: repeat(2, minmax(0, 1fr)); }
 }

 /* Compact, Elegant Stat Cards (Unified DDT Theme) */
 .dt-stat-card {
 background: #ffffff;
 border: 1px solid #E5E2DC;
 border-radius: 1rem;
 padding: 1.25rem 1.5rem;
 box-shadow: 0 1px 3px 0 rgba(48, 51, 52, 0.04);
 display: flex !important;
 flex-direction: row !important;
 align-items: center !important;
 justify-content: space-between !important;
 transition: all 0.2s ease;
 min-height: 90px;
 }
 .dt-stat-card:hover {
 box-shadow: 0 3px 8px 0 rgba(48, 51, 52, 0.06);
 border-color: #4E8F35;
 }
 .dt-stat-info {
 display: flex;
 flex-direction: column;
 align-items: flex-start;
 text-align: right;
 }
 .dt-stat-label {
 font-size: 0.75rem;
 font-weight: 700;
 color: #73777A;
 text-transform: uppercase;
 letter-spacing: 0.025em;
 margin-bottom: 0.25rem;
 }
 .dt-stat-value {
 font-size: 1.5rem;
 font-weight: 900;
 color: #303334;
 line-height: 1.2;
 font-family: ui-monospace, SFMono-Regular, monospace;
 }
 .dt-stat-icon {
 width: 3rem;
 height: 3rem;
 border-radius: 0.875rem;
 display: flex;
 align-items: center;
 justify-content: center;
 flex-shrink: 0;
 background-color: #F5F3EE !important;
 border: 1px solid #E5E2DC !important;
 color: #4E8F35 !important;
 }

 /* Cards & Containers */
 .dt-card {
 background: #ffffff;
 border: 1px solid #E5E2DC;
 border-radius: 1rem;
 padding: 1.25rem;
 box-shadow: 0 1px 3px 0 rgba(48, 51, 52, 0.03);
 margin-bottom: 1.5rem;
 }

 /* Tables */
 .dt-table-responsive {
 width: 100%;
 overflow-x: auto;
 }
 .dt-table {
 width: 100%;
 text-align: right;
 font-size: 0.8125rem;
 border-collapse: collapse;
 }
 .dt-table th {
 padding: 0.75rem 0.875rem;
 border-bottom: 1px solid #e2e8f0;
 color: #64748b;
 font-weight: 700;
 font-size: 0.75rem;
 text-transform: uppercase;
 background: #f8fafc;
 text-align: right;
 }
 .dt-table td {
 padding: 0.875rem;
 border-bottom: 1px solid #f1f5f9;
 color: #1e293b;
 vertical-align: middle;
 text-align: right;
 }
 .dt-table tr:hover td {
 background-color: #f8fafc;
 }

  /* ══════════════════════════════════════════════════════════════
     CRITICAL LAYOUT FIXES — Sidebar + Topbar + Page Content
     ══════════════════════════════════════════════════════════════ */

  /* Body & Root */
  html, body { height: 100%; margin: 0; padding: 0; }
  body { background-color: #F8F7F4 !important; overflow-x: hidden; }

  /* Wrapper: flex row so sidebar and content sit side by side */
  .wrapper {
    display: flex !important;
    flex-direction: row !important;
    min-height: 100vh !important;
    align-items: stretch !important;
  }

  /* Sidebar: fixed height, highest z-index */
  aside#app-menu {
    flex-shrink: 0 !important;
    z-index: 9999 !important;
  }

  /* Page content: takes remaining width, scrollable */
  .page-content {
    flex: 1 1 0% !important;
    min-width: 0 !important;
    min-height: 100vh !important;
    display: flex !important;
    flex-direction: column !important;
    overflow-y: auto !important;
    background-color: #F8F7F4 !important;
  }

  /* Topbar: sticky within page-content, below sidebar */
  .app-header {
    position: sticky !important;
    top: 0 !important;
    z-index: 20 !important;
    background: #F8F7F4 !important;
    padding: 10px 12px 0 !important;
    flex-shrink: 0 !important;
  }

  .app-header .min-h-topbar {
    min-height: 56px !important;
  }

  /* Main content area: proper padding */
  main {
    flex: 1 1 auto !important;
    padding: 1.5rem 1.75rem !important;
  }

  /* Footer */
  footer.footer {
    flex-shrink: 0 !important;
  }

  /* Sidebar offset on large screens (lg = 1024px+) */
  @media (min-width: 1024px) {
    /* Sidebar is fixed positioned - page content needs left margin */
    .page-content {
      margin-inline-start: 220px !important;
      transition: margin-inline-start 0.3s ease !important;
    }
    /* When sidebar is collapsed */
    html.sidebar-collapsed .page-content {
      margin-inline-start: 74px !important;
    }
  }

  @media (max-width: 1023px) {
    .page-content {
      margin-inline-start: 0 !important;
    }
    main {
      padding: 1rem 1rem !important;
    }
  }
</style>