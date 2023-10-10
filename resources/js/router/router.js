import { createRouter, createWebHashHistory } from 'vue-router';

export const router = createRouter({
  history: createWebHashHistory(),
  routes: [
    {
      path: '/game_start',
      name: 'game_start',
      component: () => import('../pages/GameStart.vue')
    },
    {
      path: '/game_end',
      name: 'game_end',
      component: () => import('../pages/GameEnd.vue')
    },
    {
      path: '/game_players',
      name: 'game_players',
      component: () => import('../pages/GamePlayers.vue')
    }
  ]
})
