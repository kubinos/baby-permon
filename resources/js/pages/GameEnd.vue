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
            :rules="[val => !!val || 'Název hry je povinný']"
            filled
            label="Čip"
            lazy-rules
          />
        </div>

        <q-btn
          color="primary"
          label="Ukončit hru"
          type="submit"
          unelevated
        />
      </q-form>
    </div>
  </div>
</template>
