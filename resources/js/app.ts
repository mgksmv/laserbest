import '../css/app.css';
import '../scss/app.scss';

import { createInertiaApp, router } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import type { DefineComponent } from 'vue';
import { createApp, h } from 'vue';
import { ZiggyVue } from 'ziggy-js';
import { initializeTheme } from './composables/useAppearance';

import PrimeVue from 'primevue/config';
import ToastService from 'primevue/toastservice';
import { useToast } from 'primevue/usetoast';
import Toast from 'primevue/toast';
import themePreset from '@/theme/noir-preset';
import globalPt from '@/theme/global-pt';

const appName = import.meta.env.VITE_APP_NAME || 'Laravel';

createInertiaApp({
    title: (title) => (title ? `${title} - ${appName}` : appName),
    resolve: (name) => resolvePageComponent(`./pages/${name}.vue`, import.meta.glob<DefineComponent>('./pages/**/*.vue')),
    setup({ el, App, props, plugin }) {
        // Global Toast component
        const Root = {
            setup() {
                // show error toast instead of standard Inertia modal response
                const toast = useToast();
                router.on('invalid', (event) => {
                    const responseBody = event.detail.response?.data;
                    if (responseBody?.error_summary && responseBody?.error_detail) {
                        event.preventDefault();
                        toast.add({
                            severity: event.detail.response?.status >= 500 ? 'error' : 'warn',
                            summary: responseBody.error_summary,
                            detail: responseBody.error_detail,
                            life: 5000,
                        });
                    }
                });

                return () => h('div', [
                    h(App, props),
                    h(Toast, { position: 'bottom-right' }),
                ]);
            },
        };

        createApp(Root)
            .use(plugin)
            .use(ZiggyVue)
            .use(PrimeVue, {
                theme: {
                    preset: themePreset,
                    options: {
                        darkModeSelector: '.dark',
                    },
                },
                pt: globalPt,
                locale: {
                    dayNames: ['Воскресенье', 'Понедельник', 'Вторник', 'Среда', 'Четверг', 'Пятница', 'Суббота'],
                    dayNamesShort: ['Вс', 'Пн', 'Вт', 'Ср', 'Чт', 'Пт', 'Сб'],
                    dayNamesMin: ['Вс', 'Пн', 'Вт', 'Ср', 'Чт', 'Пт', 'Сб'],
                    monthNames: [
                        'Январь',
                        'Февраль',
                        'Март',
                        'Апрель',
                        'Май',
                        'Июнь',
                        'Июль',
                        'Август',
                        'Сентябрь',
                        'Октябрь',
                        'Ноябрь',
                        'Декабрь',
                    ],
                    monthNamesShort: [
                        'Янв',
                        'Фев',
                        'Мар',
                        'Апр',
                        'Май',
                        'Июн',
                        'Июл',
                        'Авг',
                        'Сен',
                        'Окт',
                        'Ноя',
                        'Дек',
                    ],
                    today: 'Сегодня',
                    weekHeader: 'Нед',
                    firstDayOfWeek: 1,
                    dateFormat: 'dd/mm/yy',
                },
            })
            .use(ToastService)
            .mount(el);
    },
    progress: {
        color: '#4B5563',
    },
});

// This will set light / dark mode on page load...
initializeTheme();
