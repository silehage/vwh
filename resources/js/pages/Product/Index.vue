<script setup>
import products from '@/routes/products';
import { router, usePage } from '@inertiajs/vue3';
import { reactive, ref } from 'vue';

const data = usePage().props.data
const options = usePage().props.filters

const filter = reactive({
   product_id: usePage().props.query.product_id ? parseInt(usePage().props.query.product_id) : ''
})

const option_filtered = ref(options)

const filterFn = (val, update, abort) => {
   update(() => {
      if (val) {
         console.log(val);

         const needle = val.toLowerCase()
         option_filtered.value = options.filter(v => v.label.toLowerCase().indexOf(needle) > -1)
      } else {
         option_filtered.value = options
      }
   })
}
</script>

<template>
   <q-card class="section">
      <q-card-section>
         <q-select dense label="Filter Product" filled class="q-mb-md" :options="option_filtered" map-options emit-value
            v-model="filter.product_id" @update:model-value="router.visit(products.index({ query: filter }))" use-input
            hide-dropdown-icon clearable @clear="router.visit(products.index())" @filter="filterFn"></q-select>

         <TableContainer>


            <thead>
               <tr>
                  <th align="left">#</th>
                  <th align="left">Sku Number</th>
                  <th align="left">Product</th>
                  <th align="left">Total Stock</th>
                  <th align="left">Buy Price</th>
                  <th align="left">Sell Price</th>
               </tr>
            </thead>
            <tbody>
               <tr v-for="(item, i) in data.data" :key="i">
                  <td>{{ data.from + i }}</td>
                  <td>{{ item.sku_number }}</td>
                  <td>{{ item.title }}</td>
                  <td>{{ item.total_stock }}</td>
                  <td>{{ item.buy_price }}</td>
                  <td>{{ item.sell_price }}</td>
               </tr>
            </tbody>
         </TableContainer>
         <div class="q-mt-md">
            <AppPagination v-bind="data"></AppPagination>
         </div>
      </q-card-section>
   </q-card>

</template>