<script setup lang="ts">
import { Form, Head } from '@inertiajs/vue3';

import InputError from '@/components/InputError.vue';
import Input from '@/components/Input.vue';
import TextLink from '@/components/TextLink.vue';
import { store } from '@/routes/password/comfirm';

</script>

<template>

        <Head title="Confirm password" />

        <q-card class="card-md section">
            <q-card-section>
                <div class="card-title">
                    <div>Confirm your password</div>
                </div>
                <div class="card-caption">
                   This is a secure area of the application. Please confirm your password before continuing.
                </div>
                <Form v-bind="store.form()" reset-on-success v-slot="{ errors, processing }">
                    <div class="space-y-6">
                        <div class="grid gap-2">
                            <Input label="Password" id="password" type="password" name="password" required
                                autocomplete="current-password" autofocus />
        
                            <InputError :message="errors.password" />
                        </div>
        
                        <div class="flex items-center">
                            <q-btn class="full-width" :loading="processing" data-test="confirm-password-button">
                                <Spinner v-if="processing" />
                                Confirm Password
                            </q-btn>
                        </div>
                    </div>
                </Form>
            </q-card-section>
        </q-card>

</template>
