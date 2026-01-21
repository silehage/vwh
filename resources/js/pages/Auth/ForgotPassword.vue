<script setup lang="ts">
import { Form, Head } from '@inertiajs/vue3';

import InputError from '@/components/InputError.vue';
import Input from '@/components/Input.vue';
import TextLink from '@/components/TextLink.vue';
import { login } from '@/routes';
import { email } from '@/routes/password';

defineProps<{
    status?: string;
}>();
</script>

<template>

    <Head title="Forgot password" />

    <q-card class="card-md section">
        <q-card-section>
            <div class="card-title">
                <div>Forgot Password</div>
            </div>
            <div class="card-caption">
                Forgot your password? No problem. Just let us know your email address and we will email you a
                password reset
                link that will allow you to choose a new one.
            </div>
            <div class="q-gutter-y-sm">
                <Form v-bind="email.form()" v-slot="{ errors, processing }">
                    <div class="grid gap-2">
                        <Input label="Email address" id="email" type="email" name="email" autocomplete="off" autofocus
                            placeholder="email@example.com" />
                        <InputError :message="errors.email" />
                    </div>

                    <div class="q-mt-md">
                        <q-btn class="full-width" color="primary" :disabled="processing"
                            data-test="email-password-reset-link-button">
                            Email password reset link
                        </q-btn>
                    </div>
                </Form>

                <div class="q-gutter-x-sm text-center">
                    <TextLink :href="login()">Back to log in</TextLink>
                </div>
            </div>
        </q-card-section>
    </q-card>

</template>
