<div>
    @includeIf(data_get($setUp, 'footer.includeViewOnTop'))

    @if (method_exists($this->records, 'links'))
        {!! $this->records->links(data_get($theme, 'root') . '.pagination', [
            'recordCount' => data_get($setUp, 'footer.recordCount'),
        ]) !!}
    @endif

    @includeIf(data_get($setUp, 'footer.includeViewOnBottom'))
</div>
