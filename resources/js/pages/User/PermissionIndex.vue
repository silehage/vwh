<script setup>
import { toggle } from '@/routes/permissions';
import { useForm } from '@inertiajs/vue3';
import { computed, ref } from 'vue'

const props = defineProps(['roles', 'permissions', 'modules'])

const form = useForm({
   permission_id: '',
   role_id: ''
})
const handleToggle = (permission_id, role_id) => {

   form.permission_id = permission_id
   form.role_id = role_id

   form.post(toggle(), {
      preserveScroll: true,
      onFinish: () => form.reset()
   })

   console.log(form);

}

const filter = ref('')

const roleOptions = ref(props.roles)

const filtered_data = computed(() => {

   if (filter.value) {
      return props.permissions.filter(el => el.tag == filter.value)
   }

   return props.permissions
})

</script>


<template>
   <div>
      <AppHeader title="Assign Permissions"></AppHeader>

      <q-card class="section q-mt-md" flat>
         <q-card-section>
            <div class="q-mb-md q-gutter-sm">
               <q-btn unelevated size="13px" :color="filter == '' ? 'blue' : 'grey-6'" @click="filter = ''">ALL</q-btn>
               <q-btn v-for="module in modules" :key="module" unelevated size="13px"
                  :color="filter == module ? 'blue' : 'grey-6'" @click="filter = module">{{ module }}</q-btn>
            </div>
            <TableContainer>
               <thead>
                  <tr>
                     <th align="left">#</th>
                     <th align="left">Permissions</th>
                     <th align="left" v-for="item in roles" :key="item.id">{{ item.name }}</th>
                  </tr>
               </thead>
               <tbody>
                  <tr v-for="(item, index) in filtered_data" :key="index">
                     <td>{{ index + 1 }}</td>
                     <td>
                        <div class="nowrap">
                           {{ item.name }}
                        </div>
                     </td>
                     <td v-for="(role, roleIndex) in roleOptions" :key="roleIndex">
                        <q-toggle v-model="roleOptions[roleIndex].permissions" :val="item.id"
                           @update:model-value="() => handleToggle(item.id, role.id)"></q-toggle>
                     </td>
                  </tr>
               </tbody>
            </TableContainer>
         </q-card-section>
      </q-card>

   </div>
</template>