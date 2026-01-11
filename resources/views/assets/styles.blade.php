@if (filled(config('polirium-datatable.plugins.flatpickr.css')))
    <link
        rel="stylesheet"
        href="{{ config('polirium-datatable.plugins.flatpickr.css') }}"
    >
@endif

@isset($cssPath)
    <style>
        {!! file_get_contents($cssPath) !!}
    </style>
@endisset
