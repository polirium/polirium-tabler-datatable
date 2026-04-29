<?php

namespace Polirium\Datatable;

/**
 * Standardized action buttons for PowerGrid tables.
 *
 * Usage in actions() method:
 *   ActionButtons::edit('show-modal-edit', $row->id)
 *   ActionButtons::delete('trigger-delete', $row->id)
 *   ActionButtons::view('show-detail', $row->id)
 *   ActionButtons::copy('copy-record', $row->id)
 */
class ActionButtons
{
    /**
     * Standard edit button (primary, pencil icon).
     */
    public static function edit(string $event, int|string $id, ?string $permission = null): ?Button
    {
        if ($permission && ! auth()->user()?->can($permission)) {
            return null;
        }

        return Button::add('edit')
            ->slot(tabler_icon('pencil', ['class' => 'icon']))
            ->id()
            ->class('btn btn-primary btn-icon btn-sm me-1')
            ->attributes([
                'aria-label' => trans('polirium-datatable::datatable.actions.edit'),
                'title' => trans('polirium-datatable::datatable.actions.edit'),
            ])
            ->dispatch($event, ['id' => $id]);
    }

    /**
     * Standard delete button (outline-danger, trash icon, with confirmation).
     */
    public static function delete(string $event, int|string $id, ?string $permission = null, ?string $confirmMessage = null): ?Button
    {
        if ($permission && ! auth()->user()?->can($permission)) {
            return null;
        }

        return Button::add('delete')
            ->slot(tabler_icon('trash', ['class' => 'icon']))
            ->id()
            ->class('btn btn-outline-danger btn-icon btn-sm')
            ->attributes([
                'aria-label' => trans('polirium-datatable::datatable.actions.delete'),
                'title' => trans('polirium-datatable::datatable.actions.delete'),
                'wire:confirm' => $confirmMessage ?? trans('polirium-datatable::datatable.actions.confirm_delete'),
            ])
            ->dispatch($event, ['id' => $id]);
    }

    /**
     * Standard view button (outline-info, eye icon).
     */
    public static function view(string $event, int|string $id, ?string $permission = null): ?Button
    {
        if ($permission && ! auth()->user()?->can($permission)) {
            return null;
        }

        return Button::add('view')
            ->slot(tabler_icon('eye', ['class' => 'icon']))
            ->id()
            ->class('btn btn-outline-info btn-icon btn-sm me-1')
            ->attributes([
                'aria-label' => trans('polirium-datatable::datatable.actions.view'),
                'title' => trans('polirium-datatable::datatable.actions.view'),
            ])
            ->dispatch($event, ['id' => $id]);
    }

    /**
     * Standard copy/duplicate button (outline-secondary, copy icon).
     */
    public static function copy(string $event, int|string $id, ?string $permission = null): ?Button
    {
        if ($permission && ! auth()->user()?->can($permission)) {
            return null;
        }

        return Button::add('copy')
            ->slot(tabler_icon('copy', ['class' => 'icon']))
            ->id()
            ->class('btn btn-outline-secondary btn-icon btn-sm me-1')
            ->attributes([
                'aria-label' => trans('polirium-datatable::datatable.actions.copy'),
                'title' => trans('polirium-datatable::datatable.actions.copy'),
            ])
            ->dispatch($event, ['id' => $id]);
    }

    /**
     * Standard link button (opens in new tab).
     */
    public static function link(string $url, ?string $permission = null): ?Button
    {
        if ($permission && ! auth()->user()?->can($permission)) {
            return null;
        }

        return Button::add('link')
            ->slot(tabler_icon('external-link', ['class' => 'icon']))
            ->id()
            ->class('btn btn-outline-primary btn-icon btn-sm me-1')
            ->attributes([
                'aria-label' => trans('polirium-datatable::datatable.actions.link'),
                'title' => trans('polirium-datatable::datatable.actions.link'),
                'onclick' => "window.open('{$url}', '_blank')",
            ]);
    }

    /**
     * Build actions array, filtering out nulls (from permission checks).
     *
     * @param  array<int, Button|null>  $buttons
     * @return array<int, Button>
     */
    public static function make(Button|null ...$buttons): array
    {
        return array_values(array_filter($buttons));
    }
}
