<script setup lang="ts">
import { Link, usePage } from '@inertiajs/vue3';
import { type NavItem } from '@/types';
import Heading from '@/components/Heading.vue';
import Button from 'primevue/button';
import Divider from 'primevue/divider';

const sidebarNavItems: NavItem[] = [
    {
        title: 'Профиль',
        href: '/settings/profile',
    },
    {
        title: 'Пароль',
        href: '/settings/password',
    },
    {
        title: 'Тема',
        href: '/settings/appearance',
    },
];

const page = usePage();

const currentPath = page.props.ziggy?.location ? new URL(page.props.ziggy.location).pathname : '';
</script>

<template>
    <div class="px-4 py-6">
        <Heading
            title="Настройки"
            description="Управление настройками аккаунта"
        />

        <div class="flex flex-col space-y-8 md:space-y-0 lg:flex-row lg:space-y-0 lg:space-x-12">
            <aside class="w-full md:max-w-2xl lg:w-48">
                <nav class="flex flex-col space-x-0 space-y-1">
                    <Button
                        v-for="item in sidebarNavItems"
                        :key="item.href"
                        :class="[
                            'w-full flex !justify-start p-panelmenu-item-link',
                            { '!bg-sidebar-accent': currentPath === item.href }
                        ]"
                        variant="text"
                        :href="item.href"
                        :as="Link"
                    >
                        {{ item.title }}
                    </Button>
                </nav>
            </aside>

            <Divider class="!my-6 md:!hidden" />

            <div class="flex-1 md:max-w-2xl">
                <section class="max-w-xl space-y-12">
                    <slot />
                </section>
            </div>
        </div>
    </div>
</template>
