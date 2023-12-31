import { createRouter, createWebHashHistory } from 'vue-router';

export const router = createRouter({
  scrollBehavior: () => ({ left: 0, top: 0 }),
  history: createWebHashHistory(),
  routes: [
    {
      path: '/',
      redirect: '/game/players'
    },
    {
      path: '/login',
      name: 'login',
      component: () => import('../pages/Login.vue')
    },
    {
      path: '/game/start',
      name: 'game_start',
      component: () => import('../pages/GameStart.vue')
    },
    {
      path: '/game/end',
      name: 'game_end',
      component: () => import('../pages/GameEnd.vue')
    },
    {
      path: '/game/players',
      name: 'game_players',
      component: () => import('../pages/GamePlayers.vue')
    },
    {
      path: '/game/overview',
      name: 'game_overview',
      component: () => import('../pages/GameOverview.vue')
    },
    {
      path: '/admin/sounds',
      name: 'admin_sounds',
      component: () => import('../pages/AdminSounds.vue')
    },
    {
      path: '/admin/locations',
      name: 'admin_locations',
      component: () => import('../pages/AdminLocations.vue')
    },
    {
      path: '/admin/tasks',
      name: 'admin_tasks',
      component: () => import('../pages/AdminTasks.vue')
    },
    {
      path: '/admin/levels',
      name: 'admin_levels',
      component: () => import('../pages/AdminLevels.vue')
    }
  ]
});

function authGuard(to, from, next) {
  if (!to.name.startsWith('admin_')) {
    next();
  } else {
    if (localStorage.getItem('token')) {
      next();
    } else {
      next('/login');
    }
  }
}

router.beforeEach(authGuard);
