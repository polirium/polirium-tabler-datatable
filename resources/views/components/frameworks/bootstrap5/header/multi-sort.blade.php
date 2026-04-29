@if ($multiSort && count($sortArray) > 0)
    <div
        class="col-md-12 d-flex table-responsive"
        style="margin-top: 10px"
    >
        <span>@lang('polirium-datatable::datatable.multi_sort.message')</span>
        <span class="d-flex gap-2">
            @foreach ($sortArray as $field => $sort)
                @php
                    $label = $this->getLabelFromColumn($field);
                @endphp
                <div
                    wire:key="{{ $tableName }}-multi-sort-{{ $field }}"
                    wire:click.prevent="sortBy('{{ $field }}')"
                    title="{{ __(':label :sort', ['label' => $label, 'sort' => $sort]) }}"
                    style="cursor: pointer; padding-right: 4px"
                >
                    <span class="badge rounded-pill bg-light text-dark">{{ $label }}
                        @if ($sort == 'desc')
                            <x-polirium-datatable::icons.chevron-down
                                width="14"
                                class="ms-1"
                            />
                        @else
                            <x-polirium-datatable::icons.chevron-up
                                width="14"
                                class="ms-1"
                            />
                        @endif
                    </span>
                </div>
            @endforeach
        </span>
    </div>
@endif
