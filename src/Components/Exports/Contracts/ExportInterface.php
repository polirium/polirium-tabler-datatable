<?php

namespace Polirium\Datatable\Components\Exports\Contracts;

use Polirium\Datatable\Components\SetUp\Exportable;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

interface ExportInterface
{
    public function download(array $exportOptions): BinaryFileResponse;

    public function build(Exportable|array $exportOptions): void;
}
