<div id="unsaved-changes-modal" class="hidden fixed inset-0 bg-black/50 items-center justify-center z-50">
    <div class="bg-white rounded-lg p-6 max-w-sm w-full">
        <p class="mb-4">You have unsaved changes. Are you sure you want to leave?</p>
        <div class="flex justify-end gap-2">
            <button id="stay-on-page-btn" class="px-4 py-2 border rounded bg-amber-500 text-white hover:translate-y-1 hover:shadow-xl">Stay on Page</button>
            <button id="leave-page-btn" class="px-4 py-2 bg-red-600 text-white rounded hover:translate-y-1 hover:shadow-xl">Leave</button>
        </div>
    </div>
</div>

<script>
(function () {
    const forms = document.querySelectorAll('form[data-confirm-leave]');
    if (forms.length === 0) return;

    const modal = document.getElementById('unsaved-changes-modal');
    const stayOnPageBtn = document.getElementById('stay-on-page-btn');
    const leavePageBtn = document.getElementById('leave-page-btn');
    let isDirty = false;
    let pendingNavigation = null;
    let bypassGuard = false;

    function markDirty() { isDirty = true; }
    function resetDirty() { isDirty = false; }

    function showModal(targetUrl = null) {
        pendingNavigation = targetUrl;
        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }

    function hideModal() {
        pendingNavigation = null;
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }

    forms.forEach(form => {
        form.addEventListener('input', markDirty, true);
        form.addEventListener('change', markDirty, true);
        form.addEventListener('submit', () => { bypassGuard = true; resetDirty(); });
    });

    document.addEventListener('click', function (event) {
        const link = event.target.closest('a');
        if (!link || !isDirty) return;

        const href = link.getAttribute('href');
        if (!href || href.startsWith('#') || href.startsWith('mailto:') || href.startsWith('tel:') || href.startsWith('javascript:')) {
            return;
        }

        const targetUrl = new URL(href, window.location.href);
        const isSamePage = targetUrl.origin === window.location.origin
            && targetUrl.pathname === window.location.pathname
            && targetUrl.search === window.location.search;

        if (link.target === '_blank' || isSamePage) return;

        event.preventDefault();
        showModal(targetUrl.href);
    }, true);

    stayOnPageBtn?.addEventListener('click', hideModal);

    leavePageBtn?.addEventListener('click', function () {
        const targetUrl = pendingNavigation;
        hideModal();
        bypassGuard = true;
        resetDirty();

        if (targetUrl) {
            window.location.assign(targetUrl);
        } else {
            history.go(-2);
        }
    });

    modal?.addEventListener('click', function (event) {
        if (event.target === modal) hideModal();
    });

    window.addEventListener('beforeunload', function (event) {
        if (isDirty && !bypassGuard) {
            event.preventDefault();
            event.returnValue = '';
        }
    });

    history.pushState(null, '', window.location.href);

    window.addEventListener('popstate', function () {
        if (bypassGuard) return;
        if (isDirty) {
            history.pushState(null, '', window.location.href);
            showModal(null);
        }
    });

    window.addEventListener('pageshow', function (event) {
        if (event.persisted) {
            history.pushState(null, '', window.location.href);
        }
    });
})();
</script>
