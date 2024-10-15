import { createApp } from 'vue';
import './bootstrap';
import './utils/helpers'
import izitoast from './plugins/izitoast';

import axios from "axios";


axios.defaults.baseURL = window.laravel.baseurl;

const app = createApp({
    setup(){
        return {
            baseurl: window.laravel.baseurl,
        }
    }
});
app.use(izitoast);

import PurchaseForm from './PurchaseForm.vue';
app.component('purchase-form', PurchaseForm);

const axiosObject = axios.create({
    baseURL: window.laravel.baseurl,
    headers: { common: { 'X-Requested-With': 'XMLHttpRequest' } },
});

axiosObject.interceptors.response.use(response => response, (error) => {
    return handleErrors(error, (message) => {
        app.config.globalProperties.$toast.error(message);
    });
});

window['axios'] = axiosObject;
app.mount('#app');
