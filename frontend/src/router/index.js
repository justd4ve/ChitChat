import Vue from 'vue'
import Router from 'vue-router'
import Login from '@/components/Login'
import Register from '@/components/Register'
import Rooms from '@/components/Rooms'
import Room from '@/components/Room'
import User from '@/components/User'
import Profile from '@/components/Profile'
import PreLayout from '@/components/PreLayout'
import InLayout from '@/components/InLayout'
import UserFilter from '@/components/UserFilter'

Vue.use(Router)

export default new Router({
  routes: [
    {     
      path: '/',
      name: 'preLayout',
      component: PreLayout          
    },
    {
      path: '/login',
      name: 'login',
      component: Login
    },
    {
      path: '/register',
      name: 'register',
      component: Register
    },
    {
      path: '/rooms',
      name: 'rooms',
      component: Rooms
    },
    {
      path: '/room/:id',  //placeholder
      name: 'room',
      component: Room
    },
    {
      path: '/user/:id',  
      name: 'user',
      component: User
    },
    {
      path: '/profile/:id', 
      name: 'profile',
      component: Profile
    },
    {
      path: '/auth', 
      name: 'inLayout',
      component: InLayout
    },
    {
      path: '/filter', 
      name: 'userFilter',
      component: UserFilter
    } 
  ]
})
