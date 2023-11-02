<script setup>
import dayjs from 'dayjs';
import 'dayjs/locale/cs';
import relativeTime from 'dayjs/plugin/relativeTime';
import { onMounted, ref } from 'vue';
import { getGamesActive } from '../api.js';

dayjs.extend(relativeTime);

const loading = ref(false);
const rows = ref([]);

function formatTime (date) {
  const ms = new Date(date) - new Date();

  const s = Math.floor(ms / 1000);
  const m = Math.floor(s / 60) % 60;
  const h = Math.floor(s / 3600);

  return `${h}:${Math.abs(m) > 10 ? Math.abs(m) : '0'+Math.abs(m)}`;
}

function scrollDownAndUp() {
  setInterval(function() {
    window.scrollBy({ left: 0, top: window.innerHeight, behavior: 'smooth' })
  }, 5000);

  setTimeout(function() {
    setInterval(function() {
      window.scrollBy({ left: 0, top: -window.innerHeight, behavior: 'smooth' });
    }, 5000);

  }, 2500);
}

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
    format: (_, row) => formatTime(row.expiration),
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
  scrollDownAndUp();
});

setInterval(() => {
  fetchPlayers();
}, 30000);

</script>

<template>
  <div class="row">
    <div class="col">
      <q-table
        class="overview-table"
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
                <q-badge v-if="(new Date() > new Date(props.row.expiration)) || Math.abs(new Date() - new Date(props.row.expiration)) <= 900000" class="q-pa-sm" color="red" :label="props.value" />
              <span v-else>{{ props.value }}</span>
            </div>
          </q-td>
        </template>
      </q-table>
    </div>
  </div>
  <div class="row q-mt-lg">
    <div class="col text-center">
      <q-btn
        flat
        round
        icon="close"
        :to="{ name: 'game_players' }"
      />
    </div>
  </div>
</template>

<style lang="scss">
.overview-table {
  td, th, .q-badge {
    font-size: 66px !important;
    line-height: 66px !important;
  }
}
</style>
