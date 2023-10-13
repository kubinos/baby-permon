<script setup>
import { useQuasar } from 'quasar';
import { onMounted, ref, watch } from 'vue';
import { createStation, deleteStation, getStations, updateStation } from '../api.js';
import { rules } from '../rules.js';

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
    name: 'name',
    label: 'Název',
    field: 'name',
    align: 'left',
    sortable: false
  },
  {
    name: 'location',
    label: 'Umístění',
    field: 'location',
    align: 'left',
    sortable: false
  },
  {
    name: 'actions',
    label: 'Akce',
    field: 'number',
    align: 'right',
    sortable: false
  }
];
const options = [
  {
    label: 'Barvosvět',
    value: 'color-world'
  },
  {
    label: 'Podzemí',
    value: 'under-world'
  },
  {
    label: 'Kinetická aktivita',
    value: 'kinetic-activity'
  },
  {
    label: 'Obrázková aktivita',
    value: 'picture-activity'
  }
];

const form = ref(null);
const dialog = ref(false);
const dialogTitle = ref('Vytvořit nové stanoviště');
const rows = ref();

const station = ref({
  name: null,
  location: null
});

const fetchStations = () => {
  getStations().then(({ data }) => {
    rows.value = data.data;
  });
};

onMounted(() => {
  fetchStations();
});

watch(dialog, (value) => {
  if (!value) {
    dialogTitle.value = 'Vytvořit nové stanoviště';
    form.value.resetValidation();
    station.value = {
      name: null,
      location: null
    };
  }
});

function open (data) {
  station.value = { ...data };
  dialog.value = true;

  if (data.hasOwnProperty('id')) {
    dialogTitle.value = 'Upravit stanoviště';
  }
}

function onSubmit () {
  if (!form.value.validate()) {
    return;
  }

  const isEdit = station.value.hasOwnProperty('id');

  if (isEdit) {
    updateStation(station.value).then(() => {
      fetchStations();

      $q.notify({
        color: 'positive',
        message: 'Stanoviště bylo upraveno.',
        position: 'bottom-right'
      });

      dialog.value = false;
    }).catch(() => {
      $q.notify({
        color: 'negative',
        message: 'Při úpravě stanoviště došlo k chybě.',
        position: 'bottom-right'
      });
    });
  }
  else {
    createStation(station.value).then(() => {
      fetchStations();

      $q.notify({
        color: 'positive',
        message: 'Stanoviště bylo vytvořeno.',
        position: 'bottom-right'
      });

      dialog.value = false;
    }).catch(() => {
      $q.notify({
        color: 'negative',
        message: 'Při vytváření stanoviště došlo k chybě.',
        position: 'bottom-right'
      });
    });
  }
}

function remove (data) {
  $q.dialog({
    'title': 'Smazat stanoviště',
    'message': `Opravdu chcete smazat stanoviště ${data.name}?`,
    cancel: true
  }).onOk(() => {
    deleteStation(data.id).then(() => {
      fetchStations();

      $q.notify({
        color: 'positive',
        message: 'Stanoviště bylo smazáno.',
        position: 'bottom-right'
      });
    }).catch(() => {
      $q.notify({
        color: 'negative',
        message: 'Při mazání stanoviště došlo k chybě.',
        position: 'bottom-right'
      });
    });
  });
}
</script>

<template>
  <div class="row">
    <div class="col">
      <h1 class="text-h4">Stanoviště</h1>
    </div>
  </div>

  <div class="row">
    <div class="col">
      <q-table
        :columns="columns"
        :pagination="{ rowsPerPage: 20 }"
        :rows="rows"
        bordered
        flat
      >
        <template v-slot:top>
          <q-space />
          <q-btn color="primary" icon-right="add" label="Přidat stanoviště" @click="open" />
        </template>
        <template v-slot:body-cell-location="props">
          <q-td :props="props">
            {{ options.find(option => option.value === props.row.location)?.label }}
          </q-td>
        </template>
        <template v-slot:body-cell-actions="props">
          <q-td :props="props">
            <q-btn color="primary" flat icon="edit" round @click="open(props.row)" />
            <q-btn color="negative" flat icon="delete" round @click="remove(props.row)" />
          </q-td>
        </template>
      </q-table>
    </div>
  </div>

  <q-dialog v-model="dialog">
    <q-card style="min-width: 500px;">
      <q-form ref="form" @submit="onSubmit">
        <q-card-section class="row items-center q-pb-none">
          <div class="text-h6">{{ dialogTitle }}</div>
          <q-space />
          <q-btn v-close-popup dense flat icon="close" round />
        </q-card-section>

        <q-card-section>
          <div class="q-gutter-md">
            <q-input
              v-model="station.name"
              :rules="[rules.required]"
              filled
              label="Název"
              lazy-rules
            />
            <q-select
              v-model="station.location"
              :display-value="options.find(option => option.value === station.location)?.label"
              :options="options"
              :rules="[rules.required]"
              emit-value
              filled
              label="Umístění"
              lazy-rules
            />
          </div>
        </q-card-section>

        <q-card-actions align="right">
          <q-btn v-close-popup flat label="Zrušit" />
          <q-btn color="primary" flat label="Uložit" type="submit" />
        </q-card-actions>
      </q-form>
    </q-card>
  </q-dialog>
</template>
