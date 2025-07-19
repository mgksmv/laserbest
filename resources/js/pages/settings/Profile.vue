<script setup lang="ts">
import { Head, useForm, usePage } from '@inertiajs/vue3';
import { type BreadcrumbItem, type User } from '@/types';
import Button from 'primevue/button';
import InputText from 'primevue/inputtext';
import Message from 'primevue/message';
import HeadingSmall from '@/components/HeadingSmall.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import SettingsLayout from '@/layouts/settings/Layout.vue';
import FormLabel from '@/components/form/FormLabel.vue';

interface Props {
    mustVerifyEmail: boolean;
    status?: string;
}

defineProps<Props>();

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Профиль',
        href: '/settings/profile',
    },
];

const page = usePage();
const user = page.props.auth.user as User;

const form = useForm({
    name: user.name,
    email: user.email,
});

const submit = () => {
    form.patch(route('profile.update'), {
        preserveScroll: true,
    });
};
</script>

<template>
    <AppLayout :breadcrumbs="breadcrumbs">
        <Head title="Профиль" />

        <SettingsLayout>
            <div class="flex flex-col space-y-6">
                <HeadingSmall
                    title="Профиль"
                    description="Изменение данных профиля"
                />

                <form
                    class="space-y-6"
                    @submit.prevent="submit"
                >
                    <div class="grid gap-2">
                        <FormLabel
                            for-id="name"
                            required
                        >
                            Имя
                        </FormLabel>
                        <InputText
                            id="name"
                            v-model="form.name"
                            :invalid="Boolean(form.errors?.name)"
                            type="text"
                            autocomplete="name"
                            placeholder="Введите имя"
                            required
                        />
                        <Message
                            v-if="form.errors?.name"
                            severity="error"
                            variant="simple"
                            size="small"
                        >
                            {{ form.errors?.name }}
                        </Message>
                    </div>

                    <div class="grid gap-2">
                        <FormLabel
                            for-id="email"
                            required
                        >
                            Электронная почта
                        </FormLabel>
                        <InputText
                            id="email"
                            v-model="form.email"
                            :invalid="Boolean(form.errors?.email)"
                            type="email"
                            autocomplete="username"
                            required
                            fluid
                        />
                        <Message
                            v-if="form.errors?.email"
                            severity="error"
                            variant="simple"
                            size="small"
                        >
                            {{ form.errors?.email }}
                        </Message>
                    </div>

                    <!--<div v-if="mustVerifyEmail && !user.email_verified_at">
                        <p class="-mt-4 text-sm text-muted-foreground">
                            Your email address is unverified.
                            <Link
                                :href="route('verification.send')"
                                method="post"
                                as="button"
                                class="text-foreground underline decoration-neutral-300 underline-offset-4 transition-colors duration-300 ease-out hover:decoration-current! dark:decoration-neutral-500"
                            >
                                Click here to resend the verification email.
                            </Link>
                        </p>

                        <div v-if="status === 'verification-link-sent'" class="mt-2 text-sm font-medium text-green-600">
                            A new verification link has been sent to your email address.
                        </div>
                    </div>-->

                    <div class="flex items-center gap-4">
                        <Button
                            :disabled="form.processing"
                            type="submit"
                        >
                            Сохранить
                        </Button>

                        <Transition
                            enter-active-class="transition ease-in-out"
                            enter-from-class="opacity-0"
                            leave-active-class="transition ease-in-out"
                            leave-to-class="opacity-0"
                        >
                            <p
                                v-show="form.recentlySuccessful"
                                class="text-sm text-neutral-600"
                            >
                                Изменения сохранены.
                            </p>
                        </Transition>
                    </div>
                </form>
            </div>
        </SettingsLayout>
    </AppLayout>
</template>
