<div class="row g-2 justify-content-center justify-content-sm-between">
    <div class="col-auto d-flex align-items-center">
        @if($paginator->count() > 0)
            @if ($recordCount === 'full')
                <p class="m-0 text-secondary">
                    {{ trans('polirium-datatable::datatable.pagination.showing') }}
                    <strong>{{ $paginator->firstItem() }}</strong>
                    {{ trans('polirium-datatable::datatable.pagination.to') }}
                    <strong>{{ $paginator->lastItem() }}</strong>
                    {{ trans('polirium-datatable::datatable.pagination.of') }}
                    <strong>{{ $paginator->total() }}</strong>
                    {{ trans('polirium-datatable::datatable.pagination.results') }}
                </p>
            @elseif($recordCount === 'short')
                <p class="m-0 text-secondary">
                    <strong>{{ $paginator->firstItem() }}</strong> -
                    <strong>{{ $paginator->lastItem() }}</strong> |
                    <strong>{{ $paginator->total() }}</strong>
                </p>
            @elseif($recordCount === 'min')
                <p class="m-0 text-secondary">
                    <strong>{{ $paginator->firstItem() }}</strong> -
                    <strong>{{ $paginator->lastItem() }}</strong>
                </p>
            @endif
        @endif
    </div>
    <div class="col-auto">
        @if ($paginator->hasPages() && $recordCount != 'min')
            <ul class="pagination m-0 ms-auto">
                {{-- Previous Page Link --}}
                @if ($paginator->onFirstPage())
                    <li class="page-item disabled">
                        <a class="page-link" href="#" tabindex="-1" aria-disabled="true">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-1">
                                <path d="M15 6l-6 6l6 6"></path>
                            </svg>
                        </a>
                    </li>
                @else
                    <li class="page-item">
                        <button
                            type="button"
                            class="page-link"
                            wire:click="previousPage('{{ $paginator->getPageName() }}')"
                            wire:loading.attr="disabled"
                            rel="prev"
                            aria-label="{{ trans('pagination.previous') }}"
                        >
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-1">
                                <path d="M15 6l-6 6l6 6"></path>
                            </svg>
                        </button>
                    </li>
                @endif

                {{-- Pagination Elements --}}
                @foreach ($elements as $element)
                    {{-- "Three Dots" Separator --}}
                    @if (is_string($element))
                        <li class="page-item disabled"><span class="page-link">{{ $element }}</span></li>
                    @endif

                    {{-- Array Of Links --}}
                    @if (is_array($element))
                        @foreach ($element as $page => $url)
                            @if ($page == $paginator->currentPage())
                                <li class="page-item active" wire:key="paginator-page-{{ $page }}">
                                    <a class="page-link" href="#">{{ $page }}</a>
                                </li>
                            @else
                                <li class="page-item" wire:key="paginator-page-{{ $page }}">
                                    <button
                                        type="button"
                                        class="page-link"
                                        wire:click="gotoPage({{ $page }}, '{{ $paginator->getPageName() }}')"
                                    >{{ $page }}</button>
                                </li>
                            @endif
                        @endforeach
                    @endif
                @endforeach

                {{-- Next Page Link --}}
                @if ($paginator->hasMorePages())
                    <li class="page-item">
                        <button
                            type="button"
                            class="page-link"
                            wire:click="nextPage('{{ $paginator->getPageName() }}')"
                            wire:loading.attr="disabled"
                            rel="next"
                            aria-label="{{ trans('pagination.next') }}"
                        >
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-1">
                                <path d="M9 6l6 6l-6 6"></path>
                            </svg>
                        </button>
                    </li>
                @else
                    <li class="page-item disabled">
                        <a class="page-link" href="#" tabindex="-1" aria-disabled="true">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-1">
                                <path d="M9 6l6 6l-6 6"></path>
                            </svg>
                        </a>
                    </li>
                @endif
            </ul>
        @endif

        @if ($paginator->hasPages() && $recordCount == 'min')
            <ul class="pagination m-0 ms-auto">
                {{-- Previous Page Link --}}
                @if ($paginator->onFirstPage())
                    <li class="page-item disabled">
                        <button
                            type="button"
                            class="page-link"
                            rel="prev"
                            aria-label="{{ trans('pagination.previous') }}"
                        >
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-1">
                                <path d="M15 6l-6 6l6 6"></path>
                            </svg>
                        </button>
                    </li>
                @else
                    @if (method_exists($paginator, 'getCursorName'))
                        <li class="page-item">
                            <button
                                type="button"
                                class="page-link"
                                wire:click="setPage('{{ $paginator->previousCursor()->encode() }}','{{ $paginator->getCursorName() }}')"
                                wire:loading.attr="disabled"
                                rel="prev"
                                aria-label="{{ trans('pagination.previous') }}"
                            >
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-1">
                                    <path d="M15 6l-6 6l6 6"></path>
                                </svg>
                            </button>
                        </li>
                    @else
                        <li class="page-item">
                            <button
                                type="button"
                                class="page-link"
                                wire:click="previousPage('{{ $paginator->getPageName() }}')"
                                wire:loading.attr="disabled"
                                rel="prev"
                                aria-label="{{ trans('pagination.previous') }}"
                            >
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-1">
                                    <path d="M15 6l-6 6l6 6"></path>
                                </svg>
                            </button>
                        </li>
                    @endif
                @endif

                {{-- Next Page Link --}}
                @if ($paginator->hasMorePages())
                    @if (method_exists($paginator, 'getCursorName'))
                        <li class="page-item">
                            <button
                                type="button"
                                class="page-link"
                                wire:click="setPage('{{ $paginator->nextCursor()->encode() }}','{{ $paginator->getCursorName() }}')"
                                wire:loading.attr="disabled"
                                rel="next"
                                aria-label="{{ trans('pagination.next') }}"
                            >
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-1">
                                    <path d="M9 6l6 6l-6 6"></path>
                                </svg>
                            </button>
                        </li>
                    @else
                        <li class="page-item">
                            <button
                                type="button"
                                class="page-link"
                                wire:click="nextPage('{{ $paginator->getPageName() }}')"
                                wire:loading.attr="disabled"
                                rel="next"
                                aria-label="{{ trans('pagination.next') }}"
                            >
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-1">
                                    <path d="M9 6l6 6l-6 6"></path>
                                </svg>
                            </button>
                        </li>
                    @endif
                @else
                    <li class="page-item disabled">
                        <button
                            type="button"
                            class="page-link"
                            rel="next"
                            aria-label="{{ trans('pagination.next') }}"
                        >
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-1">
                                <path d="M9 6l6 6l-6 6"></path>
                            </svg>
                        </button>
                    </li>
                @endif
            </ul>
        @endif
    </div>
</div>
