<?php

namespace Polirium\Datatable\Recorders;

use Carbon\CarbonImmutable;
use Illuminate\Config\Repository;
use Laravel\Pulse\Pulse;
use Polirium\Datatable\Events\PowerGridPerformanceData;

/** @codeCoverageIgnore */
class PowerGridPerformanceRecorder
{
    public string $listen = PowerGridPerformanceData::class;

    public function __construct(
        protected Pulse $pulse,
        protected Repository $config
    ) {}

    public function record(PowerGridPerformanceData $class): void
    {
        $now = CarbonImmutable::now();

        $data = collect($class);

        $this->pulse->set(
            type: 'powergrid-performance',
            key: uniqid(),
            value: $data,
            timestamp: $now->getTimestamp()
        );
    }
}
