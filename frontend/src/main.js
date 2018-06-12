// The Vue build version to load with the `import` command
// (runtime-only or standalone) has been set in webpack.base.conf with an alias.
import Vue from 'vue'
import App from './App'
import router from './router'
import Axios from 'Axios';//config api
import PreLayout from '@/components/PreLayout'

const axios = Axios.create();//create instance
axios.defaults.baseURL = 'https://akela.mendelu.cz/~xdvorak/wa/public/'; // public path
Vue.prototype.$http = axios; //registration into components

Vue.config.productionTip = false;

var token = localStorage.getItem('token');
if (token){
    axios.defaults.headers.common['Authorization'] = token;
}

router.beforeEach((to, from, next)=>{
    if(axios.defaults.headers.common['Authorization']){
        //token ok
        next();    
   }else if(to.name === 'preLayout'){
        //no token => redir login/registration
        next({name: PreLayout});
    }else{
        //redir login   
        next({name: PreLayout});
    }
});



/* eslint-disable no-new */
new Vue({
  el: '#app',
  router,
  components: { 'App': App },
  template: '<App/>',
})
