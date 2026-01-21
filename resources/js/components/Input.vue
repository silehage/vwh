<script setup lang="ts">
import type { HTMLAttributes } from 'vue'
import { useVModel } from '@vueuse/core'

const props = defineProps<{
  defaultValue?: string | number
  modelValue?: string | number
  class?: HTMLAttributes['class']
  label?: string
  type?: 'text'
  dense? : boolean
  filled? : boolean
  outlined? : boolean
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
  <q-input :filled="filled" :outlined="outlined" :dense="dense" :type="type" :label="label" v-model="modelValue" data-slot="input" ></q-input>
</template>
