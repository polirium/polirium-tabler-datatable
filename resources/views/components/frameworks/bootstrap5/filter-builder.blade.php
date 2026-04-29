@props([
    'tableName' => '',
    'theme' => [],
])

@php
    $fields = $filterBuilderFields ?? [];
    $group = $filterBuilderGroup;
    $activeCount = isset($filterBuilderGroup) ? count(collect($filterBuilderGroup->conditions)->filter(fn($c) => $c->isValid())) : 0;
@endphp

@if($showFilterBuilder ?? false)
<div class="offcanvas offcanvas-end show"
     tabindex="-1"
     id="filter-builder-{{ $tableName }}"
     style="visibility: visible;">

    {{-- Header --}}
    <div class="offcanvas-header border-bottom">
        <h5 class="offcanvas-title">
            <div class="d-flex align-items-center gap-2">
                {!! tabler_icon('filter', ['class' => 'icon text-primary']) !!}
                <span>{{ __('polirium-datatable::datatable.filter_builder.title') }}</span>
                @if($activeCount > 0)
                    <span class="badge bg-primary">{{ $activeCount }}</span>
                @endif
            </div>
        </h5>
        <button type="button"
                class="btn-close"
                wire:click="toggleFilterBuilder"
                aria-label="{{ __('polirium-datatable::datatable.filter_builder.close') }}">
        </button>
    </div>

    {{-- Body --}}
    <div class="offcanvas-body p-3">
        {{-- Logic Toggle --}}
        <div class="d-flex align-items-center justify-content-between mb-3 p-2 bg-light rounded">
            <span class="small text-muted">{{ __('polirium-datatable::datatable.filter_builder.match') }}</span>

            <div class="btn-group btn-group-sm">
                <button type="button"
                        class="btn {{ data_get($group, 'logic') === 'AND' ? 'btn-primary' : 'btn-outline-secondary' }}"
                        wire:click="toggleFilterBuilderLogic">
                    AND
                </button>
                <button type="button"
                        class="btn {{ data_get($group, 'logic') === 'OR' ? 'btn-primary' : 'btn-outline-secondary' }}"
                        wire:click="toggleFilterBuilderLogic">
                    OR
                </button>
            </div>
        </div>

        {{-- Conditions List --}}
        <div class="d-flex flex-column gap-2">
            @foreach(data_get($group, 'conditions', []) as $index => $condition)
                @include('polirium-datatable::components.frameworks.bootstrap5.filter-builder-condition', [
                    'condition' => $condition,
                    'index' => $index,
                    'fields' => $fields,
                ])
            @endforeach
        </div>

        @if(empty(data_get($group, 'conditions')))
            <div class="text-center text-muted py-5">
                {!! tabler_icon('filter-off', ['class' => 'icon icon-lg mb-2']) !!}
                <p class="small">{{ __('polirium-datatable::datatable.filter_builder.no_conditions') }}</p>
            </div>
        @endif
    </div>

    {{-- Footer --}}
    <div class="offcanvas-footer border-top p-3">
        <div class="d-flex gap-2">
            <button type="button"
                    class="btn btn-primary flex-fill"
                    wire:click="addFilterBuilderCondition">
                {!! tabler_icon('plus', ['class' => 'icon icon-sm']) !!}
                {{ __('polirium-datatable::datatable.filter_builder.add_condition') }}
            </button>

            @if($activeCount > 0)
                <button type="button"
                        class="btn btn-danger btn-outline"
                        wire:click="clearFilterBuilder">
                    {!! tabler_icon('trash', ['class' => 'icon icon-sm']) !!}
                    {{ __('polirium-datatable::datatable.filter_builder.clear_all') }}
                </button>
            @endif
        </div>
    </div>
</div>

{{-- Backdrop --}}
<div class="offcanvas-backdrop fade show"
     wire:click="toggleFilterBuilder"
     style="display: block;"></div>
@endif
