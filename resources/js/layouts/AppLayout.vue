<template>
  <q-layout view="lhh LpR lff" class="dashboard bg-grey-2 text-grey-10">

    <q-header class="q-pa-md bg-transparent">
      <q-toolbar class="bg-white box-shadow text-dark rounded-borders">
        <q-btn dense flat round icon="menu" @click="toggleLeftDrawer" />
        <q-toolbar-title>
          <Link href="/" class="text-dark">
          {{ usePage().props.name }}
          </Link>
        </q-toolbar-title>

        <q-btn icon="account_circle" flat round>
          <q-menu auto-close>
            <q-list separator>
              <q-item clickable @click="router.visit(logout())">
                <q-item-section side>
                  <q-icon name="logout"></q-icon>
                </q-item-section>
                <q-item-section>Logout</q-item-section>
              </q-item>
            </q-list>
          </q-menu>
        </q-btn>
      </q-toolbar>
    </q-header>

    <q-drawer show-if-above v-model="leftDrawerOpen" side="left" :mini="is_mini" :width="210" class="bg-grey-2">
      <q-list class="q-py-md">
        <template v-for="(nav, idx) in navigations" :key="idx">
          <q-expansion-item :label="nav.title" v-if="nav.items && nav.items.length > 0" default-opened>
            <template v-for="(navItem, i) in nav.items" :key="i">
             <q-item v-if="navItem.visible" clickable :href="navItem.href.url" @click.prevent="handleClickMenu(navItem.href)">
              <q-item-section side>
                <q-icon :name="navItem.icon"></q-icon>
              </q-item-section>
              <q-item-section>
                <q-item-label>{{ navItem.title }}</q-item-label>
              </q-item-section>
              <q-tooltip v-if="is_mini" anchor="center right" self="center left" :offset="[10, 10]"
                class="bg-purple text-white">{{ navItem.title }}</q-tooltip>

            </q-item>
            </template>
          </q-expansion-item>
          <template v-else>

            <q-item v-if="nav.visible" clickable :href="nav.href.url" @click.prevent="handleClickMenu(nav.href)">
              <q-item-section side>
                <q-icon :name="nav.icon"></q-icon>
              </q-item-section>
              <q-item-section>
                <q-item-label>{{ nav.title }}</q-item-label>
              </q-item-section>
              <q-tooltip v-if="is_mini" anchor="center right" self="center left" :offset="[10, 10]"
                class="bg-purple text-white">{{ nav.title }}</q-tooltip>

            </q-item>
          </template>
        </template>
      </q-list>
    </q-drawer>

    <q-page-container>
      <q-page padding class="bg-grey-2">
        <FlashMessage></FlashMessage>
        <slot />
      </q-page>
    </q-page-container>

    <q-footer class="bg-transparent">
      <q-toolbar>
        <q-toolbar-title>
          <div class="q-px-md text-xs text-grey-6"> &copy; 2025 Allrights Reserved</div>
        </q-toolbar-title>
      </q-toolbar>
    </q-footer>

  </q-layout>
</template>

<script setup lang="ts">
import FlashMessage from '@/components/FlashMessage.vue';
import { ref } from 'vue'
import { dashboard, logout } from '@/routes';
import { router, Link, usePage } from '@inertiajs/vue3';
import { useQuasar } from 'quasar';
import users from '@/routes/users';
import permissions from '@/routes/permissions';
import roles from '@/routes/roles';
import products from '@/routes/products';
import stocks from '@/routes/stocks';
import orders from '@/routes/orders';

const $q = useQuasar()

defineProps<{
  title?: string;
  description?: string;
}>();
const leftDrawerOpen = ref(false)
const is_mini = ref(false)

const toggleLeftDrawer = () => {

  leftDrawerOpen.value = !leftDrawerOpen.value
  return

  if ($q.platform.is.desktop) {
    leftDrawerOpen.value = true
    is_mini.value = !is_mini.value
  } else {
    leftDrawerOpen.value = !leftDrawerOpen.value
  }
}

const handleClickMenu = (href) => {
  if ($q.platform.is.mobile) {
    leftDrawerOpen.value = !leftDrawerOpen.value
  }
  router.visit(href)
}

const navigations = [
  {
    title: 'Dashboard',
    href: dashboard(),
    icon: 'dashboard',
    ability: 'All',
    visible: true,
    items: []
  },
  {
    title: 'Products',
    href: products.index(),
    icon: 'groups',
    ability: 'All',
    visible: true,
    items: []
  },
  {
    title: 'Stocks',
    href: stocks.index(),
    icon: 'groups',
    ability: 'All',
    visible: true,
    items: []
  },
  {
    title: 'Orders',
    href: orders.index(),
    icon: 'groups',
    ability: 'All',
    visible: true,
    items: []
  },
  {
    title: 'Users',
    href: users.index(),
    icon: 'groups',
    ability: 'All',
    visible: true,
    items: []
  },
  {
    title: 'Roles',
    href: roles.index(),
    icon: 'groups',
    ability: 'All',
    visible: true,
    items: []
  },
  {
    title: 'Permissions',
    href: permissions.index(),
    icon: 'groups',
    ability: 'All',
    visible: true,
    items: []
  },

]

</script>