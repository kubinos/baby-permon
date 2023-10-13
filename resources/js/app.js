// Import icon libraries
import '@quasar/extras/material-icons/material-icons.css';
import { Dialog, Notify, Quasar } from 'quasar';
import quasarLang from 'quasar/lang/cs';

// Import Quasar css
import 'quasar/src/css/index.sass';
import { createApp } from 'vue';

// Assumes your root component is App.vue
// and placed in same folder as main.js
import App from './App.vue';

import { router } from './router/router';

const app = createApp(App);

app.use(Quasar, {
  plugins: {
    Notify,
    Dialog
  }, // import Quasar plugins and add here
  lang: quasarLang
});

app.use(router);

// Assumes you have a <div id="app"></div> in your index.html
app.mount('#app');
