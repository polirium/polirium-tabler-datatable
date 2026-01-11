@if (data_get($setUp, 'header.searchInput'))
    <div class="d-inline-block ms-2">
        <input
            wire:model.live.debounce.600ms="search"
            type="text"
            class="{{ theme_style($theme, 'searchBox.input') }}"
            placeholder="{{ trans('polirium-datatable::datatable.placeholders.search') }}"
            aria-label="Search"
        >
    </div>
@endif
