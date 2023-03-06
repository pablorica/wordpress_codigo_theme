/**
 * Internal dependencies
 */
import Vue from 'vue';
import Example from './vuetemplates/Example.vue';

/**
 * Externall dependencies
 */
import Emitter from 'tiny-emitter';

//console.log('VUE Main loaded')

export var emitter = new Emitter();

export const config = {};


document.addEventListener('DOMContentLoaded', function(event) {
    //console.log('DOMContentLoaded')
    if(document.getElementById("vueExample")){
        const app = Vue.createApp(Example).mount('#vueExample')
    }
})
