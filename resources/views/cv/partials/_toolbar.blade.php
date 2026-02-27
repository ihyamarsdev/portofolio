<style>
    .cv-toolbar {
        position: fixed;
        bottom: 30px;
        right: 30px;
        display: flex;
        gap: 12px;
        z-index: 1000;
    }

    .cv-btn {
        background: #0f172a;
        color: #fff;
        border: 1px solid #334155;
        padding: 10px 20px;
        border-radius: 8px;
        font-family: system-ui, -apple-system, sans-serif;
        font-size: 14px;
        font-weight: 500;
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: 8px;
        text-decoration: none;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        transition: all 0.2s;
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

    @media print {
        .cv-toolbar {
            display: none !important;
        }
    }
</style>

<div class="cv-toolbar no-print">
    <button onclick="window.print()" class="cv-btn">
        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" width="18" height="18">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2-2v4h10z">
            </path>
        </svg>
        Print
    </button>
    <a href="{{ route('cv.download', ['template' => request('template', 'modern')]) }}" class="cv-btn cv-btn-primary">
        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" width="18" height="18">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
        </svg>
        Download PDF
    </a>
</div>