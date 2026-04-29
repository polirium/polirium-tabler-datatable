@if (data_get($setUp, 'header.showMessageSoftDeletes') &&
        ($softDeletes === 'withTrashed' || $softDeletes === 'onlyTrashed'))
    <div
        class="alert alert-warning-lt my-1"
        role="alert"
    >
        @if ($softDeletes === 'withTrashed')
            @lang('polirium-datatable::datatable.soft_deletes.message_with_trashed')
        @else
            @lang('polirium-datatable::datatable.soft_deletes.message_only_trashed')
        @endif
    </div>
@endif
