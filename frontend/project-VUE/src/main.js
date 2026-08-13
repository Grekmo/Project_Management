import './assets/main.css'

import { createApp } from 'vue'
import { createPinia } from 'pinia'


import App from './App.vue'
import router from './router'

import 'bootstrap/dist/css/bootstrap.min.css'
import 'bootstrap/dist/js/bootstrap.bundle.min.js'
//import 'bootstrap'
import 'bootstrap-icons/font/bootstrap-icons.css'

import Toast from 'vue-toastification'
import 'vue-toastification/dist/index.css'

const app = createApp(App)

app.use(createPinia())
app.use(router)

app.use(Toast, {
    position: "top-right",
    timeout: 3000,
    closeOnClick: true,
    pauseOnHover: true,
    draggable: true
});


app.mount('#app')





