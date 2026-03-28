import { describe, expect, it, vi } from 'vitest';
import { mount } from '@vue/test-utils';

const { usePageMock } = vi.hoisted(() => ({
    usePageMock: vi.fn(),
}));

vi.mock('@inertiajs/vue3', async (importOriginal) => {
    const actual = await importOriginal();
    return {
        ...actual,
        usePage: () => usePageMock(),
        Link: {
            name: 'InertiaLink',
            props: ['href'],
            template: '<a class="inertia-link" :href="href"><slot /></a>',
        },
    };
});

import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

describe('AuthenticatedLayout', () => {
    it('renders primary nav items from page props via getLayoutNavigation', () => {
        usePageMock.mockReturnValue({
            props: {
                auth: {
                    user: { name: 'Sam', email: 'sam@example.com' },
                },
                navigation: {
                    primary: [
                        {
                            label: 'Overview',
                            route: 'dashboard',
                            active: 'dashboard',
                        },
                        {
                            label: 'Products',
                            route: 'products.index',
                            active: 'products.*',
                        },
                    ],
                    mobile: [
                        {
                            label: 'Overview',
                            route: 'dashboard',
                            active: 'dashboard',
                        },
                    ],
                },
            },
        });

        const wrapper = mount(AuthenticatedLayout, {
            slots: {
                default: '<p class="page-body">Body</p>',
            },
            global: {
                stubs: {
                    ApplicationLogo: true,
                    AuthenticatedUserMenu: {
                        props: ['user', 'mobile'],
                        template:
                            '<div class="user-menu-stub" data-mobile="mobile">{{ user.name }}</div>',
                    },
                    NavLink: {
                        props: ['href', 'active'],
                        template: '<a class="nav-link-stub" :href="href"><slot /></a>',
                    },
                    ResponsiveNavLink: {
                        props: ['href', 'active'],
                        template:
                            '<a class="resp-nav-stub" :href="href"><slot /></a>',
                    },
                },
            },
        });

        const navLinks = wrapper.findAll('.nav-link-stub');
        expect(navLinks).toHaveLength(2);
        expect(navLinks[0].text()).toBe('Overview');
        expect(navLinks[1].text()).toBe('Products');

        expect(wrapper.find('.page-body').exists()).toBe(true);
        expect(wrapper.find('.user-menu-stub').exists()).toBe(true);
    });

    it('degrades to empty nav when navigation prop is missing', () => {
        usePageMock.mockReturnValue({
            props: {
                auth: { user: { name: 'X', email: 'x@y.z' } },
            },
        });

        const wrapper = mount(AuthenticatedLayout, {
            slots: { default: 'x' },
            global: {
                stubs: {
                    ApplicationLogo: true,
                    AuthenticatedUserMenu: true,
                    NavLink: {
                        template: '<a class="nav-link-stub"><slot /></a>',
                    },
                    ResponsiveNavLink: {
                        template: '<a class="resp-nav-stub"><slot /></a>',
                    },
                },
            },
        });

        expect(wrapper.findAll('.nav-link-stub')).toHaveLength(0);
    });
});
