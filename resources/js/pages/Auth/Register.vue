<script setup lang="ts">
import { Form, Head } from '@inertiajs/vue3';

import InputError from '@/components/InputError.vue';
import Input from '@/components/Input.vue';
import TextLink from '@/components/TextLink.vue';
import AuthBase from '@/layouts/AuthLayout.vue';
import { login } from '@/routes';
import { store } from '@/routes/register';
import Checkbox from '@/components/Checkbox.vue';
</script>

<template>

    <Head title="Register" />

    <q-card class="card-md section">
        <q-card-section>

            <div class="card-title">Register</div>

               <div class="q-gutter-y-sm">

                   <Form v-bind="store.form()" :reset-on-success="['password', 'password_confirmation']"
                       v-slot="{ errors, processing }" >
                       <div class="q-gutter-y-sm">
                           <div class="grid gap-2">
                               <Input label="Name" id="name" type="text" required autofocus :tabindex="1" autocomplete="name"
                                   name="name" placeholder="Full name" />
                               <InputError :message="errors.name" />
                           </div>
       
                           <div class="grid gap-2">
                               <Input label="Email" id="email" type="email" required :tabindex="2" autocomplete="email" name="email"
                                   placeholder="email@example.com" />
                               <InputError :message="errors.email" />
                           </div>
       
                           <div class="grid gap-2">
                               <Input label="Password" id="password" type="password" required :tabindex="3"
                                   autocomplete="new-password" name="password" placeholder="Password" />
                               <InputError :message="errors.password" />
                           </div>
       
                           <div class="grid gap-2">
                               <Input label="Confirm password" id="password_confirmation" type="password" required
                                   :tabindex="4" autocomplete="new-password" name="password_confirmation"
                                   placeholder="Confirm password" />
                               <InputError :message="errors.password_confirmation" />
                           </div>
       
                           <q-btn type="submit" class="q-mt-md full-width" color="primary" :tabindex="4" :disable="processing"
                               data-test="login-button" label="Create Account">
                           </q-btn>
                        </div>
                        
                    </Form>
                    <div class="text-center">
                        Already have an account?
                        <TextLink :href="login()" :tabindex="6">Log in</TextLink>
                    </div>
               </div>

        </q-card-section>
    </q-card>
</template>
