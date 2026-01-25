<script setup>
import stocks from '@/routes/stocks';
import { useForm, usePage } from '@inertiajs/vue3';
import { onMounted, ref } from 'vue';

const data = usePage().props.data
const options = usePage().props.filters

const stockTypes = [
   { label: 'STOCK IN', value: 'in' },
   { label: 'STOCK OUT', value: 'out' },
]

const form = useForm({
   description: '',
   stock_type: 'in',
   stock_items: []
})

const submitData = () => {

   if (data) {
      form.put(stocks.update(data.id).url)
   } else {
      form.post(stocks.store().url)
   }
}

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

const modal = ref(false)
const filter = ref('')
const filtered_products = ref([])
const getProductList = async () => {
   filtered_products.value = []
   const response = await fetch(`/api/products/list?product_id=${filter.value}`)
   const result = await response.json()
   filtered_products.value = result.data
}

const isSelected = (productLineId) => {
   return form.stock_items.some(el => el.product_line_id == productLineId)
}

const addProductItem = (product) => {
   if (isSelected(product.id)) {
      form.stock_items = form.stock_items.filter(el => el.product_line_id != product.id)
   } else {
      form.stock_items.push({
         product_name: product.title,
         product_line_id: product.id,
         sku_number: product.sku_number,
         quantity: 0
      })

   }
}

const deleteItem = (idx) => {
   form.stock_items.splice(idx, 1)
}

const isEdit = ref(false)
const canUpdate = ref(true)


onMounted(() => {
   isEdit.value = true
   if (data) {
      canUpdate.value = data.can_update
      form.description = data.description
      form.stock_type = data.stock_type

      data.items.forEach(el => {
         form.stock_items.push({
            product_line_id: el.id,
            product_name: el.product.title,
            sku_number: el.product.sku_number,
            quantity: el.quantity
         })
      });
   }
})
</script>

<template>
   <q-form @submit="submitData">
      <q-card class="section">
         <q-card-section>
            <div class="card-title">Detail</div>
            <q-select :readonly="isEdit" :options="stockTypes" label="Stock Type" v-model="form.stock_type" emit-value map-options>
            </q-select>
            <q-input :readonly="!canUpdate" label="Description" v-model="form.description"> </q-input>
         </q-card-section>
      </q-card>
      <q-card class="section q-mt-md">
         <q-card-section>
            <div class="card-title">
               <div>Products</div>
               <q-btn v-if="canUpdate" label="Add Product" color="primary" size="12px" @click="modal = true"></q-btn>
            </div>
            <table class="table bordered">
               <thead>
                  <tr>
                     <th align="left">#</th>
                     <th align="left">SKU NUMBER</th>
                     <th align="left">PRODUCT</th>
                     <th align="left">QUANTITY</th>
                     <th align="right">Action</th>
                  </tr>
               </thead>
               <tbody>
                  <tr v-for="(item, idx) in form.stock_items" :key="idx">
                     <td>{{ idx + 1 }}</td>
                     <td>{{ item.sku_number }}</td>
                     <td>{{ item.product_name }}</td>
                     <td>
                        <q-input :readonly="!canUpdate" min="1" required style="width:100px" outlined dense
                        v-model="form.stock_items[idx].quantity" type="number"></q-input>
                     </td>
                     <td align="right">
                        <q-btn v-if="canUpdate" dense size="12px" color="red" icon="delete" @click="deleteItem(idx)"></q-btn>
                     </td>
                  </tr>
               </tbody>
            </table>
         </q-card-section>
      </q-card>

      <q-btn v-if="canUpdate" label="Submit Data" type="submit" color="primary" class="q-mt-md"></q-btn>

   </q-form>

   <q-dialog v-model="modal">
      <q-card class="section card-lg">
         <q-card-section>
            <div class="card-title">
               <div>Product</div>
               <q-btn icon="close" flat round v-close-popup></q-btn>
            </div>
            <q-select dense label="Filter Product" filled class="q-mb-md" :options="option_filtered" map-options
               emit-value v-model="filter" @update:model-value="getProductList" debounce="700" use-input
               @filter="filterFn"></q-select>

            <div class="scroll" style="max-height: 360px;">
               <q-list separator>
                  <q-item v-for="(item, i) in filtered_products" :key="i" clickable @click="addProductItem(item)">
                     <q-item-section side>
                        <q-icon :name="isSelected(item.id) ? 'check_box' : 'check_box_outline_blank'" size="md"
                           :color="isSelected(item.id) ? 'green' : 'grey-7'"></q-icon>
                     </q-item-section>
                     <q-item-section>{{ item.sku_number }}</q-item-section>
                     <q-item-section>{{ item.title }}</q-item-section>
                  </q-item>
               </q-list>
            </div>

         </q-card-section>
      </q-card>
   </q-dialog>

</template>