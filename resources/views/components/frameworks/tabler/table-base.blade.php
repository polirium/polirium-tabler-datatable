@php
    $columns = collect($columns)->map(function ($column) {
        return data_forget($column, 'rawQueries');
    });

    $enableMobileCard = data_get($setUp, 'responsive.mobileCardView', true);
@endphp
<div @if ($deferLoading) wire:init="fetchDatasource" @endif>
    <div class="{{ theme_style($theme, 'table.layout.base') }}" x-data="pgMobileCard()">
        @include(theme_style($theme, 'layout.header'), [
            'enabledFilters' => $enabledFilters,
        ])

        @if (config('polirium-datatable.filter') === 'outside')
            @php
                $filtersFromColumns = $columns
                    ->filter(fn($column) => filled(data_get($column, 'filters')));
            @endphp

            @if ($filtersFromColumns->count() > 0)
                <x-polirium-datatable::frameworks.tabler.filter
                    :enabled-filters="$enabledFilters"
                    :tableName="$tableName"
                    :columns="$columns"
                    :filtersFromColumns="$filtersFromColumns"
                    :theme="$theme"
                />
            @endif
        @endif

        {{-- Mobile View Toggle Button --}}
        @if($enableMobileCard)
            <div class="datatable-view-toggle datatable-hide-on-desktop">
                <button
                    class="view-btn"
                    :class="{ 'active': viewMode === 'auto' }"
                    @click="setViewMode('auto')"
                    title="Cards view">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <rect x="3" y="3" width="7" height="7"></rect>
                        <rect x="14" y="3" width="7" height="7"></rect>
                        <rect x="14" y="14" width="7" height="7"></rect>
                        <rect x="3" y="14" width="7" height="7"></rect>
                    </svg>
                    <span>Cards</span>
                </button>
                <button
                    class="view-btn"
                    :class="{ 'active': viewMode === 'table' }"
                    @click="setViewMode('table')"
                    title="Table view">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M9 3H5a2 2 0 0 0-2 2v4m6-6h10a2 2 0 0 1 2 2v4M9 3v18m0 0h10a2 2 0 0 0 2-2v-4M9 21H5a2 2 0 0 1-2-2v-4"></path>
                        <line x1="9" y1="9" x2="21" y2="9"></line>
                        <line x1="9" y1="13" x2="21" y2="13"></line>
                        <line x1="9" y1="17" x2="21" y2="17"></line>
                    </svg>
                    <span>Table</span>
                </button>
            </div>
        @endif

        <div class="{{ theme_style($theme, 'table.layout.div') }}">
            @include($table)
        </div>

        <div class="{{ theme_style($theme, 'footer.footer') }}">
            @include(theme_style($theme, 'footer.view'))
        </div>
    </div>
</div>
