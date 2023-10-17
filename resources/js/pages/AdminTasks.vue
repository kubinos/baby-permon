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

const answers = [
  {
    label: 'Nic',
    value: '0'
  },
  {
    label: 'Modrá',
    value: 'b'
  },
  {
    label: 'Zelená',
    value: 'g'
  },
  {
    label: 'Červená',
    value: 'r'
  },
  {
    label: 'Žlutá',
    value: 'y'
  },
  {
    label: 'Obdelník',
    value: 'o'
  },
  {
    label: 'Hvězda',
    value: 't'
  },
  {
    label: 'Kruh',
    value: 'c'
  },
  {
    label: 'Čtverec',
    value: 's'
  },
  {
    label: 'Číslo 1',
    value: '1'
  },
  {
    label: 'Číslo 2',
    value: '2'
  },
  {
    label: 'Číslo 3',
    value: '3'
  },
  {
    label: 'Číslo 4',
    value: '4'
  }
];

const form = ref(null);
const dialog = ref(false);
const dialogTitle = ref('Vytvořit nový úkol');
const rows = ref();
const enums = ref({
  difficulties: []
});

const soundsTemp = ref();
const sounds = ref();
const stations = ref();

const task = ref({
  name: null,
  difficulty: null,
  stationId: null,
  soundCsId: null,
  soundEnId: null,
  soundDeId: null,
  correct1: '0',
  correct2: '0',
  correct3: '0',
  pointsCorrect: null,
  partial1: '0',
  partial2: '0',
  partial3: '0',
  pointsPartial: null
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
    soundsTemp.value = data.data;
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
      correct1: '0',
      correct2: '0',
      correct3: '0',
      pointsCorrect: null,
      partial1: '0',
      partial2: '0',
      partial3: '0',
      pointsPartial: null
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
    correct1: data.correct1,
    correct2: data.correct2,
    correct3: data.correct3,
    pointsCorrect: data.pointsCorrect,
    partial1: data.partial1,
    partial2: data.partial2,
    partial3: data.partial3,
    pointsPartial: data.pointsPartial
  };

  if (data.hasOwnProperty('id')) {
    dialogTitle.value = 'Upravit úkol';
  }
}

function onSubmit () {
  if (!form.value.validate()) {
    return;
  }

  const isEdit = task.value.id !== undefined;

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

function filter (val, update, abort) {
  update(() => {
    const needle = val.toLowerCase();
    soundsTemp.value = sounds.value.filter(v => v.name.toLowerCase().indexOf(needle) > -1);
  });
}

function filterReset () {
  soundsTemp.value = [...sounds.value];
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
                :options="soundsTemp"
                :rules="[rules.required]"
                emit-value
                filled
                use-input
                label="Zvuk CZ"
                lazy-rules
                option-label="name"
                option-value="id"
                @filter="filter"
                @focusout="filterReset"
              />
            </div>
            <div class="col">
              <q-select
                v-model="task.soundEnId"
                :display-value="sounds.find(option => option.id === task.soundEnId)?.name"
                :options="soundsTemp"
                :rules="[rules.required]"
                emit-value
                filled
                use-input
                label="Zvuk EN"
                lazy-rules
                option-label="name"
                option-value="id"
                @filter="filter"
                @focusout="filterReset"
              />
            </div>
            <div class="col">
              <q-select
                v-model="task.soundDeId"
                :display-value="sounds.find(option => option.id === task.soundDeId)?.name"
                :options="soundsTemp"
                :rules="[rules.required]"
                emit-value
                filled
                use-input
                label="Zvuk DE"
                lazy-rules
                option-label="name"
                option-value="id"
                @filter="filter"
                @focusout="filterReset"
              />
            </div>
          </div>
          <div class="row">
            <div class="col">
              <h5 class="text-subtitle2 q-my-sm">Správná odpověď</h5>
            </div>
          </div>
          <div class="row q-gutter-md">
            <div class="col">
              <q-select
                v-model="task.correct1"
                :display-value="answers.find(option => option.value === task.correct1)?.label"
                :options="answers"
                :rules="[rules.required]"
                emit-value
                filled
                label="Pole 1"
                lazy-rules
              />
            </div>
            <div class="col">
              <q-select
                v-model="task.correct2"
                :display-value="answers.find(option => option.value === task.correct2)?.label"
                :options="answers"
                :rules="[rules.required]"
                emit-value
                filled
                label="Pole 2"
                lazy-rules
              />
            </div>
            <div class="col">
              <q-select
                v-model="task.correct3"
                :display-value="answers.find(option => option.value === task.correct3)?.label"
                :options="answers"
                :rules="[rules.required]"
                emit-value
                filled
                label="Pole 3"
                lazy-rules
              />
            </div>
            <div class="col">
              <q-input
                v-model="task.pointsCorrect"
                :rules="[rules.required]"
                filled
                label="Počet bodů"
                lazy-rules
                type="number"
              />
            </div>
          </div>
          <div class="row">
            <div class="col">
              <h5 class="text-subtitle2 q-my-sm">Částečná odpověď</h5>
            </div>
          </div>
          <div class="row q-gutter-md">
            <div class="col">
              <q-select
                v-model="task.partial1"
                :display-value="answers.find(option => option.value === task.partial1)?.label"
                :options="answers"
                :rules="[rules.required]"
                emit-value
                filled
                label="Pole 1"
                lazy-rules
              />
            </div>
            <div class="col">
              <q-select
                v-model="task.partial2"
                :display-value="answers.find(option => option.value === task.partial2)?.label"
                :options="answers"
                :rules="[rules.required]"
                emit-value
                filled
                label="Pole 2"
                lazy-rules
              />
            </div>
            <div class="col">
              <q-select
                v-model="task.partial3"
                :display-value="answers.find(option => option.value === task.partial3)?.label"
                :options="answers"
                :rules="[rules.required]"
                emit-value
                filled
                label="Pole 3"
                lazy-rules
              />
            </div>
            <div class="col">
              <q-input
                v-model="task.pointsPartial"
                :rules="[rules.required]"
                filled
                label="Počet bodů"
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
