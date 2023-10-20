<script setup>
import dayjs from 'dayjs';
import 'dayjs/locale/cs';
import relativeTime from 'dayjs/plugin/relativeTime';
import { onMounted, ref } from 'vue';
import { getGamesActive } from '../api.js';

dayjs.extend(relativeTime);

const loading = ref(false);
const rows = ref([]);

const columns = [
  {
    name: 'salutation',
    label: 'Oslovení',
    field: 'salutation',
    align: 'left',
    sortable: false
  },
  {
    name: 'points',
    label: 'Body',
    field: 'points',
    align: 'left',
    sortable: false
  },
  {
    name: 'labyring_time',
    label: 'Čas v labyrintu',
    field: 'labyring_time',
    align: 'left',
    sortable: false
  },
  {
    name: 'expiration',
    label: 'Konec hry',
    field: 'expiration',
    format: (_, row) => dayjs().locale('cs').to(row.expiration),
    align: 'right',
    sortable: false,
  }
];

function fetchPlayers () {
  loading.value = true;
  getGamesActive().then(({ data }) => {
    rows.value = data.data;
  }).finally(() => {
    loading.value = false;
  });
}

onMounted(() => {
  fetchPlayers();
});

setInterval(() => {
  fetchPlayers();
}, 30000);

</script>

<template>
  <div class="row" style="align-items: center">
    <div class="col">
      <h1 class="text-h4">Přehled / Dashboard</h1>
    </div>
    <div class="col text-right">
      <q-btn
        flat
        round
        icon="close"
        :to="{ name: 'game_players' }"
      />
    </div>
  </div>

  <div class="row">
    <div class="col">
      <q-table
        :loading="loading"
        :columns="columns"
        :rows="rows"
        :pagination="{ rowsPerPage: -1 }"
        flat
        hide-bottom
      >
        <template v-slot:body-cell-expiration="props">
          <q-td :props="props">
            <div>
              <q-badge v-if="Math.abs(new Date() - new Date(props.row.expiration)) <= 900000" class="q-pa-sm" color="red" :label="props.value" />
              <span v-else>{{ props.value }}</span>
            </div>
          </q-td>
        </template>
      </q-table>
    </div>
  </div>
</template>
