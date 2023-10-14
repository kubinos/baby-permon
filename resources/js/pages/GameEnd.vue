<script setup>
import { useQuasar } from 'quasar';
import { computed, onMounted, ref } from 'vue';
import { deleteGame, getEnums, getLevels } from '../api.js';
import { rules } from '../rules.js';

const $q = useQuasar();

const dialog = ref(false);
const levels = ref({});
const chip = ref(null);
const form = ref();
const deletedGame = ref({});
const enums = ref({
  emotions: []
});

const success = computed(() => {
  if (!levels.value || !deletedGame.value) {
    return false;
  }

  return deletedGame.value.points >= levels.value[`level_${deletedGame.value.level}`];
});

onMounted(() => {
  getLevels().then(({ data }) => {
    levels.value = data.data;
  });

  getEnums().then(({ data }) => {
    enums.value = data;
  });
});

function onSubmit () {
  if (!form.value.validate()) {
    return;
  }

  deleteGame(chip.value).then(({ data }) => {
    form.value.resetValidation();
    chip.value = null;

    deletedGame.value = data.data;
    dialog.value = true;
  }).catch(() => {
    $q.notify({
      color: 'negative',
      message: 'Hru se nepodařilo ukončit.',
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
          <h1 class="text-h4">Ukončení hry</h1>
        </div>
      </div>

      <div class="row">
        <div class="col">
          <q-form ref="form" class="q-gutter-md" @submit="onSubmit">
            <q-input
              v-model="chip"
              :rules="[rules.required]"
              filled
              label="Čip"
              lazy-rules
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

  <q-dialog persistent v-model="dialog">
    <q-card v-if="deletedGame" class="q-pa-md" style="min-width: 300px;">
      <q-card-section class="row items-center q-pb-none">
        <div :class="{ 'text-negative': !success, 'text-positive': success }" class="text-h4">
          {{ success ? 'Hra byla úspěšně dokončena' : 'Hra byla ukončena' }}
        </div>
      </q-card-section>

      <q-card-section>
        <div class="text-h6">
          Obtížnost: <strong>{{ deletedGame.level }}</strong><br>
          Počet bodů: <strong>{{ deletedGame.points }}</strong><br>
          Oslovení: <strong>{{ deletedGame.salutation }}</strong><br>
          Nálada: <strong>{{ enums.emotions.find(e => e.key === deletedGame.emotion)?.value ?? deletedGame.emotion }}</strong>
        </div>
      </q-card-section>

      <q-card-actions align="center">
        <q-btn v-close-popup class="full-width" color="primary" outline label="Zavřít" />
      </q-card-actions>
    </q-card>
    <q-card v-else class="q-pa-md" style="min-width: 300px;">
      <q-card-section class="row items-center q-pb-none">
        <div class="text-h4">
          Hra nenalezena.
        </div>
      </q-card-section>

      <q-card-section>
        <div class="text-h6">
          Hra s tímto čipem nebyla nalezena. Zkontrolujte, zda jste čip zadali správně.
        </div>
      </q-card-section>

      <q-card-actions align="center">
        <q-btn v-close-popup class="full-width" color="primary" outline label="Zavřít" />
      </q-card-actions>
    </q-card>
  </q-dialog>
</template>
