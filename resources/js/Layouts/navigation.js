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

export function normalizeNavigation(sharedNavigation = {}) {
    return {
        primary: normalizeNavigationItems(sharedNavigation?.primary),
        mobile: normalizeNavigationItems(sharedNavigation?.mobile),
    };
}
