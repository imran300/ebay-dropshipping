export function normalizeNavigation(sharedNavigation) {
    return {
        primary: Array.isArray(sharedNavigation?.primary)
            ? sharedNavigation.primary
            : [],
        mobile: Array.isArray(sharedNavigation?.mobile)
            ? sharedNavigation.mobile
            : [],
    };
}
