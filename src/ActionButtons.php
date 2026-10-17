<?php

namespace Polirium\Datatable;

/**
 * Standard row action buttons for datatables (one look on every screen).
 *
 * Icon-only ghost buttons with a tooltip: grey, delete in red. Every delete
 * asks for confirmation. make() puts them in a fixed order:
 * view, edit, copy, print, other, delete.
 *
 *   public function actions($row): array
 *   {
 *       return ActionButtons::make(
 *           ActionButtons::edit('edit-record', $row->id, 'records.edit'),
 *           ActionButtons::delete('delete-record', $row->id, 'records.delete'),
 *       );
 *   }
 *
 * Event-based buttons dispatch "$event" with ['id' => $id]. For a different
 * payload or behaviour, take the button and chain PowerGrid methods
 * (->dispatchTo(), ->openModal(), ->route()...), or build one with custom().
 */
class ActionButtons
{
    private const ORDER = ['view' => 10, 'edit' => 20, 'copy' => 30, 'print' => 40, 'link' => 50, 'custom' => 60, 'cancel' => 80, 'delete' => 90];

    public static function view(string $event, int|string $id, ?string $permission = null): ?Button
    {
        return self::allowed($permission) ? self::icon('view', 'eye', self::label('view'))->dispatch($event, ['id' => $id]) : null;
    }

    public static function edit(string $event, int|string $id, ?string $permission = null): ?Button
    {
        return self::allowed($permission) ? self::icon('edit', 'pencil', self::label('edit'))->dispatch($event, ['id' => $id]) : null;
    }

    public static function copy(string $event, int|string $id, ?string $permission = null): ?Button
    {
        return self::allowed($permission) ? self::icon('copy', 'copy', self::label('copy'))->dispatch($event, ['id' => $id]) : null;
    }

    /**
     * Opens a printable page in a new tab.
     */
    public static function print(string $url, ?string $permission = null): ?Button
    {
        return self::allowed($permission) ? self::opens('print', 'printer', self::label('print'), $url) : null;
    }

    /**
     * Opens a page in a new tab.
     */
    public static function link(string $url, ?string $permission = null): ?Button
    {
        return self::allowed($permission) ? self::opens('link', 'external-link', self::label('link'), $url) : null;
    }

    /**
     * Cancels a document (with confirmation).
     */
    public static function cancel(string $event, int|string $id, ?string $permission = null, ?string $confirmMessage = null): ?Button
    {
        if (! self::allowed($permission)) {
            return null;
        }

        return self::icon('cancel', 'ban', self::label('cancel'), true)
            ->attributes(['wire:confirm' => $confirmMessage ?? trans('polirium-datatable::datatable.actions.confirm_cancel')])
            ->dispatch($event, ['id' => $id]);
    }

    public static function delete(string $event, int|string $id, ?string $permission = null, ?string $confirmMessage = null): ?Button
    {
        if (! self::allowed($permission)) {
            return null;
        }

        return self::icon('delete', 'trash', self::label('delete'), true)
            ->attributes(['wire:confirm' => $confirmMessage ?? trans('polirium-datatable::datatable.actions.confirm_delete')])
            ->dispatch($event, ['id' => $id]);
    }

    /**
     * Any other action in the standard look (e.g. toggle active, restore).
     * Chain the behaviour: ->dispatch(), ->openModal(), ->route()...
     */
    public static function custom(string $name, string $icon, string $label, bool $danger = false, ?string $permission = null): ?Button
    {
        return self::allowed($permission) ? self::icon($name, $icon, $label, $danger) : null;
    }

    /**
     * Drop buttons hidden by permissions and sort into the standard order.
     *
     * @return array<int, Button>
     */
    public static function make(Button|null ...$buttons): array
    {
        $buttons = array_values(array_filter($buttons));

        usort($buttons, fn (Button $a, Button $b) => (self::ORDER[$a->action] ?? self::ORDER['custom']) <=> (self::ORDER[$b->action] ?? self::ORDER['custom']));

        return $buttons;
    }

    /**
     * CSS classes of a standard row action (for buttons built elsewhere).
     */
    public static function classes(bool $danger = false): string
    {
        return 'btn btn-sm btn-icon ' . ($danger ? 'btn-ghost-danger' : 'btn-ghost-secondary');
    }

    private static function icon(string $action, string $icon, string $label, bool $danger = false): Button
    {
        return Button::add($action)
            ->slot(tabler_icon($icon, ['class' => 'icon']))
            ->id()
            ->class(self::classes($danger))
            ->attributes(['title' => $label, 'aria-label' => $label]);
    }

    private static function opens(string $action, string $icon, string $label, string $url): Button
    {
        return self::icon($action, $icon, $label)
            ->attributes(['onclick' => 'window.open(' . e(json_encode($url)) . ', \'_blank\')']);
    }

    private static function label(string $key): string
    {
        return trans('polirium-datatable::datatable.actions.' . $key);
    }

    private static function allowed(?string $permission): bool
    {
        return ! $permission || (bool) auth()->user()?->can($permission);
    }
}
