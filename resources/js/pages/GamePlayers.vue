<script setup>
import dayjs from 'dayjs';
import 'dayjs/locale/cs';
import relativeTime from 'dayjs/plugin/relativeTime';
import { debounce } from 'quasar';
import { onMounted, ref } from 'vue';
import { getGames } from '../api.js';

dayjs.extend(relativeTime);

const loading = ref(false);
const rows = ref([]);

const columns = [
  {
    name: 'id',
    label: '#',
    field: 'id',
    align: 'left',
    sortable: false
  },
  {
    name: 'chip',
    label: 'Čip',
    field: 'chip',
    align: 'left',
    sortable: false
  },
  {
    name: 'salutation',
    label: 'Oslovení',
    field: 'salutation',
    align: 'left',
    sortable: false
  },
  {
    name: 'level',
    label: 'Level',
    field: 'level',
    align: 'left',
    sortable: true
  },
  {
    name: 'expiration',
    label: 'Konec hry',
    field: (row) => dayjs().locale('cs').to(row.expiration),
    align: 'right',
    sortable: true
  }
];

function fetchPlayers () {
  loading.value = true;
  getGames().then(({ data }) => {
    rows.value = data.data;
  }).finally(() => {
    loading.value = false;
  });
}

const debouncedFetch = debounce(fetchPlayers, 300);

onMounted(() => {
  fetchPlayers();
});
</script>

<template>
  <div class="row">
    <div class="col">
      <h1 class="text-h4">Hráči ve hře</h1>
    </div>
  </div>

  <div class="row">
    <div class="col">
      <q-table
        :loading="loading"
        :columns="columns"
        :rows="rows"
        :pagination="{ rowsPerPage: 20 }"
        bordered
        flat
      >
        <template v-slot:top>
          <q-space />
          <q-btn color="primary" icon-right="sync" label="Aktualizovat" @click="debouncedFetch" />
        </template>
      </q-table>
    </div>
  </div>
</template>
