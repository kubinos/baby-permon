<script setup>
import { computed, ref } from 'vue';

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
    <q-header elevated>
      <q-toolbar>
        <q-icon name="extension" size="26px" />

        <q-toolbar-title>
          {{ 'Baby Permon' }}
        </q-toolbar-title>

        <q-btn push color="white" text-color="black" label="Odhlášení" />
      </q-toolbar>
    </q-header>

    <q-drawer
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
        <div class="row">
          <div class="offset-3 col-6">
            <router-view />
          </div>
        </div>
      </q-page>
    </q-page-container>
  </q-layout>
</template>
