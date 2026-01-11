<div>
    @includeIf(data_get($setUp, 'header.includeViewOnTop'))

    @if(data_get($setUp, 'header.title') || data_get($setUp, 'header.searchInput') || data_get($setUp, 'exportable') || data_get($setUp, 'header.toggleColumns'))
        <div class="card-header">
            @if(data_get($setUp, 'header.title'))
                <h3 class="card-title">{{ data_get($setUp, 'header.title') }}</h3>
            @endif
        </div>

        <div class="card-body border-bottom py-3">
            <div class="d-flex">
                <div class="text-secondary">
                    @if (filled(data_get($setUp, 'footer.perPage')) && count(data_get($setUp, 'footer.perPageValues')) > 1)
                        <span class="me-2">
                            {{ trans('polirium-datatable::datatable.pagination.show') }}
                        </span>
                        <div class="mx-2 d-inline-block">
                            <select
                                wire:model.live="setUp.footer.perPage"
                                class="form-select form-select-sm {{ theme_style($theme, 'footer.select') }}"
                                style="width: auto;"
                            >
                                @foreach (data_get($setUp, 'footer.perPageValues') as $value)
                                    <option value="{{ $value }}">
                                        @if ($value == 0)
                                            {{ trans('polirium-datatable::datatable.labels.all') }}
                                        @else
                                            {{ $value }}
                                        @endif
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <span class="ms-2">
                            {{ trans('polirium-datatable::datatable.pagination.entries') }}
                        </span>
                    @endif

                    <div x-data="pgRenderActions" class="d-inline-flex ms-3">
                        <span class="{{ theme_style($theme, 'table.layout.actions') }}" x-html="toHtml"></span>
                    </div>

                    @includeWhen(data_get($setUp, 'exportable'), data_get($theme, 'root') . '.header.export')
                    @include(data_get($theme, 'root') . '.header.toggle-columns')
                    @includeIf(data_get($theme, 'root') . '.header.soft-deletes')
                </div>

                <div class="ms-auto text-secondary">
                    @if (config('polirium-datatable.filter') === 'outside')
                        @include(data_get($theme, 'root') . '.header.filters')
                    @endif

                    @include(data_get($theme, 'root') . '.header.search')
                    @includeWhen(boolval(data_get($setUp, 'header.wireLoading')), data_get($theme, 'root') . '.header.loading')
                </div>
            </div>
        </div>
    @endif

    @includeWhen(data_get($setUp, 'exportable.batchExport.queues', 0), data_get($theme, 'root') . '.header.batch-exporting')
    @includeIf(data_get($theme, 'root') . '.header.enabled-filters')
    @includeWhen($multiSort, data_get($theme, 'root') . '.header.multi-sort')
    @includeIf(data_get($setUp, 'header.includeViewOnBottom'))
    @includeIf(data_get($theme, 'root') . '.header.message-soft-deletes')
</div>
