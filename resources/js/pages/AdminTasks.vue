<script setup>
import { useQuasar } from 'quasar';
import { onMounted, ref, watch } from 'vue';
import { createTask, deleteTask, getEnums, getSounds, getStations, getTasks, updateTask } from '../api.js';
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
    name: 'difficulty',
    label: 'Náročnost',
    field: 'difficulty',
    align: 'left',
    sortable: true
  },
  {
    name: 'station.name',
    label: 'Stanoviště',
    field: row => row.station.name,
    align: 'left',
    sortable: true
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
const dialogTitle = ref('Vytvořit nový úkol');
const rows = ref();
const enums = ref({
  numbers: [],
  colors: [],
  shapes: [],
  difficulties: []
});
const sounds = ref();
const stations = ref();

const task = ref({
  name: null,
  difficulty: null,
  stationId: null,
  soundCsId: null,
  soundEnId: null,
  soundDeId: null,
  responseNumber: null,
  responseColor: null,
  responseShape: null,
  pointsPartial: null,
  pointsIncorrect: null
});

const fetchTasks = () => {
  getTasks().then(({ data }) => {
    rows.value = data.data;
  });
};

onMounted(() => {
  fetchTasks();

  getEnums().then(({ data }) => {
    enums.value = data;
  });

  getSounds().then(({ data }) => {
    sounds.value = data.data;
  });

  getStations().then(({ data }) => {
    stations.value = data.data;
  });
});

watch(dialog, (value) => {
  if (!value) {
    dialogTitle.value = 'Vytvořit nový úkol';
    form.value.resetValidation();
    task.value = {
      name: null,
      difficulty: null,
      stationId: null,
      soundCsId: null,
      soundEnId: null,
      soundDeId: null,
      responseNumber: null,
      responseColor: null,
      responseShape: null,
      pointsCorrect: null,
      pointsPartial: null,
      pointsIncorrect: null
    };
  }
});

function open (data) {
  dialog.value = true;
  task.value = {
    id: data.id,
    name: data.name,
    difficulty: data.difficulty,
    stationId: data.station?.id,
    soundCsId: data.soundCs?.id,
    soundEnId: data.soundEn?.id,
    soundDeId: data.soundDe?.id,
    responseNumber: data.responseNumber,
    responseColor: data.responseColor,
    responseShape: data.responseShape,
    pointsCorrect: data.pointsCorrect,
    pointsPartial: data.pointsPartial,
    pointsIncorrect: data.pointsIncorrect
  };

  if (data.hasOwnProperty('id')) {
    dialogTitle.value = 'Upravit úkol';
  }
}

function onSubmit () {
  if (!form.value.validate()) {
    return;
  }

  const isEdit = task.value.hasOwnProperty('id');

  if (isEdit) {
    updateTask(task.value).then(() => {
      fetchTasks();

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
    createTask(task.value).then(() => {
      fetchTasks();

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
    deleteTask(data.id).then(() => {
      fetchTasks();

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
      <h1 class="text-h4">Úkoly</h1>
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
          <q-btn color="primary" icon-right="add" label="Přidat úkol" @click="open" />
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
    <q-card style="min-width: 70vw;">
      <q-form ref="form" @submit="onSubmit">
        <q-card-section class="row items-center q-pb-none">
          <div class="text-h6">{{ dialogTitle }}</div>
          <q-space />
          <q-btn v-close-popup dense flat icon="close" round />
        </q-card-section>

        <q-card-section>
          <div class="row q-gutter-md">
            <div class="col">
              <q-input
                v-model="task.name"
                :rules="[rules.required]"
                filled
                label="Název"
                lazy-rules
              />
            </div>
            <div class="col">
              <q-select
                v-model="task.stationId"
                :display-value="stations.find(option => option.id === task.stationId)?.name"
                :options="stations"
                :rules="[rules.required]"
                emit-value
                filled
                label="Stanoviště"
                lazy-rules
                option-label="name"
                option-value="id"
              />
            </div>
            <div class="col">
              <q-select
                v-model="task.difficulty"
                :display-value="enums.difficulties.find(option => option.key === task.difficulty)?.value"
                :options="enums.difficulties"
                :rules="[rules.required]"
                emit-value
                filled
                label="Obtížnost"
                lazy-rules
                option-label="value"
                option-value="key"
              />
            </div>
          </div>
          <div class="row">
            <div class="col">
              <h5 class="text-subtitle2 q-my-sm">Zvuky</h5>
            </div>
          </div>
          <div class="row q-gutter-md">
            <div class="col">
              <q-select
                v-model="task.soundCsId"
                :display-value="sounds.find(option => option.id === task.soundCsId)?.name"
                :options="sounds"
                :rules="[rules.required]"
                emit-value
                filled
                label="Zvuk CZ"
                lazy-rules
                option-label="name"
                option-value="id"
              />
            </div>
            <div class="col">
              <q-select
                v-model="task.soundEnId"
                :display-value="sounds.find(option => option.id === task.soundEnId)?.name"
                :options="sounds"
                :rules="[rules.required]"
                emit-value
                filled
                label="Zvuk EN"
                lazy-rules
                option-label="name"
                option-value="id"
              />
            </div>
            <div class="col">
              <q-select
                v-model="task.soundDeId"
                :display-value="sounds.find(option => option.id === task.soundDeId)?.name"
                :options="sounds"
                :rules="[rules.required]"
                emit-value
                filled
                label="Zvuk DE"
                lazy-rules
                option-label="name"
                option-value="id"
              />
            </div>
          </div>
          <div class="row">
            <div class="col">
              <h5 class="text-subtitle2 q-my-sm">Odpovědi</h5>
            </div>
          </div>
          <div class="row q-gutter-md">
            <div class="col">
              <q-select
                v-model="task.responseNumber"
                :display-value="enums.numbers.find(option => option.key === task.responseNumber)?.value"
                :options="enums.numbers"
                :rules="[rules.required]"
                emit-value
                filled
                label="Číslo"
                lazy-rules
                option-label="value"
                option-value="key"
              />
            </div>
            <div class="col">
              <q-select
                v-model="task.responseColor"
                :display-value="enums.colors.find(option => option.key === task.responseColor)?.value"
                :options="enums.colors"
                :rules="[rules.required]"
                emit-value
                filled
                label="Barva"
                lazy-rules
                option-label="value"
                option-value="key"
              />
            </div>
            <div class="col">
              <q-select
                v-model="task.responseShape"
                :display-value="enums.shapes.find(option => option.key === task.responseShape)?.value"
                :options="enums.shapes"
                :rules="[rules.required]"
                emit-value
                filled
                label="Tvar"
                lazy-rules
                option-label="value"
                option-value="key"
              />
            </div>
          </div>
          <div class="row">
            <div class="col">
              <h5 class="text-subtitle2 q-my-sm">Body</h5>
            </div>
          </div>
          <div class="row q-gutter-md">
            <div class="col">
              <q-input
                v-model="task.pointsIncorrect"
                :rules="[rules.required]"
                filled
                label="Špatně"
                lazy-rules
                type="number"
              />
            </div>
            <div class="col">
              <q-input
                v-model="task.pointsPartial"
                :rules="[rules.required]"
                filled
                label="Část"
                lazy-rules
                type="number"
              />
            </div>
            <div class="col">
              <q-input
                v-model="task.pointsCorrect"
                :rules="[rules.required]"
                filled
                label="Správně"
                lazy-rules
                type="number"
              />
            </div>
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
