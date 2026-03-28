import { describe, expect, it, vi } from 'vitest';
import { mount } from '@vue/test-utils';

import DashboardCard from '@/Components/DashboardCard.vue';
import * as DashboardCardHelpers from '@/Components/DashboardCardHelpers.js';
import {
    getDashboardCardClasses,
    mergeDashboardCardClasses,
    shouldRenderDashboardCardTitle,
} from '@/Components/DashboardCardHelpers.js';

describe('getDashboardCardClasses', () => {
    it('uses stat variant classes by default', () => {
        expect(getDashboardCardClasses()).toEqual({
            rootClasses: 'rounded-lg bg-blue-100 p-6 shadow-sm ring-1 ring-blue-200',
            bodyClasses: '',
            titleClasses: 'mb-2 text-sm text-gray-500',
            headingClasses: '',
        });
    });

    it('uses panel variant classes', () => {
        expect(getDashboardCardClasses('panel')).toEqual({
            rootClasses:
                'overflow-hidden bg-blue-100 shadow-sm ring-1 ring-blue-200 sm:rounded-lg',
            bodyClasses: 'divide-y divide-gray-100',
            titleClasses: 'border-b border-gray-200 px-6 py-4',
            headingClasses: 'text-base font-semibold text-gray-900',
        });
    });
});

describe('mergeDashboardCardClasses', () => {
    it('falls back to base when partial is null or not an object', () => {
        const base = getDashboardCardClasses('stat');
        expect(mergeDashboardCardClasses(null, 'stat')).toEqual(base);
        expect(mergeDashboardCardClasses(undefined, 'stat')).toEqual(base);
        expect(mergeDashboardCardClasses('x', 'stat')).toEqual(base);
    });

    it('fills missing keys from defaults', () => {
        expect(mergeDashboardCardClasses({ rootClasses: 'custom-root' }, 'stat')).toEqual({
            rootClasses: 'custom-root',
            bodyClasses: '',
            titleClasses: 'mb-2 text-sm text-gray-500',
            headingClasses: '',
        });
    });
});

describe('shouldRenderDashboardCardTitle', () => {
    it('renders only for non-empty values', () => {
        expect(shouldRenderDashboardCardTitle('0')).toBe(true);
        expect(shouldRenderDashboardCardTitle('Title')).toBe(true);
        expect(shouldRenderDashboardCardTitle('')).toBe(false);
        expect(shouldRenderDashboardCardTitle(null)).toBe(false);
        expect(shouldRenderDashboardCardTitle(undefined)).toBe(false);
    });
});

describe('DashboardCard', () => {
    it('renders with safe defaults when helper throws', () => {
        const spy = vi
            .spyOn(DashboardCardHelpers, 'getDashboardCardClasses')
            .mockImplementation(() => {
                throw new Error('bad helper');
            });

        const wrapper = mount(DashboardCard, {
            props: { title: 'Stats', variant: 'stat' },
        });

        expect(wrapper.find('.rounded-lg').exists()).toBe(true);
        expect(wrapper.text()).toContain('Stats');

        spy.mockRestore();
    });

    it('renders when helper returns a partial object', () => {
        const spy = vi
            .spyOn(DashboardCardHelpers, 'getDashboardCardClasses')
            .mockReturnValue({ rootClasses: 'only-root' });

        const wrapper = mount(DashboardCard, {
            props: { title: 'Panel', variant: 'panel' },
        });

        expect(wrapper.find('.only-root').exists()).toBe(true);
        expect(wrapper.find('.divide-y').exists()).toBe(true);

        spy.mockRestore();
    });
});
