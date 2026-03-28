import { describe, expect, it } from 'vitest';

import {
    getLayoutNavigation,
    normalizeNavigation,
} from '@/Layouts/navigation.js';

describe('normalizeNavigation', () => {
    it('returns empty arrays for missing or malformed input', () => {
        expect(normalizeNavigation()).toEqual({
            primary: [],
            mobile: [],
        });

        expect(normalizeNavigation(null)).toEqual({
            primary: [],
            mobile: [],
        });

        expect(
            normalizeNavigation({
                primary: null,
                mobile: 'invalid',
            }),
        ).toEqual({
            primary: [],
            mobile: [],
        });
    });

    it('preserves valid navigation arrays', () => {
        const navigation = {
            primary: [
                { label: 'Overview', route: 'dashboard', active: 'dashboard' },
            ],
            mobile: [
                { label: 'Overview', route: 'dashboard', active: 'dashboard' },
            ],
        };

        expect(normalizeNavigation(navigation)).toEqual(navigation);
    });

    it('removes malformed items', () => {
        expect(
            normalizeNavigation({
                primary: [
                    {
                        label: 'Overview',
                        route: 'dashboard',
                        active: 'dashboard',
                    },
                    { label: '', route: 'products.index', active: 'products.*' },
                    { label: 'Orders', route: null, active: 'orders.*' },
                    { label: 'Settings', route: 'settings.index', active: '' },
                    null,
                ],
                mobile: [
                    undefined,
                    {
                        label: 'Listings',
                        route: 'listings.index',
                        active: 'listings.*',
                    },
                ],
            }),
        ).toEqual({
            primary: [
                { label: 'Overview', route: 'dashboard', active: 'dashboard' },
            ],
            mobile: [
                { label: 'Listings', route: 'listings.index', active: 'listings.*' },
            ],
        });
    });
});

describe('getLayoutNavigation', () => {
    it('always returns primary and mobile arrays', () => {
        expect(getLayoutNavigation(undefined)).toEqual({
            primary: [],
            mobile: [],
        });
        expect(getLayoutNavigation(null)).toEqual({
            primary: [],
            mobile: [],
        });
        expect(getLayoutNavigation([])).toEqual({
            primary: [],
            mobile: [],
        });
    });

    it('matches normalizeNavigation for valid payloads', () => {
        const payload = {
            primary: [
                { label: 'Orders', route: 'orders.index', active: 'orders.*' },
            ],
            mobile: [
                { label: 'Orders', route: 'orders.index', active: 'orders.*' },
            ],
        };
        expect(getLayoutNavigation(payload)).toEqual(normalizeNavigation(payload));
    });
});
