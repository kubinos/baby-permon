<script setup>
import { useQuasar } from 'quasar';
import { onMounted, ref, watch } from 'vue';
import { createSound, deleteSound, getSounds, updateSound } from '../api.js';
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
    name: 'number',
    label: 'Číslo',
    field: 'number',
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

const form = ref(null);
const dialog = ref(false);
const dialogTitle = ref('Vytvořit nový zvuk');
const rows = ref();

const sound = ref({
  name: null,
  number: null
});

const fetchSounds = () => {
  getSounds().then(({ data }) => {
    rows.value = data.data;
  });
};

onMounted(() => {
  fetchSounds();
});

watch(dialog, (value) => {
  if (!value) {
    dialogTitle.value = 'Vytvořit nový zvuk';
    form.value.resetValidation();
    sound.value = {
      name: null,
      number: null
    };
  }
});

function open (data) {
  sound.value = { ...data };
  dialog.value = true;

  if (data.hasOwnProperty('id')) {
    dialogTitle.value = 'Upravit zvuk';
  }
}

function submit () {
  form.value.submit();
}

function onSubmit () {
  if (!form.value.validate()) {
    return;
  }

  const isEdit = sound.value.hasOwnProperty('id');

  if (isEdit) {
    updateSound(sound.value).then(() => {
      fetchSounds();

      $q.notify({
        color: 'positive',
        message: 'Zvuk byl upraven.',
        position: 'bottom-right'
      });

      dialog.value = false;
    }).catch(() => {
      $q.notify({
        color: 'negative',
        message: 'Při úpravě zvuku došlo k chybě.',
        position: 'bottom-right'
      });
    });
  }
  else {
    createSound(sound.value).then(() => {
      fetchSounds();

      $q.notify({
        color: 'positive',
        message: 'Zvuk byl vytvořen.',
        position: 'bottom-right'
      });

      dialog.value = false;
    }).catch(() => {
      $q.notify({
        color: 'negative',
        message: 'Při vytváření zvuku došlo k chybě.',
        position: 'bottom-right'
      });
    });
  }
}

function remove (data) {
  $q.dialog({
    'title': 'Smazat zvuk',
    'message': `Opravdu chcete smazat zvuk ${data.name}?`,
    cancel: true
  }).onOk(() => {
    deleteSound(data.id).then(() => {
      fetchSounds();

      $q.notify({
        color: 'positive',
        message: 'Zvuk byl smazán.',
        position: 'bottom-right'
      });
    }).catch(() => {
      $q.notify({
        color: 'negative',
        message: 'Při mazání zvuku došlo k chybě.',
        position: 'bottom-right'
      });
    });
  });
}
</script>

<template>
  <div class="row">
    <div class="col">
      <h1 class="text-h4">Zvuky</h1>
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
          <q-btn color="primary" icon-right="add" label="Přidat zvuk" @click="open" />
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
      <q-card-section class="row items-center q-pb-none">
        <div class="text-h6">{{ dialogTitle }}</div>
        <q-space />
        <q-btn v-close-popup dense flat icon="close" round />
      </q-card-section>

      <q-card-section>
        <q-form ref="form" class="q-gutter-md" @submit="onSubmit">
          <q-input
            v-model="sound.name"
            :rules="[rules.required]"
            filled
            label="Název"
            lazy-rules
          />
          <q-input
            v-model="sound.number"
            :rules="[rules.required]"
            filled
            label="Číslo"
            lazy-rules
          />
        </q-form>
      </q-card-section>

      <q-card-actions align="right">
        <q-btn v-close-popup flat label="Zrušit" />
        <q-btn color="primary" flat label="Uložit" @click="submit" />
      </q-card-actions>
    </q-card>
  </q-dialog>
</template>
