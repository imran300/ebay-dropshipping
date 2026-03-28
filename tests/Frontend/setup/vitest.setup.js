import { config } from '@vue/test-utils';
import { vi } from 'vitest';

const routeTable = {
    dashboard: '/dashboard',
    'products.index': '/products',
    'listings.index': '/listings',
    'orders.index': '/orders',
    'settings.index': '/settings',
    'profile.edit': '/profile',
    logout: '/logout',
};

/** Ziggy-style: `route('name')` and `route().current(pattern)` */
function routeFn(name, ..._rest) {
    if (arguments.length === 0) {
        return { current: (...args) => routeFn.current(...args) };
    }
    return routeTable[name] ?? `/${String(name)}`;
}

routeFn.current = vi.fn(() => false);

config.global.mocks = {
    route: routeFn,
};

globalThis.route = routeFn;
