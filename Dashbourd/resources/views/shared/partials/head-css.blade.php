<!-- App css  (Mandatory in All Pages) -->
@vite(['resources/css/app.css'])

<!-- Google Fonts: Cairo & Plus Jakarta Sans -->
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;500;600;700;800;900&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

<!-- Tailwind Play CDN with Preflight disabled to generate all utility classes seamlessly -->
<script>
    tailwind = {
        config: {
            corePlugins: {
                preflight: false,
            },
            theme: {
                extend: {
                    colors: {
                        primary: '#6366f1',
                    }
                }
            }
        }
    }
</script>
<script src="https://cdn.tailwindcss.com"></script>

<!-- Iconify SVG Library -->
<script src="https://code.iconify.design/3/3.1.1/iconify.min.js"></script>

<!-- ApexCharts -->
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

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

    /* Compact, Elegant Stat Cards */
    .dt-stat-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 1rem;
        padding: 1.25rem 1.5rem;
        box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.04);
        display: flex !important;
        flex-direction: row !important;
        align-items: center !important;
        justify-content: space-between !important;
        transition: all 0.2s ease;
        min-height: 90px;
    }
    .dt-stat-card:hover {
        box-shadow: 0 4px 12px 0 rgba(0, 0, 0, 0.06);
        border-color: #cbd5e1;
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
        color: #64748b;
        text-transform: uppercase;
        letter-spacing: 0.025em;
        margin-bottom: 0.25rem;
    }
    .dt-stat-value {
        font-size: 1.5rem;
        font-weight: 900;
        color: #0f172a;
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
    }

    /* Cards & Containers */
    .dt-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 1rem;
        padding: 1.25rem;
        box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.04);
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
</style>