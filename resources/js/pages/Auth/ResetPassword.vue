<script setup lang="ts">
import { Form, Head } from '@inertiajs/vue3';
import { ref } from 'vue';

import InputError from '@/components/InputError.vue';
import Input from '@/components/Input.vue';
import { update } from '@/routes/password';

const props = defineProps<{
    token: string;
    email: string;
}>();

const inputEmail = ref(props.email);
</script>

<template>

    <Head title="Reset password" />

    <q-card class="card-md section">
        <q-card-section>
            <div class="card-title">
                <div>Reset password</div>
            </div>
            <div class="card-caption">
                Please enter your new password below
            </div>
            <Form v-bind="update.form()" :transform="(data) => ({ ...data, token, email })"
                :reset-on-success="['password', 'password_confirmation']" v-slot="{ errors, processing }">
                <div class="grid gap-6">
                    <div class="grid gap-2">
                        <Input label="Email" id="email" type="email" name="email" autocomplete="email"
                            v-model="inputEmail" class="mt-1 block w-full" readonly />
                        <InputError :message="errors.email" class="mt-2" />
                    </div>

                    <div class="grid gap-2">
                        <Input label="Password" id="password" type="password" name="password"
                            autocomplete="new-password" class="mt-1 block w-full" autofocus placeholder="Password" />
                        <InputError :message="errors.password" />
                    </div>

                    <div class="grid gap-2">
                        <Input label="Confirm Password" id="password_confirmation" type="password"
                            name="password_confirmation" autocomplete="new-password" class="mt-1 block w-full"
                            placeholder="Confirm password" />
                        <InputError :message="errors.password_confirmation" />
                    </div>

                    <q-btn type="submit" class="q-mt-md full-width" :loading="processing"
                        data-test="reset-password-button">
                        Reset password
                    </q-btn>
                </div>
            </Form>
        </q-card-section>
    </q-card>

</template>
