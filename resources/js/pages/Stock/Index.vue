<script setup>
import { router, usePage } from '@inertiajs/vue3';
import stocks from '@/routes/stocks';
import { badgeColor, dateFormat } from '@/lib/utils';

const data = usePage().props.data
const options = usePage().props.options

const handleCOnfirm = (item) => {
   router.visit(stocks.confirmed(item.id), {
      preserveState: false
   })
}

const queryparams = {
   stock_type: usePage().props.query?.stock_type || 'in'
}

</script>

<template>

   <AppHeader title="Stocks">
      <q-btn color="primary" @click="router.visit(stocks.create())" label="Add Stock"></q-btn>
   </AppHeader>
   <q-card class="section">
      <q-card-section>
         <div class="q-mb-md q-gutter-sm">
            <q-btn v-for="(val, i) in options" :label="val.label" :color="queryparams.stock_type == val.value ? 'primary' :'grey-6'" :key="i" @click="router.visit(stocks.index({ query: { stock_type: val.value } }))"></q-btn>
         </div>
         <TableContainer>
            <thead>
               <tr>
                  <th align="left">#</th>
                  <th align="left">Ref</th>
                  <th align="left">Date</th>
                  <th align="left">Description</th>
                  <th align="left">Total Stock</th>
                  <th align="left">Status</th>
                  <th align="left">ConfirmedAt</th>
                  <th align="left">ConfirmedBy</th>
                  <th>Action</th>
               </tr>
            </thead>
            <tbody>
               <tr v-for="(item, i) in data.data" :key="i">
                  <td>{{ data.from + i }}</td>
                  <td>{{ item.reference }}</td>
                  <td>{{dateFormat(item.created_at) }}</td>
                  <td>{{ item.description }}</td>
                  <td>{{ item.items_sum_quantity }}</td>
                  <td>
                     <q-badge :color="badgeColor(item.status)">{{ item.status }}</q-badge>
                  </td>
                  <td>{{ item.confirmed_at }}</td>
                  <td>{{ item.confirmed_by }}</td>
                  <td class="nowrap q-gutter-xs">
                     <q-btn-dropdown dropdown-icon="more_vert" no-icon-animation auto-close round flat dense>
                        <q-list separator>

                           <q-item clickable @click="router.visit(stocks.edit(item.id))">
                              <q-item-section>Detail</q-item-section>
                           </q-item>
                           <q-item clickable v-if="item.can_confirm" @click="handleCOnfirm(item)">
                              <q-item-section>Confirm</q-item-section>
                           </q-item>
                        </q-list>
                     </q-btn-dropdown>
                  </td>
               </tr>
            </tbody>
         </TableContainer>
         <AppPagination v-bind="data"></AppPagination>
      </q-card-section>
   </q-card>
</template>