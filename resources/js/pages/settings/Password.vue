<script setup lang="ts">
import { ref } from 'vue';
import { Head, useForm } from '@inertiajs/vue3';
import AppLayout from '@/layouts/AppLayout.vue';
import SettingsLayout from '@/layouts/settings/Layout.vue';
import HeadingSmall from '@/components/HeadingSmall.vue';
import { type BreadcrumbItem } from '@/types';
import FormLabel from '@/components/form/FormLabel.vue';
import Button from 'primevue/button';
import InputText from 'primevue/inputtext';
import Message from 'primevue/message';

const breadcrumbItems: BreadcrumbItem[] = [
    {
        title: 'Пароль',
        href: '/settings/password',
    },
];

const passwordInput = ref<HTMLInputElement | null>(null);
const currentPasswordInput = ref<HTMLInputElement | null>(null);

const form = useForm({
    current_password: '',
    password: '',
    password_confirmation: '',
});

const updatePassword = () => {
    form.put(route('password.update'), {
        preserveScroll: true,
        onSuccess: () => form.reset(),
        onError: (errors: any) => {
            if (errors.password) {
                form.reset('password', 'password_confirmation');
                if (passwordInput.value instanceof HTMLInputElement) {
                    passwordInput.value.focus();
                }
            }

            if (errors.current_password) {
                form.reset('current_password');
                if (currentPasswordInput.value instanceof HTMLInputElement) {
                    currentPasswordInput.value.focus();
                }
            }
        },
    });
};
</script>

<template>
    <AppLayout :breadcrumbs="breadcrumbItems">
        <Head title="Пароль" />

        <SettingsLayout>
            <div class="space-y-6">
                <HeadingSmall
                    title="Изменить пароль"
                    description="Убедитесь, что Вы используете длинный и надёжный пароль"
                />

                <form @submit.prevent="updatePassword" class="space-y-6">
                    <div class="grid gap-2">
                        <FormLabel for-id="current_password" required>Текущий пароль</FormLabel>
                        <InputText
                            id="current_password"
                            ref="currentPasswordInput"
                            v-model="form.current_password"
                            type="password"
                            autocomplete="current-password"
                            placeholder="Введите текущий пароль"
                            required
                        />
                        <Message
                            v-if="form.errors?.current_password"
                            severity="error"
                            variant="simple"
                            size="small"
                        >
                            {{ form.errors?.current_password }}
                        </Message>
                    </div>

                    <div class="grid gap-2">
                        <FormLabel for-id="password" required>Новый пароль</FormLabel>
                        <InputText
                            id="password"
                            ref="passwordInput"
                            v-model="form.password"
                            type="password"
                            autocomplete="new-password"
                            placeholder="Введите новый пароль"
                            required
                        />
                        <Message
                            v-if="form.errors?.password"
                            severity="error"
                            variant="simple"
                            size="small"
                        >
                            {{ form.errors?.password }}
                        </Message>
                    </div>

                    <div class="grid gap-2">
                        <FormLabel for-id="password_confirmation" required>Подтвердите пароль</FormLabel>
                        <InputText
                            id="password_confirmation"
                            v-model="form.password_confirmation"
                            type="password"
                            autocomplete="new-password"
                            placeholder="Введите новый пароль ещё раз"
                            required
                        />
                        <Message
                            v-if="form.errors?.password_confirmation"
                            severity="error"
                            variant="simple"
                            size="small"
                        >
                            {{ form.errors?.password_confirmation }}
                        </Message>
                    </div>

                    <div class="flex items-center gap-4">
                        <Button :disabled="form.processing" type="submit">Сохранить</Button>

                        <Transition
                            enter-active-class="transition ease-in-out"
                            enter-from-class="opacity-0"
                            leave-active-class="transition ease-in-out"
                            leave-to-class="opacity-0"
                        >
                            <p v-show="form.recentlySuccessful" class="text-sm text-neutral-600">Saved.</p>
                        </Transition>
                    </div>
                </form>
            </div>
        </SettingsLayout>
    </AppLayout>
</template>
