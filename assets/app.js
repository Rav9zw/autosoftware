import './styles/app.scss';
import 'devextreme/dist/css/dx.light.css';
import 'bootstrap';

import {createApp} from "vue";
import {createRouter, createWebHistory} from "vue-router"
import App from "./components/App";
import List from "./components/List";
import CreateNewMessage from "./components/CreateNewMessage";


const routes = [
    {path: '/app', component: List},
    {path: '/app/create', component: CreateNewMessage},
]

const router = createRouter({
    history: createWebHistory(),
    routes,
})

const app = createApp({
    components: {
        App
    },
});
app.use(router)
app.mount('#app');


