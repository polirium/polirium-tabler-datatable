export default (params) => ({
    init() {
        // Livewire v4: Replace 'commit' hook with 'interceptMessage'
        Livewire.interceptMessage(({ message, onSuccess }) => {
            const component = message.component;
            if (component.name === params.tableName) {
                onSuccess(() => {
                    if (typeof window.pgFilterBuilderInit === 'function') {
                        window.pgFilterBuilderInit();
                    }
                });
            }
        });
    },

    applyAndClose() {
        Livewire.dispatch(params.tableName + '-applyFilterBuilder');
    },

    clearAndClose() {
        Livewire.dispatch(params.tableName + '-clearFilterBuilder');
    }
});
