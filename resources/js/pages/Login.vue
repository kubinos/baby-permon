<script setup>
// bp-permon
import { ref } from 'vue';
import { useRouter } from 'vue-router';
import { rules } from '../rules.js';

const router = useRouter();

const password = ref('');
const form = ref();

function onSubmit () {
  if (!form.value.validate()) {
    return;
  }

  if (password.value === 'bp-permon') {
    localStorage.setItem('token', 'true');
    router.push({ name: 'admin_tasks' });
  }
}
</script>

<template>

  <div class="row">
    <div class="col offset-md-3 col-md-6 offset-lg-4 col-lg-4">
      <div class="row">
        <div class="col">
          <h1 class="text-h4">Přihlášení do administrace</h1>
        </div>
      </div>

      <div class="row">
        <div class="col">
          <q-form ref="form" class="q-gutter-md" @submit="onSubmit">
            <q-input
              type="password"
              v-model="password"
              :rules="[rules.required]"
              filled
              label="Heslo"
              lazy-rules
            />

            <div>
              <q-btn
                color="primary"
                label="Odeslat"
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
