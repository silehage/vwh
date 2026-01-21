<script setup>
import { router } from '@inertiajs/vue3';

defineProps({
   total: Number,
   per_page: Number,
   current_page: Number,
   last_page: Number,
   next_page_url: String,
   prev_page_url: String,
   first_page_url: String,
   last_page_url: String,
   from: Number,
})

</script>

<template>
   <div class="flex justify-end items-center q-gutter-x-sm no-wrap q-py-xs" v-if="total > 0">
      <div>Page</div>
      <div>{{ current_page }} / {{ last_page }}</div>
      <div>Total {{ total }}</div>
      <!-- <q-btn v-if="current_page > 1" outline size="11px" label="FIRST" @click="router.get(first_page_url)"></q-btn> -->
      <q-btn :disable="!prev_page_url" outline size="11px" label="PREV" @click="router.get(prev_page_url)"></q-btn>
      <q-btn :disable="!next_page_url" outline size="11px" label="NEXT" @click="router.get(next_page_url)"></q-btn>
      <!-- <q-btn :disable="current_page == last_page" outline size="11px" label="LAST" @click="router.get(last_page_url)"></q-btn> -->
   </div>
</template>