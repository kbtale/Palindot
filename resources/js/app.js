import './bootstrap'
import '../css/app.css'
import { createApp } from 'vue/dist/vue.esm-bundler.js'
import { createRouter, createWebHashHistory } from 'vue-router'
import routes from './router.js'
import App from './app.vue'

const router = createRouter({
    history: createWebHashHistory(),
    routes,
})

const app = createApp(App)

app.use(router).mount("#app")