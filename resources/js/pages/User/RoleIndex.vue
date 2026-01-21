<script setup>
import { can } from '@/lib/utils';
import roles from '@/routes/roles';
import { useForm, usePage } from '@inertiajs/vue3';
import { computed, ref } from 'vue'

const data = usePage().props.data

const form = useForm({
   name: '',
})


const modal = ref(false)
const editSelected = ref(null)

const submitData = () => {
   if (editSelected.value) {
      form.put(roles.update(editSelected.value.id).url, {
         preserveState: false
      })
   }
   form.post(roles.store().url, {
      preserveState: false
   })
}

const handleAddRole = () => {
   form.name = ''
   modal.value = true
}
const handleEdit = (item) => {
   form.name = item.name
   editSelected.value = item
   modal.value = true
}



</script>


<template>
   <div>
      <AppHeader title="Roles">
          <q-btn color="primary" @click="handleAddRole">Add Role</q-btn>
      </AppHeader>

      <q-card class="section q-mt-md" flat>
         <q-card-section>
            <div class="table-responsive">
               <table class="table bordered">
                  <thead>
                     <tr>
                        <th align="left">#</th>
                        <th align="left">Name</th>
                        <th align="left">Action</th>
                     </tr>
                  </thead>
                  <tbody>
                     <tr v-for="(item, index) in data" :key="index">
                        <td>{{ index + 1 }}</td>
                        <td>
                           <div class="nowrap">
                              {{ item.name }}
                           </div>
                        </td>
                        <td class="q-gutter-xs no-wrap">
                           <q-btn v-if="can('update-role')" class="btn-action" no-caps color="blue"
                              @click="handleEdit(item)">Edit</q-btn color="blue">
                        </td>
                     </tr>
                  </tbody>
               </table>
            </div>
         </q-card-section>
      </q-card>
      <q-dialog v-model="modal">
         <q-card class="card-md section">
            <q-card-section>
               <div class="card-title flex justify-between items-center">
                  <div>Form Role</div>
                  <q-btn icon="close" flat round v-close-popup></q-btn>
               </div>
               <q-form @submit="submitData">
                  <q-input v-model="form.name" label="Name" required></q-input>
                  <div class="card-action">
                     <q-btn type="submit" class="full-width" color="primary" label="Submit Data"></q-btn>
                  </div>
               </q-form>
            </q-card-section>
         </q-card>
      </q-dialog>
   </div>
</template>