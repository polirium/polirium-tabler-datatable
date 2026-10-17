<?php

namespace Polirium\Datatable\Livewire;

use Illuminate\Contracts\View\View;
use Livewire\Attributes\On;
use Livewire\Component;

/**
 * Detail panel of one table row.
 *
 * Opening and closing happen in the browser. The panel content is rendered
 * by the server once, the first time the row is opened (load()), so closing
 * other rows or reopening this one costs no request.
 *
 * @codeCoverageIgnore
 */
class Detail extends Component
{
    public string $tableName = '';

    public int|string $rowId = '';

    public string $trClass = '';

    /** Whether the content has been rendered (the row was opened once). */
    public bool $show = false;

    public mixed $row = null;

    public string $view = '';

    public mixed $options = null;

    public bool $collapseOthers = false;

    /** Active tab of detail views that switch tabs on the server ($set('tab', n)). */
    public int $tab = 1;

    public function load(): void
    {
        $this->show = true;

        $this->dispatch('pg-toggle-detail-' . $this->tableName . '-loaded');
    }

    #[On('pg-refresh-detail-{tableName}')]
    public function refreshRow(): void
    {
        // Re-render so an opened detail re-queries the DB after an edit.
    }

    public function render(): View
    {
        return view('polirium-datatable::livewire.detail');
    }
}
