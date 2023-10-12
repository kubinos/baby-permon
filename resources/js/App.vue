<script setup>
import { useQuasar } from 'quasar';
import { computed, ref } from 'vue';

const $q = useQuasar();

const drawer = ref(true);

const menu = ref([
  {
    name: 'Založit hru',
    route: 'game_start',
    icon: 'add'
  },
  {
    name: 'Ukončit hru',
    route: 'game_end',
    icon: 'remove'
  },
  {
    name: 'Hráči',
    route: 'game_players',
    icon: 'group'
  },
  {
    name: 'Zvuky',
    route: 'admin_sounds',
    icon: 'music_note'
  },
  {
    name: 'Umístění',
    route: 'admin_locations',
    icon: 'location_on'
  },
  {
    name: 'Úkoly',
    route: 'admin_tasks',
    icon: 'checklist'
  },
  {
    name: 'Obtížnost',
    route: 'admin_levels',
    icon: 'show_chart'
  }
]);

const gameMenu = computed(() => {
  return menu.value.filter(item => item.route.startsWith('game'));
});

const adminMenu = computed(() => {
  return menu.value.filter(item => item.route.startsWith('admin'));
});
</script>

<template>
  <q-layout view="hHh Lpr lFf">
    <q-header>
      <q-toolbar>
        <q-btn flat round dense icon="menu" aria-label="Menu" @click="drawer = !drawer" />

        <q-icon name="extension" class="q-ml-sm" size="26px" />

        <q-toolbar-title>
          {{ 'Baby Permon' }}
        </q-toolbar-title>

        <q-btn v-if="$q.dark.isActive" flat round dense icon="light_mode" @click="$q.dark.set(false)" />
        <q-btn v-else flat round dense icon="dark_mode" @click="$q.dark.set(true)" />
        <q-separator class="q-mx-sm" vertical inset />
        <q-btn flat icon-right="logout" text-color="white" label="Odhlášení" />
      </q-toolbar>
    </q-header>

    <q-drawer
      v-model="drawer"
      show-if-above
      bordered
    >
      <q-list>
        <q-item-label header>
          {{ 'Pokladna' }}
        </q-item-label>

        <q-item
          v-for="{ name, route, icon } in gameMenu"
          :key="route"
          v-ripple
          clickable
          :to="{ name: route }"
        >
          <q-item-section avatar>
            <q-icon :name="icon" />
          </q-item-section>
          <q-item-section>
            {{ name }}
          </q-item-section>
        </q-item>

        <q-separator />

        <q-item-label header>
          {{ 'Administrace' }}
        </q-item-label>

        <q-item
          v-for="{ name, route, icon } in adminMenu"
          :key="route"
          v-ripple
          clickable
          :to="{ name: route }"
        >
          <q-item-section avatar>
            <q-icon :name="icon" />
          </q-item-section>
          <q-item-section>
            {{ name }}
          </q-item-section>
        </q-item>
      </q-list>
    </q-drawer>

    <q-page-container>
      <q-page padding>
        <router-view />
      </q-page>
    </q-page-container>
  </q-layout>
</template>
