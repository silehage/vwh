<script setup>
import { usePage } from '@inertiajs/vue3';
import { Notify } from 'quasar';
import { computed, watch } from 'vue';

const message = computed(() => usePage().props.flash.message)
const error = computed(() => usePage().props.flash.error)

watch(() => message.value, (val) => {
    if(val) {
      Notify.create({
         type: 'positive',
         message: val,
         position: 'top-right'
      })
   }
})
watch(() => error.value, (val) => {
    if(val) {
      Notify.create({
         type: 'negative',
         message: val,
         position: 'top-right'
      })
   }
   
})

</script>

<template>
   <div class="flash-message q-px-sm q-mb-sm" v-if="1 == 2">
      <q-item v-if="error" class="text-red bg-red-1">
         <q-item-section side>
            <q-icon name="warning" color="red"></q-icon>
         </q-item-section>
         <q-item-section>{{ error }}</q-item-section>
      </q-item>
      <q-item v-if="message" class="text-green bg-green-1">
         <q-item-section side>
            <q-icon name="check_circle" color="green"></q-icon>
         </q-item-section>
         <q-item-section>{{ message }}</q-item-section>
      </q-item>
   </div>
</template>