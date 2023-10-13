<script setup>
// JeEvp9AM0h0kTIkQ
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

  if (password.value === 'JeEvp9AM0h0kTIkQ') {
    localStorage.setItem('token', 'true');
    router.push({ name: 'game_start' });
  }
}
</script>

<template>
  <q-layout>
    <q-page-container>
      <q-page class="flex justify-center items-center">
        <div class="row" style="min-width: 500px;">
          <div class="col">
            <q-form ref="form" @submit="onSubmit">
              <q-card class="q-pa-lg shadow-1">
                <q-card-section>
                  <q-form class="q-gutter-md">
                    <q-input autofocus square filled clearable type="password" label="Heslo" v-model="password" :rules="[rules.required]" />
                  </q-form>
                </q-card-section>
                <q-card-actions class="q-px-md">
                  <q-btn type="submit" unelevated color="primary" size="lg" class="full-width" label="Přihlásit se" />
                </q-card-actions>
              </q-card>
            </q-form>
          </div>
        </div>
      </q-page>
    </q-page-container>
  </q-layout>
</template>
