<style>
    .cv-toolbar {
        position: fixed;
        bottom: 30px;
        right: 30px;
        display: flex;
        gap: 12px;
        z-index: 1000;
        flex-wrap: wrap;
        justify-content: flex-end;
    }

    .cv-btn,
    .cv-zoom-indicator {
        background: #0f172a;
        color: #fff;
        border: 1px solid #334155;
        padding: 10px 16px;
        border-radius: 8px;
        font-family: system-ui, -apple-system, sans-serif;
        font-size: 14px;
        font-weight: 500;
        display: flex;
        align-items: center;
        gap: 8px;
        text-decoration: none;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        transition: all 0.2s;
    }

    .cv-btn {
        cursor: pointer;
    }

    .cv-btn:hover {
        background: #1e293b;
        transform: translateY(-2px);
    }

    .cv-btn-primary {
        background: #06b6d4;
        border-color: #06b6d4;
        color: #0f172a;
        font-weight: 600;
    }

    .cv-btn-primary:hover {
        background: #0891b2;
    }

    .cv-zoom-indicator {
        min-width: 72px;
        justify-content: center;
        font-variant-numeric: tabular-nums;
        color: #cbd5e1;
        background: #111827;
    }

    .page {
        transform: scale(var(--cv-zoom, 1));
        transform-origin: top center;
        transition: transform 0.2s ease;
    }

    body.cv-zoomed {
        min-height: calc(297mm * var(--cv-zoom, 1));
    }

    @media (max-width: 640px) {
        .cv-toolbar {
            left: 12px;
            right: 12px;
            bottom: 12px;
        }

        .cv-btn,
        .cv-zoom-indicator {
            padding: 9px 12px;
            font-size: 12px;
        }
    }

    @media print {
        .cv-toolbar {
            display: none !important;
        }

        .page {
            transform: none !important;
        }
    }
</style>

<div class="cv-toolbar no-print" data-cv-zoom-toolbar>
    <button type="button" data-cv-zoom-out class="cv-btn" title="Zoom out">
        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" width="18" height="18">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"></path>
        </svg>
        Zoom Out
    </button>

    <button type="button" data-cv-zoom-reset class="cv-zoom-indicator" title="Reset zoom">
        <span data-cv-zoom-value>100%</span>
    </button>

    <button type="button" data-cv-zoom-in class="cv-btn" title="Zoom in">
        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" width="18" height="18">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
        </svg>
        Zoom In
    </button>

    <button onclick="window.print()" class="cv-btn cv-btn-primary">
        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" width="18" height="18">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2-2v4h10z">
            </path>
        </svg>
        Print / Save PDF
    </button>
</div>

<script>
    (() => {
        const minZoom = 60;
        const maxZoom = 160;
        const step = 10;
        const storageKey = 'cv-preview-zoom';
        const page = document.querySelector('.page');
        const value = document.querySelector('[data-cv-zoom-value]');
        const zoomIn = document.querySelector('[data-cv-zoom-in]');
        const zoomOut = document.querySelector('[data-cv-zoom-out]');
        const zoomReset = document.querySelector('[data-cv-zoom-reset]');

        if (! page || ! value || ! zoomIn || ! zoomOut || ! zoomReset) {
            return;
        }

        let zoom = Number(localStorage.getItem(storageKey) || 100);

        const applyZoom = () => {
            zoom = Math.min(maxZoom, Math.max(minZoom, zoom));
            const scale = zoom / 100;

            page.style.transform = `scale(${scale})`;
            page.style.transformOrigin = 'top center';
            page.style.marginBottom = `${Math.max(0, page.offsetHeight * (scale - 1))}px`;
            value.textContent = `${zoom}%`;
            document.body.classList.toggle('cv-zoomed', zoom !== 100);
            localStorage.setItem(storageKey, String(zoom));
        };

        zoomIn.addEventListener('click', () => {
            zoom += step;
            applyZoom();
        });

        zoomOut.addEventListener('click', () => {
            zoom -= step;
            applyZoom();
        });

        zoomReset.addEventListener('click', () => {
            zoom = 100;
            applyZoom();
        });

        applyZoom();
    })();
</script>
