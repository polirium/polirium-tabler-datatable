@php
    use Polirium\Datatable\Enums\FilterOperator;

    $conditionId = data_get($condition, 'id');
    $selectedField = data_get($condition, 'field');
    $selectedOperator = data_get($condition, 'operator');
    $valueType = data_get($condition, 'valueType', 'text');
    $value = data_get($condition, 'value');

    if ($selectedField) {
        $fieldConfig = collect($fields)->first(fn($f) => data_get($f, 'field') === $selectedField);
        $fieldType = data_get($fieldConfig, 'type', 'text');
        $availableOperators = FilterOperator::forType($fieldType);
    } else {
        $availableOperators = [];
        $fieldType = 'text';
    }
@endphp

<div class="card card-sm mb-2" wire:key="condition-{{ $conditionId }}">
    <div class="card-body p-2">
        <div class="d-flex gap-2 align-items-start">

            {{-- Field Selector --}}
            <div class="flex-fill">
                <select class="form-select form-select-sm"
                        wire:model.live="filterBuilderGroup.conditions.{{ $index }}.field">
                    <option value="">{{ __('polirium-datatable::datatable.filter_builder.select_field') }}</option>
                    @foreach($fields as $field)
                        <option value="{{ data_get($field, 'field') }}">
                            {{ data_get($field, 'label') }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Operator Selector --}}
            @if($selectedField)
                <div class="flex-fill">
                    <select class="form-select form-select-sm"
                            wire:model.live="filterBuilderGroup.conditions.{{ $index }}.operator">
                        @foreach($availableOperators as $operator)
                            <option value="{{ $operator->value }}">
                                {{ $operator->label() }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Value Input (based on field type) --}}
                <div class="flex-fill">
                    @if(in_array($selectedOperator, ['is_null', 'is_not_null', 'is_empty', 'is_not_empty']))
                        {{-- No value input for these operators --}}
                        <span class="form-control form-control-sm bg-light text-muted">
                            {{ __('polirium-datatable::datatable.filter_builder.no_value_needed') }}
                        </span>
                    @elseif($fieldType === 'boolean')
                        <select class="form-select form-select-sm"
                                wire:model.live="filterBuilderGroup.conditions.{{ $index }}.value">
                            <option value="1">{{ __('polirium-datatable::datatable.boolean_filter.true') }}</option>
                            <option value="0">{{ __('polirium-datatable::datatable.boolean_filter.false') }}</option>
                        </select>
                    @elseif($fieldType === 'date' || $fieldType === 'datetime')
                        <input type="date"
                               class="form-control form-control-sm"
                               wire:model.live="filterBuilderGroup.conditions.{{ $index }}.value" />
                    @else
                        <input type="text"
                               class="form-control form-control-sm"
                               wire:model.live="filterBuilderGroup.conditions.{{ $index }}.value"
                               placeholder="{{ __('polirium-datatable::datatable.filter_builder.enter_value') }}" />
                    @endif
                </div>
            @endif

            {{-- Remove Button --}}
            <button type="button"
                    class="btn btn-sm btn-outline-danger"
                    wire:click="removeFilterBuilderCondition('{{ $conditionId }}')">
                {!! tabler_icon('trash', ['class' => 'icon icon-sm']) !!}
            </button>

        </div>
    </div>
</div>
