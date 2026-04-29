/**
 * pgMobileCard - Alpine.js component for mobile card view management
 * Handles responsive view switching between table and card layouts
 */

const LOCAL_STORAGE_KEY = 'pg-mobile-view-mode';

export default () => ({
    isMobile: false,
    viewMode: 'auto', // 'auto', 'table', 'card'
    mobileBreakpoint: 768,

    init() {
        // Load saved view mode from localStorage
        const savedMode = localStorage.getItem(LOCAL_STORAGE_KEY);
        if (savedMode && ['auto', 'table', 'card'].includes(savedMode)) {
            this.viewMode = savedMode;
        }

        // Initial check
        this.$nextTick(() => {
            this.checkMobile();
        });

        // Listen for resize events
        window.addEventListener('resize', () => {
            this.checkMobile();
        });

        // Listen for Livewire updates
        window.addEventListener('pg-livewire-request-finished', () => {
            this.$nextTick(() => {
                this.checkMobile();
            });
        });
    },

    checkMobile() {
        const wasMobile = this.isMobile;
        this.isMobile = window.innerWidth < this.mobileBreakpoint;

        // Reset to auto if switching between mobile/desktop
        if (wasMobile !== this.isMobile && this.viewMode !== 'auto') {
            this.viewMode = 'auto';
            this.saveViewMode();
        }
    },

    showTableView() {
        return !this.isMobile || this.viewMode === 'table';
    },

    showCardView() {
        return this.isMobile && (this.viewMode === 'auto' || this.viewMode === 'card');
    },

    setViewMode(mode) {
        if (['auto', 'table', 'card'].includes(mode)) {
            this.viewMode = mode;
            this.saveViewMode();
        }
    },

    toggleViewMode() {
        if (this.viewMode === 'auto') {
            this.viewMode = 'table';
        } else if (this.viewMode === 'table') {
            this.viewMode = 'card';
        } else {
            this.viewMode = 'auto';
        }
        this.saveViewMode();
    },

    saveViewMode() {
        localStorage.setItem(LOCAL_STORAGE_KEY, this.viewMode);
    },

    isViewModeActive(mode) {
        return this.viewMode === mode;
    }
});
