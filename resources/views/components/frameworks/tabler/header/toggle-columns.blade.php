@if (data_get($setUp, 'header.toggleColumns'))
<div class="dropdown pg-toggle-columns-dropdown">
    <button
        data-cy="toggle-columns-{{ $tableName }}"
        class="pg-btn pg-btn-outline"
        type="button"
        data-bs-toggle="dropdown"
        aria-expanded="false"
        title="@lang('polirium-datatable::datatable.labels.columns')"
    >
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M9 3H5a2 2 0 0 0-2 2v4m6-6h10a2 2 0 0 1 2 2v4M9 3v18m0 0h10a2 2 0 0 0 2-2v-4M9 21H5a2 2 0 0 1-2-2v-4"/>
        </svg>
    </button>
    <div class="dropdown-menu dropdown-menu-end pg-columns-menu" x-data="columnToggle('{{ $tableName }}')">
        {{-- Header --}}
        <div class="pg-columns-header">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M9 3H5a2 2 0 0 0-2 2v4m6-6h10a2 2 0 0 1 2 2v4M9 3v18m0 0h10a2 2 0 0 0 2-2v-4M9 21H5a2 2 0 0 1-2-2v-4"/>
            </svg>
            <span>@lang('polirium-datatable::datatable.labels.columns')</span>
        </div>

        {{-- Column List --}}
        <div class="pg-columns-list">
            @foreach ($this->visibleColumns as $column)
                <div
                    wire:key="toggle-column-{{ data_get($column, 'isAction') ? 'actions' : data_get($column, 'field') }}"
                    data-cy="toggle-field-{{ data_get($column, 'isAction') ? 'actions' : data_get($column, 'field') }}"
                    class="pg-column-item"
                    @click="toggleColumn('{{ data_get($column, 'field') }}', $el)"
                >
                    <span class="pg-column-name">{!! data_get($column, 'title') !!}</span>
                    <button
                        type="button"
                        class="pg-switch"
                        x-bind:class="isColumnVisible('{{ data_get($column, 'field') }}') ? 'active' : ''"
                        role="switch"
                        x-bind:aria-checked="isColumnVisible('{{ data_get($column, 'field') }}')"
                    >
                        <span class="pg-switch-thumb"></span>
                    </button>
                </div>
            @endforeach
        </div>

        {{-- Footer hint --}}
        <div class="pg-columns-footer">
            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="12" cy="12" r="10"/>
                <path d="M12 16v-4"/>
                <path d="M12 8h.01"/>
            </svg>
            <span>@lang('polirium-datatable::datatable.labels.toggle_hint', ['default' => 'Click to toggle visibility'])</span>
        </div>
    </div>
</div>
@endif

@once
@push('scripts')
<script>
function columnToggle(tableName) {
    return {
        tableName: tableName,
        storageKey: 'pg-columns-' + tableName,
        hiddenColumns: [],
        initialized: false,

        init() {
            const stored = localStorage.getItem(this.storageKey);
            if (stored) {
                this.hiddenColumns = JSON.parse(stored);
                if (this.hiddenColumns.length > 0 && !this.initialized) {
                    this.initialized = true;
                    this.$wire.call('restoreHiddenColumns', this.hiddenColumns);
                }
            }

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
            this.$wire.call('toggleColumn', field).then(() => {
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
@endpush
@endonce
