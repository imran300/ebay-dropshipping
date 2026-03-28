import test from 'node:test';
import assert from 'node:assert/strict';

import {
    getDashboardCardClasses,
    shouldRenderDashboardCardTitle,
} from '../../resources/js/Components/dashboardCard.js';

test('dashboard card uses stat variant classes by default', () => {
    assert.deepStrictEqual(getDashboardCardClasses(), {
        rootClasses: 'rounded-lg bg-blue-100 p-6 shadow-sm ring-1 ring-blue-200',
        bodyClasses: '',
        titleClasses: 'mb-2 text-sm text-gray-500',
        headingClasses: '',
    });
});

test('dashboard card uses panel variant classes', () => {
    assert.deepStrictEqual(getDashboardCardClasses('panel'), {
        rootClasses: 'overflow-hidden bg-blue-100 shadow-sm ring-1 ring-blue-200 sm:rounded-lg',
        bodyClasses: 'divide-y divide-gray-100',
        titleClasses: 'border-b border-gray-200 px-6 py-4',
        headingClasses: 'text-base font-semibold text-gray-900',
    });
});

test('dashboard card title renders only for non-empty values', () => {
    assert.equal(shouldRenderDashboardCardTitle('0'), true);
    assert.equal(shouldRenderDashboardCardTitle('Title'), true);
    assert.equal(shouldRenderDashboardCardTitle(''), false);
    assert.equal(shouldRenderDashboardCardTitle(null), false);
    assert.equal(shouldRenderDashboardCardTitle(undefined), false);
});
