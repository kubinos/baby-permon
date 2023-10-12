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
]

const form = ref(null);
const dialog = ref(false);
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
}

function submit () {
  form.value.submit();
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
        message: 'Stanice byla upravena.',
        position: 'bottom-right'
      });

      dialog.value = false;
    }).catch(() => {
      $q.notify({
        color: 'negative',
        message: 'Při úpravě stanice došlo k chybě.',
        position: 'bottom-right'
      });
    });
  }
  else {
    createStation(station.value).then(() => {
      fetchStations();

      $q.notify({
        color: 'positive',
        message: 'Stanice byla vytvořena.',
        position: 'bottom-right'
      });

      dialog.value = false;
    }).catch(() => {
      $q.notify({
        color: 'negative',
        message: 'Při vytváření stanice došlo k chybě.',
        position: 'bottom-right'
      });
    });
  }
}

function remove (data) {
  $q.dialog({
    'title': 'Smazat zvuk',
    'message': `Opravdu chcete smazat stanici ${data.name}?`,
    cancel: true
  }).onOk(() => {
    deleteStation(data.id).then(() => {
      fetchStations();

      $q.notify({
        color: 'positive',
        message: 'Stanice byla smazána.',
        position: 'bottom-right'
      });
    }).catch(() => {
      $q.notify({
        color: 'negative',
        message: 'Při mazání stanice došlo k chybě.',
        position: 'bottom-right'
      });
    });
  });
}
</script>

<template>
  <div class="row">
    <div class="col">
      <h1 class="text-h4">Stanice</h1>
    </div>
  </div>

  <div class="row">
    <div class="col">
      <q-table
        flat
        bordered
        :columns="columns"
        :rows="rows"
        :pagination="{ rowsPerPage: 20 }"
      >
        <template v-slot:top>
          <q-space />
          <q-btn color="primary" icon-right="add" label="Přidat stanici" @click="open" />
        </template>
        <template v-slot:body-cell-location="props">
          <q-td :props="props">
            {{ options.find(option => option.value === props.row.location)?.label }}
          </q-td>
        </template>
        <template v-slot:body-cell-actions="props">
          <q-td :props="props">
            <q-btn flat round color="primary" icon="edit" @click="open(props.row)" />
            <q-btn flat round color="negative" icon="delete" @click="remove(props.row)" />
          </q-td>
        </template>
      </q-table>
    </div>
  </div>

  <q-dialog v-model="dialog">
    <q-card style="min-width: 500px;">
      <q-card-section class="row items-center q-pb-none">
        <div class="text-h6">Vytvořit novou stanici</div>
        <q-space />
        <q-btn icon="close" flat round dense v-close-popup />
      </q-card-section>

      <q-card-section>
        <q-form ref="form" class="q-gutter-md" @submit="onSubmit">
          <q-input
            v-model="station.name"
            filled
            label="Název"
            lazy-rules
            :rules="[rules.required]"
          />
          <q-select
            v-model="station.location"
            :options="options"
            filled
            label="Umístění"
            lazy-rules
            :rules="[rules.required]"
            emit-value
            :display-value="options.find(option => option.value === station.location)?.label"
          />
        </q-form>
      </q-card-section>

      <q-card-actions align="right">
        <q-btn flat label="Zrušit" v-close-popup />
        <q-btn flat color="primary" label="Uložit" @click="submit" />
      </q-card-actions>
    </q-card>
  </q-dialog>
</template>
