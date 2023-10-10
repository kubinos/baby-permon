<script setup>

</script>

<template>
  <div class="row">
    <div class="col">
      <h3 class="q-mb-md">Ukončení hry</h3>
    </div>
  </div>

  <div class="row">
    <div class="col">
      <q-form>
        <div class="q-pb-md q-pt-md">
          <q-input
            filled
            label="Čip"
            lazy-rules
            :rules="[val => !!val || 'Název hry je povinný']"
          />
        </div>

        <q-btn
          unelevated
          type="submit"
          color="primary"
          label="Ukončit hru"
        />
      </q-form>
    </div>
  </div>
</template>
