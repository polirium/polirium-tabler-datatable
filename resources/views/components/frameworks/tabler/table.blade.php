@php
    use Polirium\Datatable\DataSource\DataTransformer;
    use Polirium\Datatable\PowerGridComponent;

    $dataTransformer = new DataTransformer($this);
    $tableIsLazy = !is_null(data_get($setUp, 'lazy'));
    $lazyConfig = data_get($setUp, 'lazy');
    $rowsPerChildren = data_get($lazyConfig, 'rowsPerChildren');
    $enableMobileCard = data_get($setUp, 'responsive.mobileCardView', true);

    /** @var PowerGridComponent $this */

@endphp
<x-polirium-datatable::table-base
    :$readyToLoad
    :$tableName
    :$theme
    :lazy="$tableIsLazy"
>
    <x-slot:header>
        @include('polirium-datatable::components.table.tr')
    </x-slot:header>

    <x-slot:loading>
        @include('polirium-datatable::components.table.tr', ['loading' => true])
    </x-slot:loading>

    <x-slot:body>
        @includeWhen($this->hasColumnFilters, 'polirium-datatable::components.inline-filters')

        @if (count($this->records) === 0)
            @include('polirium-datatable::components.table.th-empty')
        @else
            @includeWhen($headerTotalColumn, 'polirium-datatable::components.table-header')

            {{-- Table View - Hide on mobile when card mode is active --}}
            @if($enableMobileCard)
                <div x-data="pgMobileCard()">
            @endif
            <div x-show="showTableView()" class="datatable-table-view d-none d-md-block">
            @if (empty($lazyConfig))


                @if (isset($setUp['detail']))
                    @foreach ($this->records as $row)
                        @php
                            $rowId = data_get($row, $this->realPrimaryKey);
                            $class = theme_style($theme, 'table.body.tr');
                        @endphp

                        <tbody
                            wire:key="tbody-{{ substr($rowId, 0, 6) }}"
                            class="{{ $class }}"
                        >
                            <tr
                                x-data="() => {
                                    return {
                                        collapsed: false,
                                        loading: false,
                                        collapseOthers: @js(data_get($setUp, 'detail.collapseOthers', false)),
                                        toggleDetail() {
                                            const isOpen = this.collapsed;

                                            if (this.collapseOthers) {
                                                this.$dispatch('pg-toggle-detail-{{ $tableName }}-hidden-all');
                                                expandedId = '{{ $rowId }}';
                                            }

                                            this.loading = true;
                                            this.collapsed = !isOpen;

                                            this.$dispatch('pg-toggle-detail-{{ $tableName }}-{{ $rowId }}', {
                                                collapsed: this.collapsed
                                            });
                                        }
                                    }
                                }"
                                x-on:pg-toggle-detail-{{ $tableName }}-hidden-all.window="collapsed = false"
                                x-on:pg-toggle-detail-{{ $tableName }}-loaded.window="loading = false;"
                                x-on:click="if ($event.target.tagName !== 'BUTTON' && $event.target.tagName !== 'A' && !$event.target.closest('button') && !$event.target.closest('a') && !$event.target.closest('input') && !$event.target.closest('select') && !$event.target.closest('.dropdown') && !$event.target.closest('.form-check')) { toggleDetail(); }"
                                class="cursor-pointer"
                                style="cursor: pointer;"
                            >
                                @include(data_get($theme, 'root') . '.row', [
                                    'rowIndex' => $loop->index + 1,
                                ])
                            </tr>

                            @php
                                $hasDetailView = (bool) data_get(
                                    collect($row->__powergrid_rules)->where('apply', true)->last(),
                                    'detailView',
                                );

                                if ($hasDetailView) {
                                    $detailView = data_get($row->__powergrid_rules, '0.detailView');
                                    $rulesValues = data_get($row->__powergrid_rules, '0.options', []);
                                } else {
                                    $detailView = data_get($setUp, 'detail.view');
                                    $rulesValues = data_get($setUp, 'detail.options', []);
                                }
                            @endphp

                            @php
                                if ($row instanceof stdClass) {
                                    $row = collect($row);
                                }
                            @endphp

                            <livewire:powergrid-detail
                                key="powergrid-detail-{{ $rowId }}"
                                :view="$detailView"
                                :options="$rulesValues"
                                :row-id="$rowId"
                                tr-class="{{ $class }}"
                                :row="(object) $row->toArray()"
                                :collapse-others="data_get($setUp, 'detail.collapseOthers', false)"
                                :table-name="$tableName"
                            />
                        </tbody>

                        @includeWhen(isset($setUp['responsive']),
                            'polirium-datatable::components.expand-container')
                    @endforeach
                @else
                    @foreach ($this->records as $row)
                        @php
                            $rowId = data_get($row, $this->realPrimaryKey);
                            $class = theme_style($theme, 'table.body.tr');
                        @endphp

                        <tr
                            wire:replace.self
                            class="{{ $class }}"
                            x-data="pgRowAttributes({ rowId: @js($rowId), rules: @js($row->__powergrid_rules) })"
                            x-bind="getAttributes"
                        >
                            @include(data_get($theme, 'root') . '.row', [
                                'rowIndex' => $loop->index + 1,
                            ])
                        </tr>

                        @includeWhen(isset($setUp['responsive']),
                            'polirium-datatable::components.expand-container')
                    @endforeach
                @endif
            @else
                <div>
                    @foreach (range(0, data_get($lazyConfig, 'items')) as $item)
                        @php
                            $skip = $item * $rowsPerChildren;
                            $take = $rowsPerChildren;
                        @endphp

                        <livewire:lazy-child
                            key="{{ $this->getLazyKeys }}"
                            :parentId="$this->getId()"
                            :child-index="$item"
                            :primary-key="$primaryKey"
                            real-primary-key="{{ $this->realPrimaryKey }}"
                            :$radio
                            :$radioAttribute
                            :$checkbox
                            :$checkboxAttribute
                            :theme="$theme"
                            :$setUp
                            :$tableName
                            :parentName="$this->getName()"
                            :columns="$this->visibleColumns"
                            :data="$dataTransformer->transform($data->skip($skip)->take($take))->collection"
                        />
                    @endforeach
                </div>
            @endif
            </div>

            {{-- Mobile Card View - Show on mobile when card mode is active --}}
            @if($enableMobileCard)
                <div class="datatable-mobile-wrapper d-md-none" x-show="showCardView()">
                    @foreach ($this->records as $row)
                        @php
                            $rowId = data_get($row, $this->realPrimaryKey);
                        @endphp
                        @include('polirium-datatable::components.frameworks.tabler.mobile-card', [
                            'row' => $row,
                            'rowId' => $rowId,
                            'columns' => $this->visibleColumns->toArray(),
                            'primaryKey' => $this->realPrimaryKey,
                        ])
                    @endforeach
                </div>
                </div>{{-- Close x-data="pgMobileCard()" --}}
            @endif

            @includeWhen($footerTotalColumn, 'polirium-datatable::components.table-footer')
        @endif
    </x-slot:body>
</x-polirium-datatable::table-base>
