require('./bootstrap');

import Vue from 'vue'
import router from './router'
import ViewUI from 'view-design';
import 'view-design/dist/styles/iview.css';
import common from './common';




Vue.component('mainto',require('./components/mainto.vue').default);
Vue.use(ViewUI);
Vue.mixin(common)


// import JQuery from 'jquery'
// window.$ = JQuery


const app=new Vue({
    el:"#app",
    router
})



