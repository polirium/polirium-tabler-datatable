<tr
    x-data="{ collapsed: @entangle('show'), collapseOthers: @entangle('collapseOthers') }"
    class="{{ $trClass }}"
    x-show="collapsed || (collapsed && collapseOthers && expandedId == '{{ $rowId }}')"
>
    <td colspan="999" style="padding: 0; border: 0;">
        <div
            x-show="collapsed"
            x-transition:enter.duration.200ms
            x-transition:leave.duration.150ms
            x-transition.opacity
            style="overflow: hidden;"
        >
            @includeWhen($show, $view, [
                'id' => $rowId,
                'options' => $options,
            ])
        </div>
    </td>
</tr>
