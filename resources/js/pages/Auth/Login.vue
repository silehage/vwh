<script setup lang="ts">
import { Form, Head } from '@inertiajs/vue3';
import TextLink from '@/components/TextLink.vue';
import InputError from '@/components/InputError.vue';
import { register } from '@/routes';
import { store } from '@/routes/login';
import { request } from '@/routes/password';
import Input from '@/components/Input.vue';
import Checkbox from '@/components/Checkbox.vue';
defineProps<{
    canResetPassword: boolean;
    canRegister: boolean;
}>();

</script>

<template>

    <Head title="Login" />

    <q-card class="card-md section">
        <q-card-section>

            <div class="card-title">Login</div>

           <div class="q-gutter-y-sm">

                <Form v-bind="store.form()" :reset-on-success="['password']" v-slot="{ errors, processing }">
                    <div class="q-gutter-y-sm">
                        <div>
                            <Input name="email" id="email" label="Email Address" required></Input>
                            <InputError :message="errors.email" />
                        </div>

                        <div class="grid gap-2">
                            <Input id="password" type="password" name="password" required :tabindex="2"
                                autocomplete="current-password" placeholder="Password" />
                            <InputError :message="errors.password" />
                        </div>

                        <div class="flex items-center justify-between">
                            <Checkbox id="remember" name="remember" :tabindex="3" label="Remember Me" />
                              <TextLink :href="request()" :tabindex="6">Forgot Password?</TextLink>
                        </div>

                        <q-btn type="submit" class="q-mt-md full-width" color="primary" :tabindex="4"
                            :disable="processing" data-test="login-button" label="Login">
                        </q-btn>
                    </div>

                </Form>
                <div class="text-center">
                    Belum punya akun?
                    <TextLink :href="register()" :tabindex="6">Register</TextLink>
                </div>
            </div>
        </q-card-section>
    </q-card>


</template>
