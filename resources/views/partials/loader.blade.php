<!-- Global Loader Overlay -->
<div id="global-app-loader" class="global-loader-overlay d-none">
    <div class="global-loader-card">
        <div class="global-spinner-wrapper">
            <div class="spinner-ring outer-ring"></div>
            <div class="spinner-ring inner-ring"></div>
            <div class="brand-icon-wrapper">
                <i class="fa-solid fa-arrows-rotate fa-spin text-primary" id="loader-brand-icon"></i>
            </div>
        </div>
        <div class="loader-text-wrapper">
            <h6 class="loader-title mb-1" id="global-loader-text">Processing Request...</h6>
            <p class="loader-subtitle mb-0 text-muted">Please wait a moment while we update your data.</p>
        </div>
    </div>
</div>

<style>
.global-loader-overlay {
    position: fixed;
    top: 0;
    left: 0;
    width: 100vw;
    height: 100vh;
    background: rgba(15, 23, 42, 0.65);
    backdrop-filter: blur(8px);
    -webkit-backdrop-filter: blur(8px);
    z-index: 999999;
    display: flex;
    align-items: center;
    justify-content: center;
    opacity: 0;
    transition: opacity 0.25s ease-in-out;
    pointer-events: auto;
}

.global-loader-overlay.active {
    opacity: 1;
}

.global-loader-card {
    background: rgba(255, 255, 255, 0.96);
    border: 1px solid rgba(255, 255, 255, 0.4);
    box-shadow: 0 20px 45px rgba(0, 0, 0, 0.25), 0 0 0 1px rgba(76, 117, 161, 0.1);
    border-radius: 18px;
    padding: 2.25rem 2.5rem;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    text-align: center;
    max-width: 380px;
    width: 90%;
    transform: scale(0.92);
    transition: transform 0.25s cubic-bezier(0.34, 1.56, 0.64, 1);
}

.global-loader-overlay.active .global-loader-card {
    transform: scale(1);
}

.global-spinner-wrapper {
    position: relative;
    width: 76px;
    height: 76px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-bottom: 1.25rem;
}

.spinner-ring {
    position: absolute;
    border-radius: 50%;
    border: 3px solid transparent;
}

.outer-ring {
    width: 76px;
    height: 76px;
    border-top-color: #4c75a1;
    border-right-color: #4c75a1;
    animation: loader-spin 1.1s linear infinite;
}

.inner-ring {
    width: 56px;
    height: 56px;
    border-bottom-color: #b0c6db;
    border-left-color: #1e293b;
    animation: loader-spin-reverse 0.85s linear infinite;
}

.brand-icon-wrapper {
    font-size: 1.35rem;
    color: #4c75a1;
    z-index: 2;
}

.loader-title {
    font-family: 'Outfit', 'Inter', system-ui, sans-serif;
    font-weight: 700;
    color: #0f172a;
    font-size: 1.05rem;
    letter-spacing: -0.01em;
}

.loader-subtitle {
    font-size: 0.8125rem;
    color: #64748b !important;
}

@keyframes loader-spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}

@keyframes loader-spin-reverse {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(-360deg); }
}
</style>

<script>
(function() {
    var loaderOverlay = null;
    var loaderText = null;
    var loaderIcon = null;
    var safetyTimeout = null;

    function initLoaderElements() {
        loaderOverlay = document.getElementById('global-app-loader');
        loaderText = document.getElementById('global-loader-text');
        loaderIcon = document.getElementById('loader-brand-icon');
    }

    window.showGlobalLoader = function(customText, customIconClass) {
        if (!loaderOverlay) initLoaderElements();
        if (!loaderOverlay) return;

        if (customText) {
            loaderText.innerText = customText;
        } else {
            loaderText.innerText = 'Processing Request...';
        }

        if (customIconClass && loaderIcon) {
            loaderIcon.className = customIconClass;
        } else if (loaderIcon) {
            loaderIcon.className = 'fa-solid fa-arrows-rotate fa-spin text-primary';
        }

        loaderOverlay.classList.remove('d-none');
        // Trigger opacity fade-in
        setTimeout(function() {
            loaderOverlay.classList.add('active');
        }, 10);

        // Safety fallback: Hide loader after 15 seconds to prevent permanent lockup if network hangs
        clearTimeout(safetyTimeout);
        safetyTimeout = setTimeout(function() {
            window.hideGlobalLoader();
        }, 15000);
    };

    window.hideGlobalLoader = function() {
        if (!loaderOverlay) initLoaderElements();
        if (!loaderOverlay) return;

        clearTimeout(safetyTimeout);
        loaderOverlay.classList.remove('active');
        setTimeout(function() {
            loaderOverlay.classList.add('d-none');
        }, 250);
    };

    document.addEventListener('DOMContentLoaded', function() {
        initLoaderElements();

        // 1. Intercept all Form Submissions
        document.addEventListener('submit', function(e) {
            var form = e.target;
            if (!form) return;

            // Check browser validation state
            if (typeof form.checkValidity === 'function' && !form.checkValidity()) {
                return; // Let browser show native validation tooltips
            }

            // Determine custom message based on action or submit button
            var submitter = e.submitter;
            var text = 'Processing Request...';
            var icon = 'fa-solid fa-arrows-rotate fa-spin text-primary';

            var formText = (form.innerText || '').toLowerCase();
            var actionUrl = (form.action || '').toLowerCase();
            var submitterText = submitter ? (submitter.innerText || submitter.value || '').toLowerCase() : '';

            if (submitterText.includes('email') || submitterText.includes('send') || actionUrl.includes('send') || actionUrl.includes('email') || actionUrl.includes('contact')) {
                text = 'Sending Email & Dispatching Notice...';
                icon = 'fa-solid fa-paper-plane fa-bounce text-primary';
            } else if (submitterText.includes('delete') || submitterText.includes('remove') || actionUrl.includes('destroy') || actionUrl.includes('delete') || actionUrl.includes('remove')) {
                text = 'Deleting Data...';
                icon = 'fa-solid fa-trash-can fa-spin text-danger';
            } else if (submitterText.includes('save') || submitterText.includes('update') || submitterText.includes('store') || submitterText.includes('create') || submitterText.includes('post')) {
                text = 'Saving Changes & Updating Record...';
                icon = 'fa-solid fa-floppy-disk fa-spin text-primary';
            } else if (submitterText.includes('filter') || submitterText.includes('search')) {
                text = 'Searching & Applying Filters...';
                icon = 'fa-solid fa-magnifying-glass fa-beat text-primary';
            } else if (submitterText.includes('upload') || formText.includes('file')) {
                text = 'Uploading File & Processing...';
                icon = 'fa-solid fa-cloud-arrow-up fa-bounce text-primary';
            }

            window.showGlobalLoader(text, icon);
        });

        // 2. Intercept elements with data-show-loader or class show-loader
        document.addEventListener('click', function(e) {
            var target = e.target.closest('[data-show-loader], .show-loader');
            if (target) {
                var msg = target.getAttribute('data-loader-text') || 'Loading, please wait...';
                window.showGlobalLoader(msg);
            }
        });

        // Hide loader if page is restored from back/forward cache (bfcache)
        window.addEventListener('pageshow', function(event) {
            if (event.persisted) {
                window.hideGlobalLoader();
            }
        });
    });
})();
</script>
