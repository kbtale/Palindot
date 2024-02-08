import './bootstrap'
import '../css/app.css'
import { createApp } from 'vue/dist/vue.esm-bundler.js'
import Main from '../layouts/main.vue'
import { createRouter } from 'vue-router'
import { routes } from './router.js'

const router = VueRouter.createRouter({
    // 4. Provide the history implementation to use. We are using the hash history for simplicity here.
    history: VueRouter.createWebHashHistory(),
    routes, // short for `routes: routes`
})

const app = createApp()

app.use(router).mount("#app")