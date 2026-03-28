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
