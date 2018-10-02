
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

//Vue.component('example-component', require('./components/ExampleComponent.vue'));
Vue.config.devtools = true ;
Vue.config.performance = true;
window.axios.defaults.headers.common = {
    'X-Requested-With': 'XMLHttpRequest',
};

const app = new Vue({
    el: '#ss',
    data:{
        message:"This is a VUE text modifiss   asdfa ssaaaa asdfasdf safasdfasdfs sed",
        secondText:"this is a second text ss aa",
        counter : 0
    },
    methods: {
        doClick : function(event){
          this.counter+=1;
          console.log('sssss');
            window.axios.get('/oauth/scopes').then(({ data }) => {
                 console.log(data)
            });

        }

    }

});
//https://blog.pusher.com/web-application-laravel-vue-part-2/
//https://blog.pusher.com/make-an-oauth2-server-using-laravel-passport/
//https://blog.pusher.com/web-application-laravel-vue-part-3/
app.counter = 0;