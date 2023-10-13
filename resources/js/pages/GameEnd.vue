<script setup>
import { useQuasar } from 'quasar';
import { ref } from 'vue';
import { deleteGame } from '../api.js';
import { rules } from '../rules.js';

const $q = useQuasar();

const chip = ref(null);
const form = ref();

function onSubmit () {
  if (!form.value.validate()) {
    return;
  }

  deleteGame(chip.value).then(() => {
    form.value.resetValidation();
    chip.value = null;

    $q.notify({
      color: 'positive',
      message: 'Hra byla ukončena.',
      position: 'bottom-right'
    });
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
</template>
