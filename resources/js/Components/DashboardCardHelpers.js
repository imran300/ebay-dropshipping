export function shouldRenderDashboardCardTitle(title) {
    return title !== '' && title != null;
}

/**
 * @param {string} [variant]
 * @returns {{ rootClasses: string, bodyClasses: string, titleClasses: string, headingClasses: string }}
 */
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

/**
 * Merges a partial / untrusted helper result with defaults so DashboardCard never reads undefined keys.
 *
 * @param {unknown} partial
 * @param {string} [variant]
 * @returns {{ rootClasses: string, bodyClasses: string, titleClasses: string, headingClasses: string }}
 */
export function mergeDashboardCardClasses(partial, variant = 'stat') {
    const base = getDashboardCardClasses(variant);
    if (!partial || typeof partial !== 'object') {
        return { ...base };
    }

    return {
        rootClasses:
            typeof partial.rootClasses === 'string'
                ? partial.rootClasses
                : base.rootClasses,
        bodyClasses:
            typeof partial.bodyClasses === 'string'
                ? partial.bodyClasses
                : base.bodyClasses,
        titleClasses:
            typeof partial.titleClasses === 'string'
                ? partial.titleClasses
                : base.titleClasses,
        headingClasses:
            typeof partial.headingClasses === 'string'
                ? partial.headingClasses
                : base.headingClasses,
    };
}

export function safeShouldRenderDashboardCardTitle(title) {
    try {
        return shouldRenderDashboardCardTitle(title);
    } catch {
        return false;
    }
}
