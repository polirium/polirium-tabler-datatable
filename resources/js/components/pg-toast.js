/**
 * PowerGrid Toast Notification System
 * Listens for Livewire success/error/warning/info events and shows Tabler-styled toasts.
 */
export default function pgToast() {
    let container = document.getElementById('pg-toast-container');
    if (!container) {
        container = document.createElement('div');
        container.id = 'pg-toast-container';
        container.className = 'pg-toast-container';
        document.body.appendChild(container);
    }

    const icons = {
        success: '<svg xmlns="http://www.w3.org/2000/svg" class="pg-toast-icon" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12l5 5l10 -10"/></svg>',
        error: '<svg xmlns="http://www.w3.org/2000/svg" class="pg-toast-icon" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 9v4"/><path d="M10.363 3.591l-8.106 13.534a1.914 1.914 0 0 0 1.636 2.871h16.214a1.914 1.914 0 0 0 1.636 -2.87l-8.106 -13.536a1.914 1.914 0 0 0 -3.274 0z"/><path d="M12 16h.01"/></svg>',
        warning: '<svg xmlns="http://www.w3.org/2000/svg" class="pg-toast-icon" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 12m-9 0a9 9 0 1 0 18 0a9 9 0 1 0 -18 0"/><path d="M12 8v4"/><path d="M12 16h.01"/></svg>',
        info: '<svg xmlns="http://www.w3.org/2000/svg" class="pg-toast-icon" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 12m-9 0a9 9 0 1 0 18 0a9 9 0 1 0 -18 0"/><path d="M12 8l.01 0"/><path d="M11 12l1 0l0 4l1 0"/></svg>',
    };

    function show(message, type = 'info', duration = 4000) {
        const toast = document.createElement('div');
        toast.className = `pg-toast pg-toast-${type}`;
        toast.innerHTML = `
            ${icons[type] || icons.info}
            <span class="pg-toast-message">${message}</span>
            <button class="pg-toast-close" onclick="this.parentElement.remove()">&times;</button>
        `;
        container.appendChild(toast);

        // Trigger animation
        requestAnimationFrame(() => toast.classList.add('pg-toast-show'));

        // Auto dismiss
        setTimeout(() => {
            toast.classList.remove('pg-toast-show');
            toast.classList.add('pg-toast-hide');
            setTimeout(() => toast.remove(), 300);
        }, duration);
    }

    // Register Livewire listeners
    ['success', 'error', 'warning', 'info'].forEach(type => {
        Livewire.on(type, (event) => {
            const message = Array.isArray(event) ? event[0] : event;
            show(message, type);
        });
    });

    // Expose globally for manual use
    window.pgToast = { show };
}
