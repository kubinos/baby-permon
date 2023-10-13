<script setup>
import { useQuasar } from 'quasar';
import { onMounted, ref } from 'vue';
import { getLevels, updateLevels } from '../api.js';
import { rules } from '../rules.js';

const $q = useQuasar();

const levels = ref({
  level_1: null,
  level_2: null,
  level_3: null
});

const form = ref(null);

onMounted(() => {
  form.value.resetValidation();

  getLevels().then(({ data }) => {
    levels.value = data.data;
  });
});

function onSubmit () {
  if (!form.value.validate()) {
    return;
  }

  updateLevels(levels.value).then(() => {
    $q.notify({
      color: 'positive',
      message: 'Obtížnost byla nastavena.',
      position: 'bottom-right'
    });
  }).catch(() => {
    $q.notify({
      color: 'negative',
      message: 'Obtížnost se nepodařilo nastavit.',
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
          <h1 class="text-h4">Nastavení obtížnosti</h1>
        </div>
      </div>

      <div class="row">
        <div class="col">
          <q-form ref="form" class="q-gutter-md" @submit="onSubmit">
            <q-input
              v-model="levels.level_1"
              :rules="[rules.required]"
              filled
              label="Úroveň 1"
              lazy-rules
            />
            <q-input
              v-model="levels.level_2"
              :rules="[rules.required]"
              filled
              label="Úroveň 2"
              lazy-rules
            />
            <q-input
              v-model="levels.level_3"
              :rules="[rules.required]"
              filled
              label="Úroveň 3"
              lazy-rules
            />

            <div>
              <q-btn
                color="primary"
                label="Nastavit"
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
