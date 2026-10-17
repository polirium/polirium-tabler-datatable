{{-- Showing and hiding is the browser's job: Livewire must not reset this
     row's attributes (display) when the content arrives. --}}
<tr
    wire:ignore.self
    class="{{ $trClass }}"
    x-data="{ open: false }"
    x-show="open"
    x-on:pg-toggle-detail-{{ $tableName }}-{{ $rowId }}.window="open = !! $event.detail?.collapsed; if (open && ! $wire.show) { $wire.load() }"
    x-on:pg-toggle-detail-{{ $tableName }}-hidden-all.window="open = false"
    style="display: none"
>
    <td colspan="999" style="padding: 0; border: 0;">
        <div style="overflow: hidden;">
            @if ($show)
                @include($view, [
                    'id' => $rowId,
                    'options' => $options,
                ])
            @else
                <div class="d-flex align-items-center justify-content-center gap-2 py-4 text-secondary">
                    <span class="spinner-border spinner-border-sm" role="status"></span>
                    <span>{{ __('polirium-datatable::datatable.loading') }}</span>
                </div>
            @endif
        </div>
    </td>
</tr>
