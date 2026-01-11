@php
    $columns = collect($columns)->map(function ($column) {
        return data_forget($column, 'rawQueries');
    });
@endphp
<div @if ($deferLoading) wire:init="fetchDatasource" @endif>
    <div class="{{ theme_style($theme, 'table.layout.base') }}">
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

        <div class="{{ theme_style($theme, 'table.layout.div') }}">
            @include($table)
        </div>

        <div class="{{ theme_style($theme, 'footer.footer') }}">
            @include(theme_style($theme, 'footer.view'))
        </div>
    </div>
</div>
