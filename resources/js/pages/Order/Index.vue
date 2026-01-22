<script setup>
import { router, usePage } from '@inertiajs/vue3';
import { onMounted, reactive, ref, watch } from 'vue';
import orders from '@/routes/orders';

const data = usePage().props.data

const columns = [
   'orderId',
   'orderSn',
   'bookingSn',
   'buyerNotes',
   'cancelBy',
   'codOrder',
   'createTime',
   'customerEmail',
   'customerName',
   'customerPhone',
   'discount',
   'hasPaid',
   'includeTax',
   'insuranceCost',
   'logisticStatus',
   'orderStatusList',
   'subOrderStatusList',
   'platformOrderStatusList',
   'paymentMethod',
   'platform',
   'platformName',
   'preOrder',
   'storeId',
   'storeName',
   'subTotal',
   'tax',
   'totalPrice',
   'totalWeight',
   'courier',
   'deliveryDeadline',
   'shippingAddress',
   'shippingArea',
   'shippingCity',
   'shippingCost',
   'shippingFullName',
   'shippingPhone',
   'shippingProvince',
   'trackingNumber',
   'shippingPostCode',
]

const selected_columns = ref([
   'orderSn',
   'codOrder',
   'createTime',
   'customerName',
   'discount',
   'hasPaid',
   'orderStatusList',
   'paymentMethod',
   'platformOrderStatusList',
   'platformName',
   'preOrder',
   'storeName',
   'subTotal',
   'totalPrice',
   'courier',
   'deliveryDeadline',
   'shippingCost',
   'trackingNumber',
])

watch(() => selected_columns.value, (val) => {
   localStorage.setItem('order__columns', JSON.stringify(val))
})

onMounted(() => {
   if (localStorage.getItem('order__columns')) {
      selected_columns.value = JSON.parse(localStorage.getItem('order__columns'))
   }
})

const queryParams = reactive({
   search: '',
   status: ''
})

const modal = ref(false)

</script>

<template>
   <AppHeader title="Order">
      <q-btn @click="modal = true" color="primary" label="Custom Table">
      </q-btn>
   </AppHeader>

   <q-card class="section">
      <q-card-section>
         <div class="q-mb-md">
            <q-input filled label="Search orderSn, orderId, mp or status" v-model="queryParams.search" clearable>
               <template v-slot:append>
                  <q-btn class="q-mr-xs" label="Search" color="primary"
                     @click=" router.visit(orders.index({ query: queryParams }))"></q-btn>
                  <q-btn label="Reset" color="teal" @click="router.visit(orders.index())"></q-btn>
               </template>
            </q-input>
         </div>
         <TableContainer>
            <thead>
               <tr>
                  <th>#</th>
                  <template v-for="(k, i) in columns" :key="i">
                     <th v-if="selected_columns.includes(k)">{{ k }}</th>
                  </template>
               </tr>

            </thead>
            <tbody>
               <tr v-for="(item, idx) in data.data" :key="idx">
                  <td>
                     {{ data.from + idx }}
                  </td>
                  <template v-for="(val, name, i) in item" :key="i">
                     <td v-if="selected_columns.includes(name)" :data-name="name">
                        {{ val }}
                     </td>
                  </template>

               </tr>
            </tbody>

         </TableContainer>
         <div v-if="!data.total" class="q-pa-sm">No items found</div>
         <AppPagination v-bind="data"></AppPagination>
      </q-card-section>
   </q-card>

   <q-dialog v-model="modal" position="right">
      <q-card>
         <q-card-section>
            <div class="card-subtitle">Select Columns</div>
            <q-list dense separator>
               <q-item v-for="item in columns" :key="item">
                  <q-item-section avatar>
                     <q-checkbox v-model="selected_columns" :val="item"></q-checkbox>
                  </q-item-section>
                  <q-item-section>{{ item }}</q-item-section>
               </q-item>
            </q-list>
         </q-card-section>
      </q-card>
   </q-dialog>
</template>