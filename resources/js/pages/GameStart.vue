<script setup>
import { useQuasar } from 'quasar';
import { onMounted, ref } from 'vue';
import { createGame, getEnums } from '../api.js';
import { rules } from '../rules.js';

const languages = [
  {
    label: 'Čeština',
    value: 'cs'
  },
  {
    label: 'Angličtina',
    value: 'en'
  },
  {
    label: 'Němčina',
    value: 'de'
  }
];

const form = ref();
const enums = ref({
  game_types: [],
  levels: [],
  emotions: []
});

onMounted(() => {
  getEnums().then(({ data }) => {
    enums.value = data;
  });
});

const model = ref({
  chip: null,
  type: 'player',
  salutation: null,
  language: 'cs',
  level: null,
  emotion: null
});

const $q = useQuasar();

function onSubmit () {
  if (!form.value.validate()) {
    return;
  }

  createGame(model.value).then(() => {
    form.value.resetValidation();
    model.value = {
      chip: null,
      salutation: null,
      language: 'cs',
      level: null,
      emotion: null
    };

    $q.notify({
      color: 'positive',
      message: 'Hra byla založena.',
      position: 'bottom-right'
    });
  }).catch(() => {
    $q.notify({
      color: 'negative',
      message: 'Hru se nepodařilo vytvořit.',
      position: 'bottom-right'
    });
  });
}
</script>

<template>

  <div class="row">
    <div class="col offset-md-3 col-md-6 offset-lg-4 col-lg-4">
      <div class="row">
        <div class="col">
          <h1 class="text-h4">Založit hru</h1>
        </div>
      </div>

      <div class="row">
        <div class="col">
          <q-form ref="form" class="q-gutter-md" @submit="onSubmit">
            <q-input
              v-model="model.chip"
              :rules="[rules.required]"
              filled
              label="Čip"
              lazy-rules
            />
            <q-input
              v-model="model.salutation"
              :rules="[rules.required]"
              filled
              label="Oslovení"
              lazy-rules
            />
            <q-select
              v-model="model.type"
              :display-value="enums.game_types.find(option => option.key === model.type)?.value"
              :options="enums.game_types"
              :rules="[rules.required]"
              emit-value
              filled
              label="Typ"
              lazy-rules
              option-label="value"
              option-value="key"
            />
            <q-select
              v-model="model.language"
              :display-value="languages.find(option => option.value === model.language)?.label"
              :options="languages"
              :rules="[rules.required]"
              emit-value
              filled
              label="Jazyk"
              lazy-rules
              :disable="model.type !== 'player'"
            />
            <q-select
              v-model="model.level"
              :display-value="enums.levels.find(option => option.key === model.level)?.value"
              :options="enums.levels"
              :rules="[rules.required]"
              emit-value
              filled
              label="Level"
              lazy-rules
              option-label="value"
              option-value="key"
              :disable="model.type !== 'player'"
            />
            <q-select
              v-model="model.emotion"
              :display-value="enums.emotions.find(option => option.key === model.emotion)?.value"
              :options="enums.emotions"
              :rules="[rules.required]"
              emit-value
              filled
              label="Emoce"
              lazy-rules
              option-label="value"
              option-value="key"
              :disable="model.type !== 'player'"
            />

            <div>
              <q-btn
                color="primary"
                label="Uložit"
                type="submit"
                unelevated
              />
            </div>
          </q-form>
        </div>
      </div>
    </div>
  </div>
</template>
