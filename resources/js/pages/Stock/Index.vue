<script setup>
import { router, usePage } from '@inertiajs/vue3';
import stocks from '@/routes/stocks';

const data = usePage().props.data

const handleCOnfirm = (item) => {
   router.visit(stocks.confirmed(item.id), {
      preserveState: false
   })
}

</script>

<template>

   <AppHeader title="Stocks">
      <q-btn color="primary" @click="router.visit(stocks.create())" label="Add Stock"></q-btn>
   </AppHeader>
   <q-card class="section">
      <q-card-section>
         <div class="table-responsive">
            <table class="table bordered">
               <thead>
                  <tr>
                     <th align="left">#</th>
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
                     <td>{{ item.created_at }}</td>
                     <td>{{ item.description }}</td>
                     <td>{{ item.items_sum_quantity }}</td>
                     <td>
                        <q-badge :color="item.status == 'Pending' ? 'grey-7' : 'green'">{{ item.status }}</q-badge>
                     </td>
                     <td>{{ item.confirmed_at }}</td>
                     <td>{{ item.confirmed_by }}</td>
                     <td class="nowrap q-gutter-xs">
                        <q-btn-dropdown dropdown-icon="more_vert" no-icon-animation auto-close round flat>
                           <q-list separator>

                              <q-item clickable @click="router.visit(stocks.edit(item.id))">
                                 <q-item-section>Detail</q-item-section>
                              </q-item>
                              <q-item clickable v-if="item.can_confirm"
                                 @click="handleCOnfirm(item)">
                                 <q-item-section>Confirm</q-item-section>
                              </q-item>
                           </q-list>
                        </q-btn-dropdown>
                     </td>
                  </tr>
               </tbody>
            </table>
         </div>
         <div class="q-mt-md">
            <AppPagination v-bind="data"></AppPagination>
         </div>
      </q-card-section>
   </q-card>
</template>