<div class="dropdown">
    @if (data_get($setUp, 'header.toggleColumns'))
        <div class="btn-group">
            <button
                data-cy="toggle-columns-{{ $tableName }}"
                class="btn btn-sm btn-secondary dropdown-toggle"
                type="button"
                data-bs-toggle="dropdown"
                aria-expanded="false"
            >
                <x-polirium-datatable::icons.eye-off width="16" />
            </button>
            <ul class="dropdown-menu" x-data="columnToggle('{{ $tableName }}')">
                @foreach ($this->visibleColumns as $column)
                    <li
                        wire:key="toggle-column-{{ data_get($column, 'isAction') ? 'actions' : data_get($column, 'field') }}"
                        data-cy="toggle-field-{{ data_get($column, 'isAction') ? 'actions' : data_get($column, 'field') }}"
                    >
                        <a
                            class="dropdown-item d-flex align-items-center gap-2"
                            href="#"
                            @click.prevent="toggleColumn('{{ data_get($column, 'field') }}', $el)"
                        >
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" x-show="isColumnVisible('{{ data_get($column, 'field') }}')">
                                <path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"></path>
                                <circle cx="12" cy="12" r="3"></circle>
                            </svg>
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" x-show="!isColumnVisible('{{ data_get($column, 'field') }}')">
                                <path d="M9.88 9.88a3 3 0 1 0 4.24 4.24"></path>
                                <path d="M10.73 5.08A10.43 10.43 0 0 1 12 5c7 0 10 7 10 7a13.16 13.16 0 0 1-1.67 2.68"></path>
                                <path d="M6.61 6.61A13.526 13.526 0 0 0 2 12s3 7 10 7a13.5 13.5 0 0 0 5.88-1.92"></path>
                                <line x1="2" x2="22" y1="2" y2="22"></line>
                            </svg>
                            <span>{!! data_get($column, 'title') !!}</span>
                        </a>
                    </li>
                @endforeach
            </ul>
        </div>
    @endif
</div>

<script>
function columnToggle(tableName) {
    return {
        tableName: tableName,
        storageKey: 'pg-columns-' + tableName,
        hiddenColumns: [],
        initialized: false,

        init() {
            // Load from localStorage
            const stored = localStorage.getItem(this.storageKey);
            if (stored) {
                this.hiddenColumns = JSON.parse(stored);

                // Restore hidden columns to Livewire on page load
                if (this.hiddenColumns.length > 0 && !this.initialized) {
                    this.initialized = true;
                    this.$wire.call('restoreHiddenColumns', this.hiddenColumns);
                }
            }

            // Listen for Livewire updates
            this.$wire.$el.addEventListener('pg:column-toggled', (e) => {
                if (e.detail.tableName === this.tableName) {
                    this.hiddenColumns = e.detail.hiddenColumns;
                    this.saveToStorage();
                }
            });
        },

        isColumnVisible(field) {
            return !this.hiddenColumns.includes(field);
        },

        toggleColumn(field, element) {
            // Dispatch to Livewire
            this.$wire.call('toggleColumn', field).then(() => {
                // Update local state
                const index = this.hiddenColumns.indexOf(field);
                if (index > -1) {
                    this.hiddenColumns.splice(index, 1);
                } else {
                    this.hiddenColumns.push(field);
                }
                this.saveToStorage();
            });
        },

        saveToStorage() {
            localStorage.setItem(this.storageKey, JSON.stringify(this.hiddenColumns));
        }
    };
}
</script>
