<script setup>
import { useQuasar } from 'quasar';
import { computed, ref, watch } from 'vue';
import { useRoute, useRouter } from 'vue-router';

const $q = useQuasar();

const drawer = ref(true);
const auth = ref(false);
const route = useRoute();
const router = useRouter();

watch(route, () => {
  auth.value = localStorage.getItem('token') === 'true';
});

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
    name: 'Stanoviště',
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

function logout () {
  localStorage.removeItem('token');

  $q.notify({
    message: 'Byli jste odhlášeni',
    color: 'positive',
    icon: 'logout',
    position: 'bottom-right'
  });

  router.push({ name: 'login' });
}
</script>

<template>
  <q-layout v-if="auth" view="hHh Lpr lFf">
    <q-header>
      <q-toolbar>
        <q-btn aria-label="Menu" dense flat icon="menu" round @click="drawer = !drawer" />

        <q-icon class="q-ml-sm" name="extension" size="26px" />

        <q-toolbar-title>
          {{ 'Baby Permon' }}
        </q-toolbar-title>

        <q-btn v-if="$q.dark.isActive" dense flat icon="light_mode" round @click="$q.dark.set(false)" />
        <q-btn v-else dense flat icon="dark_mode" round @click="$q.dark.set(true)" />
        <q-separator class="q-mx-sm" color="white" inset vertical />
        <q-btn flat icon-right="logout" label="Odhlášení" text-color="white" @click="logout" />
      </q-toolbar>
    </q-header>

    <q-drawer
      v-model="drawer"
      bordered
      show-if-above
    >
      <q-list>
        <q-item-label header>
          {{ 'Pokladna' }}
        </q-item-label>

        <q-item
          v-for="{ name, route, icon } in gameMenu"
          :key="route"
          v-ripple
          :to="{ name: route }"
          clickable
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
          :to="{ name: route }"
          clickable
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
  <router-view v-else />
</template>
