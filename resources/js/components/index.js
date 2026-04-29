import pgToggleable from './pg-toggleable'
import pgFlatpickr from "./pg-flatpickr";
import pgEditable from "./pg-editable";
import pgResponsive from './pgResponsive';
import pgMobileCard from './pgMobileCard';
import pgTomSelect from "./select/tomSelect";
import pgSlimSelect from "./select/slimSelect";
import pgLoadMore from "./pg-load-more";
import pgRenderActions from "./pg-render-actions";
import pgRenderRowTemplate from "./pg-render-row-template";
import pgRowAttributes from "./pg-row-attributes";
import pgFilterBuilder from "./pg-filter-builder";
import pgToast from "./pg-toast";

window.pgToggleable = pgToggleable
window.pgFlatpickr = pgFlatpickr
window.pgEditable = pgEditable
window.pgResponsive = pgResponsive
window.pgMobileCard = pgMobileCard
window.pgTomSelect = pgTomSelect
window.pgSlimSelect = pgSlimSelect
window.pgLoadMore = pgLoadMore
window.pgRenderActions = pgRenderActions
window.pgRowAttributes = pgRowAttributes
window.pgRenderRowTemplate = pgRenderRowTemplate
window.pgFilterBuilder = pgFilterBuilder

document.addEventListener("DOMContentLoaded", () => {
    // Initialize toast system
    pgToast();

    // Double-click to copy cell content
    document.addEventListener('dblclick', (e) => {
        const td = e.target.closest('td[data-column]');
        if (!td || td.dataset.column === 'actions') return;

        const text = td.innerText.trim();
        if (!text) return;

        navigator.clipboard.writeText(text).then(() => {
            // Show copy feedback
            const feedback = document.createElement('div');
            feedback.className = 'pg-copy-feedback';
            feedback.textContent = 'Đã copy!';

            // Position near cursor
            feedback.style.left = e.pageX + 'px';
            feedback.style.top = (e.pageY - 30) + 'px';
            document.body.appendChild(feedback);

            requestAnimationFrame(() => feedback.classList.add('pg-copy-show'));

            setTimeout(() => {
                feedback.classList.remove('pg-copy-show');
                feedback.classList.add('pg-copy-hide');
                setTimeout(() => feedback.remove(), 300);
            }, 1200);
        }).catch(() => {
            // Fallback for older browsers
            const textarea = document.createElement('textarea');
            textarea.value = text;
            textarea.style.position = 'fixed';
            textarea.style.opacity = '0';
            document.body.appendChild(textarea);
            textarea.select();
            document.execCommand('copy');
            textarea.remove();
        });
    });

    // Livewire v4: Replace 'commit' hook with 'interceptMessage'
    Livewire.interceptMessage(({ message, onSuccess, onFailure }) => {
        const component = message.component;

        if (component.ephemeral.setUp && component.ephemeral.setUp.hasOwnProperty('responsive')) {
            onSuccess(() => {
                queueMicrotask(() => {
                    window.dispatchEvent(
                        new CustomEvent('pg-livewire-request-finished')
                    );
                })
            })

            onFailure(() => {
                window.dispatchEvent(
                    new CustomEvent('pg-livewire-request-finished')
                );
            })
        }
    })
})
