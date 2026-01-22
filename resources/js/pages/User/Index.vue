<script setup>
import Input from '@/components/Input.vue';
import InputError from '@/components/InputError.vue';
import { store, update } from '@/routes/users';
import { computed, ref } from 'vue';
import { Form } from '@inertiajs/vue3';

defineProps(['data', 'roles'])

const modal = ref(false)
const selectedEdit = ref(null)
const isUpdatePassword = ref(false)

const handleAdd = () => {
   selectedEdit.value = null
   isUpdatePassword.value = true
   modal.value = true
}

const handleEdit = (user) => {
   selectedEdit.value = user
   isUpdatePassword.value = false

   modal.value = true
}

import { can } from '@/lib/utils';
import Select from '@/components/Select.vue';
const module = 'User'

const handleFinish = () => {
   modal.value = false
}

const formAction = computed(() => {
   if (selectedEdit.value) {
      return update.form(selectedEdit.value.id)
   }
   return store.form()
})

</script>

<template>

   <AppHeader title="Users">
      <div class="flex justify-end q-gutter-sm">
         <q-btn v-if="can('Create')" color="primary" @click="handleAdd" label="Add User"></q-btn>
      </div>
   </AppHeader>

   <q-card class="section">
      <q-card-section>

         <div>
            <!-- <AppPagination v-bind="data"></AppPagination> -->
            <TableContainer>
               <thead>
                  <tr>
                     <th class="text-left uppercase">#</th>
                     <th class="text-left uppercase">Nama</th>
                     <th class="text-left uppercase">Email</th>
                     <th class="text-left uppercase">Role</th>
                     <th class="text-left uppercase" v-if="can('Update')">Aksi</th>
                  </tr>
               </thead>
               <tbody>
                  <tr v-for="(item, idx) in data.data" :key="idx">
                     <td>{{ data.from + idx }}</td>
                     <td>{{ item.name }}</td>
                     <td>{{ item.email }}</td>
                     <td>{{ item.role ? item.role.name : '-' }}</td>
                     <td class="q-gutter-xs no-wrap">
                        <q-btn v-if="can('Update')" class="btn-action" no-caps color="blue"
                           @click="handleEdit(item)">Edit</q-btn color="blue">
                     </td>
                  </tr>
               </tbody>
            </TableContainer>
            <div v-if="!data.total" class="q-pa-sm">No items found</div>

            <AppPagination v-bind="data"></AppPagination>

         </div>
      </q-card-section>
   </q-card>

   <q-dialog v-model="modal">
      <q-card class="section card-lg">
         <q-card-section>
            <div class="card-title flex justify-between items-center">
               <div>Form User</div>
               <q-btn flat round icon="close" v-close-popup></q-btn>
            </div>
            <Form v-bind="formAction" v-slot="{ errors, processing }" autocomplete="off" @success="handleFinish">
               <div class="q-gutter-sm">
                  <div>
                     <Input :defaultValue="selectedEdit ? selectedEdit.name : ''" label="Nama" id="name" name="name"
                        autocomplete="off"></Input>
                     <InputError :message="errors.name" />
                  </div>
                  <div>
                     <Input :defaultValue="selectedEdit ? selectedEdit.email : ''" label="Email" id="email" name="email"
                        type="email" autocomplete="off"></Input>
                     <InputError :message="errors.email" />
                  </div>
                  <div>
                     <Select :defaultValue="selectedEdit ? selectedEdit.role_id : ''" label="Role" id="role" name="role"
                        :options="roles" emit-value map-options></Select>
                     <InputError :message="errors.role" />
                  </div>
                  <div>
                     <q-btn padding="1px 4px" flat no-caps color="primary" class="q-my-xs" label="Edit Password"
                        @click="isUpdatePassword = !isUpdatePassword" v-if="!isUpdatePassword"></q-btn>
                  </div>
                  <template v-if="isUpdatePassword">
                     <Input label="Password" id="password" name="password" autocomplete="new-password"></Input>
                     <div>
                        <Input label="Ulangi Password" id="password_confirmation" name="password_confirmation"
                           type="password_confirmation" autocomplete=""></Input>
                        <InputError :message="errors.password_confirmation" />
                     </div>

                  </template>
               </div>

               <q-btn :loading="processing" label="Submit" type="submit" color="primary"
                  class="full-width q-mt-lg"></q-btn>

            </Form>
         </q-card-section>
      </q-card>
   </q-dialog>

</template>
