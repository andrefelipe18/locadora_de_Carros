import _ from 'lodash';
window._ = _;

import 'bootstrap';

import store from './store';
import { createApp } from 'vue';
const app = createApp({});
app.use(store);

import axios from 'axios';
window.axios = axios;

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

//Interceptar os reuqests da aplicação
axios.interceptors.request.use(config => {

}, error => {
    //return Promise.reject(error);
});

//Interceptar os responses da aplicação
axios.interceptors.response.use(response => {
    //return response;
}, error => {
    //return Promise.reject(error);
})
