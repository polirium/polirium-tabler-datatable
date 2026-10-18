@if (data_get($setUp, 'header.searchInput'))
    <div class="input-icon pg-search">
        <span class="input-icon-addon">{!! tabler_icon('search', ['class' => 'icon']) !!}</span>
        <input
            wire:model.live.debounce.300ms="search"
            type="search"
            class="form-control"
            placeholder="{{ trans('polirium-datatable::datatable.placeholders.search') }}"
            aria-label="{{ trans('polirium-datatable::datatable.placeholders.search') }}"
        >
    </div>
@endif
