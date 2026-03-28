import test from 'node:test';
import assert from 'node:assert/strict';

import { normalizeNavigation } from '../../resources/js/Layouts/navigation.js';

test('normalizeNavigation returns empty arrays for missing or malformed input', () => {
    assert.deepStrictEqual(normalizeNavigation(), {
        primary: [],
        mobile: [],
    });

    assert.deepStrictEqual(normalizeNavigation(null), {
        primary: [],
        mobile: [],
    });

    assert.deepStrictEqual(normalizeNavigation({
        primary: null,
        mobile: 'invalid',
    }), {
        primary: [],
        mobile: [],
    });
});

test('normalizeNavigation preserves valid navigation arrays', () => {
    const navigation = {
        primary: [
            { label: 'Overview', route: 'dashboard', active: 'dashboard' },
        ],
        mobile: [
            { label: 'Overview', route: 'dashboard', active: 'dashboard' },
        ],
    };

    assert.deepStrictEqual(normalizeNavigation(navigation), navigation);
});

test('normalizeNavigation removes malformed items', () => {
    assert.deepStrictEqual(
        normalizeNavigation({
            primary: [
                { label: 'Overview', route: 'dashboard', active: 'dashboard' },
                { label: '', route: 'products.index', active: 'products.*' },
                { label: 'Orders', route: null, active: 'orders.*' },
                { label: 'Settings', route: 'settings.index', active: '' },
                null,
            ],
            mobile: [
                undefined,
                { label: 'Listings', route: 'listings.index', active: 'listings.*' },
            ],
        }),
        {
            primary: [
                { label: 'Overview', route: 'dashboard', active: 'dashboard' },
            ],
            mobile: [
                { label: 'Listings', route: 'listings.index', active: 'listings.*' },
            ],
        }
    );
});
