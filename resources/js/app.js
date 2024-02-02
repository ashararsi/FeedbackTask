/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

import './bootstrap';
import { createApp } from 'vue';

/**
 * Next, we will create a fresh Vue application instance. You may then begin
 * registering components with the application instance so they are ready
 * to use in your application's views. An example is included for you.
 */

const app = createApp({});


import ExampleComponent from './components/ExampleComponent.vue';
import create from './feedback/create.vue';
import index from './feedback/index.vue';
import edit from './feedback/edit.vue';
import profile_edit from './profile/profile.vue';
import feedback_home from './home/feedback.vue';


app.component('example-component', ExampleComponent);
app.component('create', create);
app.component('index', index);
app.component('edit', edit);
app.component('profile_edit', profile_edit);
app.component('feedback_home', feedback_home);



app.mount('#app');
