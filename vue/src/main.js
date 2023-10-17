import { createApp } from 'vue'
import { GlobalEvents } from 'vue-global-events'
import store from './store'
import router from './router'
import App from './App.vue'
import './index.css'

createApp(App)
    .component('GlobalEvents', GlobalEvents)
    .use(store)
    .use(router)
    .mount('#app')
