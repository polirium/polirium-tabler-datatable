@props([
    'rowIndex' => 0,
    'childIndex' => null,
    'parentId' => null,
])

@includeWhen(isset($setUp['responsive']), data_get($theme, 'root') . '.toggle-detail-responsive', [
    'view' => data_get($setUp, 'detail.viewIcon') ?? null,
])

@php
    $defaultCollapseIcon = data_get($theme, 'root') . '.toggle-detail';
    $hasDetail = isset($setUp['detail']);
@endphp

{{-- Detail icon column for Tabler theme - shows + icon --}}
@if($hasDetail)
    <td class="w-1 text-center">
        <div
            x-data="() => {
                return {
                    collapsed: false,
                    collapseOthers: @js(data_get($setUp, 'detail.collapseOthers', false)),
                    init() {
                        this.$watch('collapsed', value => {
                            const icon = this.$el.querySelector('.detail-toggle-icon');
                            if (icon) {
                                icon.style.transform = value ? 'rotate(45deg)' : 'rotate(0deg)';
                            }
                        });
                    }
                }
            }"
            x-on:pg-toggle-detail-{{ $tableName }}-hidden-all.window="collapsed = false"
            x-on:pg-toggle-detail-{{ $tableName }}-{{ $rowId }}.window="collapsed = $event.detail.collapsed"
            class="d-inline-flex align-items-center justify-content-center"
            style="width: 24px; height: 24px; transition: transform 0.2s ease;"
        >
            <svg xmlns="http://www.w3.org/2000/svg" class="detail-toggle-icon" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="transition: transform 0.2s ease;">
                <path d="M12 5v14M5 12h14"></path>
            </svg>
        </div>
    </td>
@endif

{{-- Skip collapse icon for Tabler theme - click on row instead --}}
{{-- @includeWhen(data_get($setUp, 'detail.showCollapseIcon'),
    data_get(collect($row->__powergrid_rules)->last(), 'toggleDetailView') ?? $defaultCollapseIcon,
    [
        'view' => data_get($setUp, 'detail.viewIcon') ?? null,
    ]
) --}}

@includeWhen($radio && $radioAttribute, 'polirium-datatable::components.radio-row', [
    'attribute' => $row->{$radioAttribute},
])

@includeWhen($checkbox && $checkboxAttribute, 'polirium-datatable::components.checkbox-row', [
    'attribute' => $row->{$checkboxAttribute},
])

@foreach ($columns as $column)
    @php
        $field = data_get($column, 'field');
        $content = $row->{$field} ?? '';
        $templateContent = null;

        if (is_array($content)) {
            $template = data_get($column, 'template');
            $templateContent = $content;
            $content = '';
        }

        $contentClassField = data_get($column, 'contentClassField');

        if ($content instanceof \UnitEnum) {
            $content = $content instanceof \BackedEnum
                ? $content->value
                : $content->name;
        }

        $content = preg_replace('#<script(.*?)>(.*?)</script>#is', '', $content ?? '');
        $field = data_get($column, 'dataField', data_get($column, 'field'));

        $contentClass = data_get($column, 'contentClasses');

        if (is_array(data_get($column, 'contentClasses'))) {
            $contentClass = array_key_exists($content, data_get($column, 'contentClasses'))
                ? data_get($column, 'contentClasses')[$content]
                : '';
        }
    @endphp
    <td
        @class([
            theme_style($theme, 'table.body.td'),
            data_get($column, 'bodyClass'),
        ])
        @style([
            'display:none' => data_get($column, 'hidden'),
            data_get($column, 'bodyStyle'),
        ])
        wire:key="row-{{ substr($rowId, 0, 6) }}-{{ $field }}-{{ $childIndex ?? 0 }}"
        data-column="{{ data_get($column, 'isAction') ? 'actions' : $field }}"
    >
        @if (count(data_get($column, 'customContent')) > 0)
            @include(data_get($column, 'customContent.view'), data_get($column, 'customContent.params'))
        @else
            @if (data_get($column, 'isAction'))
                <div class="pg-actions">
                    @if (method_exists($this, 'actionsFromView') && ($actionsFromView = $this->actionsFromView($row)))
                        <div wire:key="actions-view-{{ data_get($row, $this->realPrimaryKey) }}">
                            {!! $actionsFromView !!}
                        </div>
                    @endif

                    <div wire:replace.self>
                        @if (data_get($column, 'isAction'))
                            <div
                                x-data="pgRenderActions({ rowId: @js(data_get($row, $this->realPrimaryKey)), parentId: @js($parentId) })"
                                class="{{ theme_style($theme, 'table.body.tdActionsContainer') }}"
                                x-html="toHtml"
                            >
                            </div>
                        @endif
                    </div>
                </div>
            @endif

            @php
                $showEditOnClick = $this->shouldShowEditOnClick($column, $row);
            @endphp

            @if ($showEditOnClick === true)
                <span @class([$contentClassField, $contentClass])>
                    @include(theme_style($theme, 'editable.view') ?? null, [
                        'editable' => data_get($column, 'editable'),
                    ])
                </span>
            @elseif(count(data_get($column, 'toggleable')) > 0)
                @php
                    $showToggleable = $this->shouldShowToggleable($column, $row);
                @endphp
                @include(theme_style($theme, 'toggleable.view'), ['tableName' => $tableName])
            @else
                <span @class([$contentClassField, $contentClass])>
                    @if (filled($templateContent))
                        <div
                            x-data="pgRenderRowTemplate({
                                parentId: @js($parentId),
                                templateContent: @js($templateContent)
                            })"
                            x-html="rendered"
                        >
                        </div>
                    @else
                        <div>{!! data_get($column, 'index') ? $rowIndex : $content !!}</div>
                    @endif
                </span>
            @endif
        @endif
    </td>
@endforeach
