<template>

   <Head :title="title"></Head>

   <div class="page-header">
     <div class="flex q-gutter-x-xs no-wrap items-center">
         <q-btn v-if="back" @click="backTo" flat dense icon="arrow_back"></q-btn>
         <h1>{{ title }}</h1>
      </div>
      <slot></slot>
   </div>

</template>


<script setup lang="ts">

import { Head, router } from '@inertiajs/vue3';

defineProps<{
  title?: string;
  description?: string;
  back?: boolean
}>();

const backTo = () => {
   window.history.back()
}

</script>
