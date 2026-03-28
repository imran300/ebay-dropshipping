import { describe, expect, it, vi } from 'vitest';
import { mount } from '@vue/test-utils';

import AuthenticatedUserMenu from '@/Components/AuthenticatedUserMenu.vue';

const DropdownLinkStub = {
    name: 'DropdownLink',
    inheritAttrs: true,
    props: ['href'],
    template:
        '<a class="dropdown-link-stub" :href="href" v-bind="$attrs"><slot /></a>',
};

const ResponsiveNavLinkStub = {
    name: 'ResponsiveNavLink',
    inheritAttrs: true,
    props: ['href'],
    template:
        '<a class="responsive-nav-stub" :href="href" v-bind="$attrs"><slot /></a>',
};

const DropdownAlwaysOpenStub = {
    name: 'Dropdown',
    template: `
        <div class="dropdown-stub">
            <div class="trigger-slot"><slot name="trigger" /></div>
            <div class="content-slot"><slot name="content" /></div>
        </div>
    `,
};

describe('AuthenticatedUserMenu', () => {
    it('desktop: shows user name in trigger (email is mobile-only in layout)', () => {
        const wrapper = mount(AuthenticatedUserMenu, {
            props: {
                user: { name: 'Jordan', email: 'jordan@example.com' },
                mobile: false,
            },
            global: {
                stubs: {
                    Dropdown: DropdownAlwaysOpenStub,
                    DropdownLink: DropdownLinkStub,
                },
            },
        });

        expect(wrapper.find('button').text()).toContain('Jordan');
        expect(wrapper.find('button').text()).not.toContain('jordan@example.com');
    });

    it('desktop: shows Profile and Log out with correct hrefs and logout uses POST', () => {
        const wrapper = mount(AuthenticatedUserMenu, {
            props: {
                user: { name: 'Alex', email: 'alex@example.com' },
                mobile: false,
            },
            global: {
                stubs: {
                    Dropdown: DropdownAlwaysOpenStub,
                    DropdownLink: DropdownLinkStub,
                },
            },
        });

        const links = wrapper.findAll('.dropdown-link-stub');
        const hrefs = links.map((w) => w.attributes('href'));
        expect(hrefs).toContain('/profile');
        expect(hrefs).toContain('/logout');

        const logout = links.find((w) => w.text().includes('Log Out'));
        expect(logout).toBeDefined();
        expect(logout.attributes('method')).toBe('post');
    });

    it('shows empty strings when user fields are missing', () => {
        const wrapper = mount(AuthenticatedUserMenu, {
            props: {
                user: {},
                mobile: true,
            },
            global: {
                stubs: { ResponsiveNavLink: ResponsiveNavLinkStub },
            },
        });

        expect(wrapper.find('.text-base').text().trim()).toBe('');
        expect(wrapper.find('.text-sm').text().trim()).toBe('');
    });

    it('mobile: displays name, email, and stubbed links with logout POST', () => {
        const wrapper = mount(AuthenticatedUserMenu, {
            props: {
                user: { name: 'Mobile User', email: 'm@example.com' },
                mobile: true,
            },
            global: {
                stubs: {
                    ResponsiveNavLink: ResponsiveNavLinkStub,
                },
            },
        });

        expect(wrapper.text()).toContain('Mobile User');
        expect(wrapper.text()).toContain('m@example.com');

        const links = wrapper.findAll('.responsive-nav-stub');
        const hrefs = links.map((w) => w.attributes('href'));
        expect(hrefs).toContain('/profile');
        expect(hrefs).toContain('/logout');

        const logout = links.find((w) => w.text().includes('Log Out'));
        expect(logout.attributes('method')).toBe('post');
    });

    it('desktop: opens dropdown and reveals menu items after trigger click', async () => {
        const wrapper = mount(AuthenticatedUserMenu, {
            props: {
                user: { name: 'Click', email: 'click@example.com' },
                mobile: false,
            },
            attachTo: document.body,
        });

        const flyout = wrapper.find(
            '.absolute.z-50.mt-2.rounded-md.shadow-lg',
        );
        expect(flyout.exists()).toBe(true);
        expect(getComputedStyle(flyout.element).display).toBe('none');

        await wrapper.find('button').trigger('click');

        expect(getComputedStyle(flyout.element).display).not.toBe('none');
        expect(flyout.text()).toContain('Profile');
        expect(flyout.text()).toContain('Log Out');

        wrapper.unmount();
    });
});
