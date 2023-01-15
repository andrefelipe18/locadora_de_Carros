 /**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

import './bootstrap';
import store from './store';
import { createApp } from 'vue';

/* importando e configurando o vuex */
import Vuex from 'vuex';

/**
 * Next, we will create a fresh Vue application instance. You may then begin
 * registering components with the application instance so they are ready
 * to use in your application's views. An example is included for you.
 */

const app = createApp({});


import ExampleComponent from './components/ExampleComponent.vue';
app.component('example-component', ExampleComponent);


//Registrando o componente login
import Login from './components/Login.vue';
app.component('login-component', Login);

//Registrando o componente home
import Home from './components/Home.vue';
app.component('home-component', Home);

//Registrando o componente marcas
import Marcas from './components/Marcas.vue';
app.component('marcas-component', Marcas);

//Registrando o componente que vai conter os cards do bootstrap
import Card from './components/Card.vue';
app.component('card-component', Card);

//Registrando o componnente InputContianer
import InputContainer from './components/InputContainer.vue';
app.component('input-container-component', InputContainer);

//Registrando o componente da tabela
import Table from './components/Table.vue';
app.component('table-component', Table);

//Registrando o componente do modal
import Modal from './components/Modal.vue';
app.component('modal-component', Modal);

//Registrando o componente de alert
import Alert from './components/Alert.vue';
app.component('alert-component', Alert);

//Registrando o componente de paginate
import Paginate from './components/Paginate.vue';
app.component('paginate-component', Paginate);
/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// Object.entries(import.meta.glob('./**/*.vue', { eager: true })).forEach(([path, definition]) => {
//     app.component(path.split('/').pop().replace(/\.\w+$/, ''), definition.default);
// });

/**
 * Finally, we will attach the application instance to a HTML element with
 * an "id" attribute of "app". This element is included with the "auth"
 * scaffolding. Otherwise, you will need to add an element yourself.
 */
app.use(store)
app.mount('#app');
