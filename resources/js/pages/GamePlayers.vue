<script setup>
import dayjs from 'dayjs';
import 'dayjs/locale/cs';
import relativeTime from 'dayjs/plugin/relativeTime';
import { debounce, useQuasar } from 'quasar';
import { onMounted, ref } from 'vue';
import { deleteGame, getGameLogs, getGames } from '../api.js';

dayjs.extend(relativeTime);

const loading = ref(false);
const rows = ref([]);

const $q = useQuasar();

const columns = [
  {
    name: 'id',
    label: '#',
    field: 'id',
    align: 'left',
    sortable: false
  },
  {
    name: 'ended',
    label: 'Ukončeno',
    field: (row) => row.ended_at !== null ? 'ano' : 'ne',
    align: 'left',
    sortable: true
  },
  {
    name: 'salutation',
    label: 'Oslovení',
    field: 'salutation',
    align: 'left',
    sortable: false
  },
  {
    name: 'type',
    label: 'Typ',
    field: 'type_trans',
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
    name: 'level',
    label: 'Level',
    field: 'level',
    align: 'left',
    sortable: true
  },
  {
    name: 'points',
    label: 'Body',
    field: 'points',
    align: 'left',
    sortable: true
  },
  {
    name: 'expiration',
    label: 'Konec hry',
    field: 'expiration',
    format: (_, row) => formatTime(row.expiration),
    align: 'left',
    sortable: true,
    sort: (a, b) => dayjs(a).unix() - dayjs(b).unix()
  },
  {
    name: 'actions',
    label: '-',
    align: 'right',
    sortable: false,
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

const dialogTitle = ref('Detail hráče');
const dialog = ref(false);

const logColumns = ref([
  {
    name: 'id',
    label: '#',
    field: 'id',
    align: 'left',
    sortable: false
  },
  {
    name: 'created_at',
    label: 'Datum',
    field: 'created_at',
    format: (_, row) => (new Date(row.created_at)).toLocaleString('cs'),
    align: 'left',
    sortable: false
  },
  {
    name: 'action',
    label: 'Úkon',
    field: 'action',
    align: 'left',
    sortable: false
  },
]);

const logRows = ref([]);

function showLog ({ id, salutation }) {
  getGameLogs(id).then(({ data }) => {
    logRows.value = data.data;
    dialogTitle.value = `Detail hráče ${salutation}`;
    dialog.value = true;
  });
}

function formatTime (date) {
  const ms = new Date(date) - new Date();

  const s = Math.floor(ms / 1000);
  const m = Math.floor(s / 60) % 60;
  const h = Math.floor(s / 3600);

  return `${h}:${Math.abs(m) > 10 ? Math.abs(m) : '0'+Math.abs(m)}`;
}

function endGame ({ chip }) {
  $q.dialog({
    title: 'Ukončení hry',
    message: 'Opravdu chcete ukončit hru?',
    cancel: true,
  }).onOk(() => {
    deleteGame('00' + chip).then(() => {
      fetchPlayers();

      $q.notify({
        color: 'positive',
        message: 'Hra byla ukončena'
      });
    });
  });
}
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
        <template v-slot:body-cell-actions="props">
          <q-td :props="props">
            <q-btn flat round size="sm" color="primary" @click="showLog(props.row)">
              <q-icon name="visibility" />
            </q-btn>
            <q-btn flat round size="sm" color="negative" @click="endGame(props.row)">
              <q-icon name="delete" />
            </q-btn>
          </q-td>
        </template>
      </q-table>
    </div>
  </div>

  <q-dialog v-model="dialog">
    <q-card style="min-width: 800px;">
      <q-card-section class="row items-center q-pb-none">
        <div class="text-h6">{{ dialogTitle }}</div>
        <q-space />
        <q-btn v-close-popup dense flat icon="close" round />
      </q-card-section>

      <q-card-section>
        <q-table
          flat
          :rows="logRows"
          :columns="logColumns"
          row-key="name"
          :pagination="{ rowsPerPage: -1 }"
          hide-bottom
        />
      </q-card-section>

      <q-card-actions align="right">
        <q-btn v-close-popup flat form="form" label="Zavřít" />
      </q-card-actions>
    </q-card>
  </q-dialog>
</template>
