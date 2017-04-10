import Vue from 'vue'
import Router from 'vue-router'
import addcase from '../components/addcase'
import personalCount from '../components/personalCount'
import account from '../components/account'
import login from '../components/login'
import register from '../components/register'
import clearAccount from '../components/clearAccount'
import home from '../components/home'

Vue.use(Router)

export default new Router({
    routes: [{
            path: '/',
            component: login
        },
        {
            path: '/home',
            component: home,
            children: [{
                    path: '/addcase',
                    component: addcase
                },
                {
                    path: '/personalCount',
                    component: personalCount
                },
                {
                    path: '/account',
                    component: account
                },
                {
                    path: '/clearAccount',
                    component: clearAccount
                }
            ]
        },
        {
            path: '/register',
            component: register
        }
    ]
})