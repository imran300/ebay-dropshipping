export function shouldRenderDashboardCardTitle(title) {
    return title !== '' && title != null;
}

export function getDashboardCardClasses(variant = 'stat') {
    const isPanel = variant === 'panel';

    return {
        rootClasses: isPanel
            ? 'overflow-hidden bg-blue-100 shadow-sm ring-1 ring-blue-200 sm:rounded-lg'
            : 'rounded-lg bg-blue-100 p-6 shadow-sm ring-1 ring-blue-200',
        bodyClasses: isPanel ? 'divide-y divide-gray-100' : '',
        titleClasses: isPanel
            ? 'border-b border-gray-200 px-6 py-4'
            : 'mb-2 text-sm text-gray-500',
        headingClasses: isPanel
            ? 'text-base font-semibold text-gray-900'
            : '',
    };
}
