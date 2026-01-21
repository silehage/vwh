<template>

  <q-layout view="lHh LpR fFf">

    <q-page-container>
     <q-page padding class="flex flex-center bg-grey-2">
      <FlashMessage></FlashMessage>
      <slot />
     </q-page>
    </q-page-container>

  </q-layout>
</template>

<script setup lang="ts">

import FlashMessage from '@/components/FlashMessage.vue';

defineProps<{
  title?: string;
  description?: string;
}>();

</script>