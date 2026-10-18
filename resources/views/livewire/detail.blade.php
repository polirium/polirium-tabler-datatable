{{-- Showing and hiding is the browser's job: Livewire must not reset this
     row's attributes (display) when the content arrives.
     The content is fetched once: when the pointer rests on the row (preload)
     or when it is opened, whichever comes first. --}}
<tr
    wire:ignore.self
    class="{{ $trClass }}"
    x-data="{
        open: false,
        reveal: false,
        fetching: false,
        timer: null,
        fetch() {
            if (! $wire.show && ! this.fetching) {
                this.fetching = true;
                $wire.load();
            }
        },
        toggle(open) {
            clearTimeout(this.timer);
            if (open) {
                this.open = true;
                this.fetch();
                this.$nextTick(() => requestAnimationFrame(() => this.reveal = true));
            } else if (this.open) {
                this.reveal = false;
                this.timer = setTimeout(() => this.open = false, 200);
            }
        },
    }"
    x-show="open"
    x-on:pg-toggle-detail-{{ $tableName }}-{{ $rowId }}.window="toggle(!! $event.detail?.collapsed)"
    x-on:pg-preload-detail-{{ $tableName }}-{{ $rowId }}.window="fetch()"
    x-on:pg-toggle-detail-{{ $tableName }}-hidden-all.window="toggle(false)"
    style="display: none"
>
    <td colspan="999" style="padding: 0; border: 0;">
        <div class="pg-detail-reveal" wire:ignore.self :class="{ 'is-open': reveal }">
            <div class="pg-detail-inner">
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
        </div>
    </td>
</tr>
