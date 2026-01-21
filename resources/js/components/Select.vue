<script setup lang="ts">
import { useVModel } from '@vueuse/core'

const props = defineProps<{
  label?: string;
  defaultValue?: string | number;
  modelValue?: string | number | void;
  emitValue?: boolean;
  mapOptions?: boolean;
  options?: array;
}>()


const emits = defineEmits<{
  (e: 'update:modelValue', payload: string | number): void
}>()

const modelValue = useVModel(props, 'modelValue', emits, {
  passive: true,
  defaultValue: props.defaultValue,
})
</script>

<template>
  <q-select :label="label" v-model="modelValue" data-slot="input" :options="options" :emit-value="emitValue" :map-options="mapOptions"></q-select>
</template>
