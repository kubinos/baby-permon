<script setup>

</script>

<template>
  <div class="row">
    <div class="col">
      <h1 class="text-h4">Hráči ve hře</h1>
    </div>
  </div>

  <div class="row">
    <div class="col">
      <q-table
        :pagination="{ rowsPerPage: 20 }"
        bordered
        flat
      />
    </div>
  </div>
</template>
