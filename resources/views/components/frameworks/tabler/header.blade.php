<div>
    @includeIf(data_get($setUp, 'header.includeViewOnTop'))

    @if(data_get($setUp, 'header.title'))
        <div class="card-header">
            <h3 class="card-title">{{ data_get($setUp, 'header.title') }}</h3>
        </div>
    @endif

    @if(data_get($setUp, 'header.searchInput') || data_get($setUp, 'exportable') || data_get($setUp, 'header.toggleColumns') || filled(data_get($setUp, 'footer.perPage')))
        {{-- Toolbar: search on the left; columns, export and page size on the right. --}}
        <div class="pg-toolbar">
            <div class="pg-toolbar-start">
                @include(data_get($theme, 'root') . '.header.search')

                @if($filterBuilderEnabled ?? false)
                    <button type="button"
                            class="btn btn-icon btn-ghost-secondary"
                            wire:click="toggleFilterBuilder"
                            title="{{ __('polirium-datatable::datatable.buttons.filter_builder') }}">
                        {!! tabler_icon('filter', ['class' => 'icon']) !!}
                        @if(isset($filterBuilderGroup) && count($filterBuilderGroup->conditions) > 0)
                            <span class="badge bg-primary">{{ count(collect($filterBuilderGroup->conditions)->filter(fn($c) => $c->isValid())) }}</span>
                        @endif
                    </button>
                @endif

                @if (config('polirium-datatable.filter') === 'outside')
                    @include(data_get($theme, 'root') . '.header.filters')
                @endif

                <div x-data="pgRenderActions">
                    <span class="{{ theme_style($theme, 'table.layout.actions') }}" x-html="toHtml"></span>
                </div>
            </div>

            <div class="pg-toolbar-end">
                @includeWhen(boolval(data_get($setUp, 'header.wireLoading')), data_get($theme, 'root') . '.header.loading')
                @include(data_get($theme, 'root') . '.header.toggle-columns')
                @includeWhen(data_get($setUp, 'exportable'), data_get($theme, 'root') . '.header.export')
                @includeIf(data_get($theme, 'root') . '.header.soft-deletes')

                @if (filled(data_get($setUp, 'footer.perPage')) && count(data_get($setUp, 'footer.perPageValues')) > 1)
                    <select
                        wire:model.live="setUp.footer.perPage"
                        class="form-select pg-per-page"
                        title="{{ trans('polirium-datatable::datatable.labels.results_per_page') }}"
                        aria-label="{{ trans('polirium-datatable::datatable.labels.results_per_page') }}"
                    >
                        @foreach (data_get($setUp, 'footer.perPageValues') as $value)
                            <option value="{{ $value }}">
                                {{ $value == 0 ? trans('polirium-datatable::datatable.pagination.all') : $value . ' / ' . trans('polirium-datatable::datatable.labels.page') }}
                            </option>
                        @endforeach
                    </select>
                @endif
            </div>
        </div>
    @endif

    @includeWhen(data_get($setUp, 'exportable.batchExport.queues', 0), data_get($theme, 'root') . '.header.batch-exporting')
    @includeIf(data_get($theme, 'root') . '.header.enabled-filters')
    @includeWhen($multiSort, data_get($theme, 'root') . '.header.multi-sort')
    @includeIf(data_get($setUp, 'header.includeViewOnBottom'))
    @includeIf(data_get($theme, 'root') . '.header.message-soft-deletes')
</div>
