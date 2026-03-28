/**
 * Shared navigation item from HandleInertiaRequests (or any producer).
 *
 * @typedef {{ label: string, route: string, active: string }} NavigationItem
 */

/**
 * Shape consumed by AuthenticatedLayout: two parallel arrays of validated items.
 * `route` is a Ziggy route name; `active` is the pattern passed to route().current().
 *
 * @typedef {{ primary: NavigationItem[], mobile: NavigationItem[] }} LayoutNavigation
 */

function isValidNavigationItem(item) {
    return Boolean(
        item &&
            typeof item === 'object' &&
            typeof item.label === 'string' &&
            item.label !== '' &&
            typeof item.route === 'string' &&
            item.route !== '' &&
            typeof item.active === 'string' &&
            item.active !== ''
    );
}

function normalizeNavigationItems(items) {
    return Array.isArray(items) ? items.filter(isValidNavigationItem) : [];
}

/**
 * Sanitizes raw shared navigation from the server into validated item lists.
 *
 * @param {Record<string, unknown>} [sharedNavigation]
 * @returns {LayoutNavigation}
 */
export function normalizeNavigation(sharedNavigation) {
    const raw =
        sharedNavigation !== null &&
        sharedNavigation !== undefined &&
        typeof sharedNavigation === 'object' &&
        !Array.isArray(sharedNavigation)
            ? sharedNavigation
            : {};

    return {
        primary: normalizeNavigationItems(raw.primary),
        mobile: normalizeNavigationItems(raw.mobile),
    };
}

/**
 * Adapter for layout usage: guarantees {@link LayoutNavigation} with array fields
 * even if normalizeNavigation or upstream data changes.
 *
 * @param {unknown} sharedNavigation
 * @returns {LayoutNavigation}
 */
export function getLayoutNavigation(sharedNavigation) {
    const normalized = normalizeNavigation(
        sharedNavigation && typeof sharedNavigation === 'object' && !Array.isArray(sharedNavigation)
            ? sharedNavigation
            : {},
    );

    return {
        primary: Array.isArray(normalized.primary) ? normalized.primary : [],
        mobile: Array.isArray(normalized.mobile) ? normalized.mobile : [],
    };
}
