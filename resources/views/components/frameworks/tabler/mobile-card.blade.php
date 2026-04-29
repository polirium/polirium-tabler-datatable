@props([
    'row' => null,
    'rowId' => null,
    'columns' => [],
    'primaryKey' => 'id',
])

@php
    $enableMobileCard = data_get($setUp, 'responsive.mobileCardView', true);
    $mobileHiddenColumns = data_get($setUp, 'responsive.mobileHiddenColumns', []);

    // Find title column for card header (first non-action, non-hidden column)
    $titleColumn = collect($columns)->first(function ($column) {
        $isAction = is_array($column) ? data_get($column, 'isAction') : $column->isAction;
        $hidden = is_array($column) ? data_get($column, 'hidden') : $column->hidden;
        $field = is_array($column) ? data_get($column, 'field') : $column->field;
        return !$isAction
            && !$hidden
            && !in_array($field, $mobileHiddenColumns ?? []);
    });

    // Find actions column
    $actionsColumn = collect($columns)->first(function ($column) {
        $isAction = is_array($column) ? data_get($column, 'isAction') : $column->isAction;
        return $isAction === true;
    });

    // Group columns: primary (id, name, code) and secondary (others)
    $primaryFields = ['id', 'name', 'code', 'title', 'email', 'phone', 'status'];
    $secondaryFields = ['description', 'content', 'note', 'remarks', 'address'];

    $primaryColumns = [];
    $secondaryColumns = [];

    foreach ($columns as $column) {
        $field = is_array($column) ? data_get($column, 'field') : $column->field;
        $isAction = is_array($column) ? data_get($column, 'isAction') : $column->isAction;
        $isHidden = is_array($column) ? data_get($column, 'hidden') : $column->hidden;

        if ($isAction || $isHidden || in_array($field, $mobileHiddenColumns ?? [])) {
            continue;
        }

        if (in_array($field, $primaryFields)) {
            $primaryColumns[] = $column;
        } else {
            $secondaryColumns[] = $column;
        }
    }

    // Merge: primary first, then secondary
    $orderedColumns = array_merge($primaryColumns, $secondaryColumns);
@endphp

{{-- Mobile Card --}}
<div class="datatable-mobile-card" wire:key="mobile-card-{{ substr($rowId, 0, 6) }}">
    {{-- Card Header: Title + Actions --}}
    <div class="card-header">
        @if($titleColumn)
            <span class="card-title">
                @php
                    $field = is_array($titleColumn) ? data_get($titleColumn, 'field') : $titleColumn->field;
                    $content = data_get($row, $field);

                    $isTemplate = is_array($titleColumn) ? data_get($titleColumn, 'template') : $titleColumn->template;
                    $customContent = is_array($titleColumn) ? data_get($titleColumn, 'customContent', []) : ($titleColumn->customContent ?? []);
                @endphp
                @if (!empty($customContent) && count($customContent) > 0)
                    @php
                        $customView = is_array($titleColumn) ? data_get($titleColumn, 'customContent.view') : ($titleColumn->customContent['view'] ?? null);
                        $customParams = is_array($titleColumn) ? data_get($titleColumn, 'customContent.params', []) : ($titleColumn->customContent['params'] ?? []);
                    @endphp
                    @include($customView, $customParams)
                @elseif ($isTemplate)
                    <div
                        x-data="pgRenderRowTemplate({
                            parentId: null,
                            templateContent: @js(data_get($row, '__powergrid_rules.0.options', []))
                        })"
                        x-html="rendered"
                    ></div>
                @else
                    {!! $content !!}
                @endif
            </span>
        @else
            <span class="card-title">#{{ data_get($row, $primaryKey) }}</span>
        @endif

        {{-- Card Actions --}}
        @if($actionsColumn)
            <div class="card-actions">
                @if (method_exists($this, 'actionsFromView') && ($actionsFromView = $this->actionsFromView($row)))
                    <div wire:key="actions-view-mobile-{{ data_get($row, $primaryKey) }}">
                        {!! $actionsFromView !!}
                    </div>
                @endif

                <div wire:replace.self>
                    <div
                        x-data="pgRenderActions({ rowId: @js(data_get($row, $primaryKey)), parentId: null })"
                        class="pg-actions-mobile"
                        x-html="toHtml"
                    ></div>
                </div>
            </div>
        @endif
    </div>

    {{-- Card Body: 2-Column Grid Layout --}}
    <div class="card-body">
        @foreach($orderedColumns as $column)
            @php
                $field = is_array($column) ? data_get($column, 'field') : $column->field;
                $isAction = is_array($column) ? data_get($column, 'isAction') : $column->isAction;
                $isHidden = is_array($column) ? data_get($column, 'hidden') : $column->hidden;
                $isTemplate = is_array($column) ? data_get($column, 'template') : $column->template;
                $customContent = is_array($column) ? data_get($column, 'customContent', []) : ($column->customContent ?? []);
                $hasCustomContent = is_array($customContent) && count($customContent) > 0;

                // Skip actions, hidden columns, mobile-hidden columns, and title column (already in header)
                if ($isAction || $isHidden || in_array($field, $mobileHiddenColumns ?? [])) {
                    continue;
                }

                // Skip title column if it was used in header
                if ($titleColumn && $field === (is_array($titleColumn) ? data_get($titleColumn, 'field') : $titleColumn->field)) {
                    continue;
                }

                $content = data_get($row, $field, '');
                $label = is_array($column) ? data_get($column, 'title', $field) : ($column->title ?? $field);
                $contentClassField = is_array($column) ? data_get($column, 'contentClassField') : ($column->contentClassField ?? null);
                $contentClasses = is_array($column) ? data_get($column, 'contentClasses') : ($column->contentClasses ?? null);
                $columnIndex = is_array($column) ? data_get($column, 'index') : ($column->index ?? false);

                // Determine if field should be full-width
                $isFullWidth = in_array($field, $secondaryFields) || strlen($content ?? '') > 50;

                // Handle enum content
                if ($content instanceof \UnitEnum) {
                    $content = $content instanceof \BackedEnum
                        ? $content->value
                        : $content->name;
                }

                // Sanitize content
                $content = preg_replace('#<script(.*?)>(.*?)</script>#is', '', $content ?? '');
            @endphp

            <div class="card-field {{ $isFullWidth ? 'card-field-full' : '' }}" data-column="{{ $field }}">
                <span class="field-label">{{ $label }}</span>
                <span class="field-value @if($contentClassField) {{ data_get($row, $contentClassField) }} @endif @if(is_array($contentClasses) && isset($contentClasses[$content])) {{ $contentClasses[$content] }} @elseif(is_string($contentClasses)) {{ $contentClasses }} @endif {{ in_array($field, $secondaryFields) ? 'field-value-secondary' : '' }}">
                    @if ($hasCustomContent)
                        @php
                            $customView = is_array($column) ? data_get($column, 'customContent.view') : ($column->customContent['view'] ?? null);
                            $customParams = is_array($column) ? data_get($column, 'customContent.params', []) : ($column->customContent['params'] ?? []);
                        @endphp
                        @include($customView, $customParams)
                    @elseif ($isTemplate)
                        <div
                            x-data="pgRenderRowTemplate({
                                parentId: null,
                                templateContent: @js(data_get($row, '__powergrid_rules.0.options', []))
                            })"
                            x-html="rendered"
                        ></div>
                    @elseif ($columnIndex)
                        <div>{{ $loop->parent->index + 1 }}</div>
                    @else
                        <div>{!! $content !!}</div>
                    @endif
                </span>
            </div>
        @endforeach
    </div>
</div>
