<script setup>
import { rules } from '../rules.js';
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
          <q-form ref="form" class="q-gutter-md">
            <q-input
              :rules="[rules.required]"
              filled
              label="Čip"
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
