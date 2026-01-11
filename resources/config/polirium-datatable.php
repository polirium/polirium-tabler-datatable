<?php

use Illuminate\Support\Js;

return [

    /*
    |--------------------------------------------------------------------------
    | Theme
    |--------------------------------------------------------------------------
    |
    | Polirium Datatable supports Tabler UI (default), Bootstrap 5 themes.
    | Tabler UI is the recommended theme for Polirium ERP.
    | Configure here the theme of your choice.
    */

    'theme' => \Polirium\Datatable\Themes\Tabler::class,
    // 'theme' => \Polirium\Datatable\Themes\Bootstrap5::class,
    // 'theme' => \Polirium\Datatable\Themes\DaisyUI::class,

    'cache_ttl' => null,

    'icon_resources' => [
        'paths' => [
            // 'default' => 'resources/views/components/icons',
            // 'outline' => 'vendor/wireui/wireui/resources/views/components/icons/outline',
            // 'solid'   => 'vendor/wireui/wireui/resources/views/components/icons/solid',
        ],

        'allowed' => [
            // 'pencil',
        ],

        'attributes' => ['class' => 'w-5 text-red-600'],
    ],

    /*
    |--------------------------------------------------------------------------
    | Plugins
    |--------------------------------------------------------------------------
    |
    | Plugins used: flatpickr.js to datepicker.
    |
    */

    'plugins' => [
        /*
         * https://flatpickr.js.org
         */
        'flatpickr' => [
            'locales' => [
                'pt_BR' => [
                    'locale' => 'pt',
                    'dateFormat' => 'd/m/Y H:i',
                    'enableTime' => true,
                    'time_24hr' => true,
                ],
            ],
        ],

        'select' => [
            'default' => 'tom',

            /*
             * TomSelect Options
             * https://tom-select.js.org
             */
            'tom' => [
                'plugins' => [
                    'clear_button' => [
                        'title' => 'Remove all selected options',
                    ],
                ],
            ],

            /*
             * Slim Select options
             * https://slimselectjs.com/
             */
            'slim' => [
                'settings' => [
                    'alwaysOpen' => false,
                ],
            ],
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Filters
    |--------------------------------------------------------------------------
    |
    | PowerGrid supports inline and outside filters.
    | 'inline': Filters data inside the table.
    | 'outside': Filters data outside the table.
    | 'null'
    |
    */

    'filter' => 'inline',

    /*
    |--------------------------------------------------------------------------
    | Filters Attributes
    |--------------------------------------------------------------------------

    | You can add custom attributes to the filters.
    | The key is the filter type and the value is a callback function.
    | like: input_text, select, datetime, etc.
    | The callback function receives the field and title as parameters.
    | The callback function must return an array with the attributes.
    */

    'filter_attributes' => [
        'input_text' => \Polirium\Datatable\FilterAttributes\InputText::class,
        'boolean' => \Polirium\Datatable\FilterAttributes\Boolean::class,
        'number' => \Polirium\Datatable\FilterAttributes\Number::class,
        'select' => \Polirium\Datatable\FilterAttributes\Select::class,
    ],

    /*
    |--------------------------------------------------------------------------
    | Persisting
    |--------------------------------------------------------------------------
    |
    | PowerGrid supports persisting of the filters, columns and sorting.
    | 'session': persist in the session.
    | 'cache': persist with cache.
    | 'cookies': persist with cookies (default).
    |
    */

    'persist_driver' => 'cookies',

    /*
    |--------------------------------------------------------------------------
    | Exportable class
    |--------------------------------------------------------------------------
    |
    |
    */

    'exportable' => [
        'default' => 'openspout_v4',
        'openspout_v4' => [
            'xlsx' => \Polirium\Datatable\Components\Exports\OpenSpout\v4\ExportToXLS::class,
            'csv' => \Polirium\Datatable\Components\Exports\OpenSpout\v4\ExportToCsv::class,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Auto-Discover Models
    |--------------------------------------------------------------------------
    |
    | PowerGrid will search for Models in the directories listed below.
    | These Models be listed as options when you run the
    | "artisan powergrid:create" command.
    |
    */

    'auto_discover_models_paths' => [
        app_path('Models'),
    ],
];
