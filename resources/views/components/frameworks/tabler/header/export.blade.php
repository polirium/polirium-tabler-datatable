<div class="dropdown pg-export-dropdown">
    <button
        class="pg-btn pg-btn-outline"
        type="button"
        data-bs-toggle="dropdown"
        aria-expanded="false"
    >
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/>
            <polyline points="7 10 12 15 17 10"/>
            <line x1="12" y1="15" x2="12" y2="3"/>
        </svg>
    </button>
    <ul x-data="{countChecked: @entangle('checkboxValues').live}" class="dropdown-menu dropdown-menu-end pg-dropdown-menu">
        <li class="pg-dropdown-header">
            <span>@lang('polirium-datatable::datatable.labels.export')</span>
        </li>
        @if (in_array('xlsx', data_get($setUp, 'exportable.type')))
            <li>
                <a class="pg-dropdown-item" wire:click.prevent="exportToXLS" href="#">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                        <polyline points="14 2 14 8 20 8"/>
                        <line x1="16" y1="13" x2="8" y2="13"/>
                        <line x1="16" y1="17" x2="8" y2="17"/>
                        <polyline points="10 9 9 9 8 9"/>
                    </svg>
                    <span>Excel (.xlsx)</span>
                    <span class="pg-badge">{{ $this->total }}</span>
                </a>
            </li>
            @if ($checkbox)
                <li>
                    <a class="pg-dropdown-item" wire:click.prevent="exportToXLS(true)" href="#" x-bind:class="countChecked.length === 0 ? 'disabled' : ''">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M9 11l3 3L22 4"/>
                            <path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"/>
                        </svg>
                        <span>@lang('polirium-datatable::datatable.labels.selected')</span>
                        <span class="pg-badge" x-text="countChecked.length"></span>
                    </a>
                </li>
            @endif
        @endif
        @if (in_array('csv', data_get($setUp, 'exportable.type')))
            <li class="pg-dropdown-divider"></li>
            <li>
                <a class="pg-dropdown-item" wire:click.prevent="exportToCsv" href="#">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                        <polyline points="14 2 14 8 20 8"/>
                        <line x1="16" y1="13" x2="8" y2="13"/>
                        <line x1="16" y1="17" x2="8" y2="17"/>
                    </svg>
                    <span>CSV (.csv)</span>
                    <span class="pg-badge">{{ $this->total }}</span>
                </a>
            </li>
            @if ($checkbox)
                <li>
                    <a class="pg-dropdown-item" wire:click.prevent="exportToCsv(true)" href="#" x-bind:class="countChecked.length === 0 ? 'disabled' : ''">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M9 11l3 3L22 4"/>
                            <path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"/>
                        </svg>
                        <span>@lang('polirium-datatable::datatable.labels.selected')</span>
                        <span class="pg-badge" x-text="countChecked.length"></span>
                    </a>
                </li>
            @endif
        @endif
    </ul>
</div>
