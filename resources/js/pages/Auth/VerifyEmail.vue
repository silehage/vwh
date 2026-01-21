<script setup lang="ts">
import { Form, Head } from '@inertiajs/vue3';

import InputError from '@/components/InputError.vue';
import Input from '@/components/Input.vue';
import TextLink from '@/components/TextLink.vue';
import { logout } from '@/routes';
import { send } from '@/routes/verification';

defineProps<{
    status?: string;
}>();
</script>

<template>

        <Head title="Email verification" />

        <div v-if="status === 'verification-link-sent'" class="q-mb-md text-center text-green">
            A new verification link has been sent to the email address you
            provided during registration.
        </div>

        <q-card class="card-md section">
            <q-card-section>
                <div class="card-title">
                    <div>Verify email</div>
                </div>
                <div class="card-caption">
                    Please verify your email address by clicking on the link we just emailed to you.
                </div>
                <Form v-bind="send.form()" class="q-gutter-y-md text-center" v-slot="{ processing }">
                    <Button :disabled="processing" variant="secondary">
                        <Spinner v-if="processing" />
                        Resend verification email
                    </Button>
        
                    <TextLink :href="logout()" as="button" class="mx-auto block">
                        Log out
                    </TextLink>
                </Form>
            </q-card-section>
        </q-card>


</template>
