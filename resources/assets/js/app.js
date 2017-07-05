
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

Vue.component('example', require('./components/Example.vue'));

// var app = new Vue({
//     el: '#app',
//     data: {
//         message: 'Hello Vue!'
//     }
// })

const app = new Vue({
    el: '#app',

    data: {
        messages: []
    },

    created() {
        this.fetchMessages();
    },
    methods: {
        fetchMessages() {
            axios.get('/home').then(response => {
                this.messages = response.data;
            });
            Echo.private('chat').listen('App\\Events\\SentMessage', (e) => {
                console.log(e.user.data);
                this.messages.push({


                    message: e.message.message,
                    user: e.user
                });
            });
        },



        addMessage(message) {
            this.messages.push(message);

            axios.post('/home', message).then(response => {
                console.log(response.data);
            });
        }
    }
});

