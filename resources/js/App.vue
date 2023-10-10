<script setup>
const name = 'Vue 3';
</script>

<template>
  <q-layout view="hHh Lpr lFf">
    <q-header elevated>
      <q-toolbar>
        <q-icon name="extension" size="26px" />

        <q-toolbar-title>
          {{ 'Baby Permon' }}
        </q-toolbar-title>

        <q-btn push color="white" text-color="black" label="Odhlášení" />
      </q-toolbar>
    </q-header>

    <q-drawer
      show-if-above
      bordered
    >
      <q-list>
        <q-item-label header>
          {{ 'Pokladna' }}
        </q-item-label>

        <q-item v-ripple clickable :to="{ name: 'game_start' }">
          <q-item-section avatar>
            <q-icon name="add" />
          </q-item-section>
          <q-item-section>
            {{ 'Založit hru' }}
          </q-item-section>
        </q-item>

        <q-item v-ripple clickable :to="{ name: 'game_end' }">
          <q-item-section avatar>
            <q-icon name="remove" />
          </q-item-section>
          <q-item-section>
            {{ 'Ukončit hru' }}
          </q-item-section>
        </q-item>

        <q-item v-ripple clickable :to="{ name: 'game_players' }">
          <q-item-section avatar>
            <q-icon name="group" />
          </q-item-section>
          <q-item-section>
            {{ 'Hráči' }}
          </q-item-section>
        </q-item>

        <q-separator />

        <q-item-label header>
          {{ 'Administrace' }}
        </q-item-label>

        <q-item v-ripple clickable>
          <q-item-section avatar>
            <q-icon name="music_note" />
          </q-item-section>
          <q-item-section>
            {{ 'Zvuky' }}
          </q-item-section>
        </q-item>

        <q-item v-ripple clickable>
          <q-item-section avatar>
            <q-icon name="location_on" />
          </q-item-section>
          <q-item-section>
            {{ 'Umístění' }}
          </q-item-section>
        </q-item>

        <q-item v-ripple clickable>
          <q-item-section avatar>
            <q-icon name="checklist" />
          </q-item-section>
          <q-item-section>
            {{ 'Úkoly' }}
          </q-item-section>
        </q-item>

        <q-item v-ripple clickable>
          <q-item-section avatar>
            <q-icon name="show_chart" />
          </q-item-section>
          <q-item-section>
            {{ 'Obtížnost' }}
          </q-item-section>
        </q-item>
      </q-list>
    </q-drawer>

    <q-page-container>
      <router-view />
    </q-page-container>
  </q-layout>
</template>
