import { createRouter, createWebHashHistory } from 'vue-router'
import Timer from '../views/Timer.vue'
import Login from '../views/Login.vue'
import Register from '../views/Register.vue'
import AuthLayout from '../components/AuthLayout.vue'
import Profile from '../views/Profile.vue'
import EmailNotify from '../views/EmailNotify.vue'
import store from '../store'
import EmailVerify from '../views/EmailVerify.vue'
import ForgotPassword from '../views/ForgotPassword.vue'
import ResetPassword from '../views/ResetPassword.vue'

const routes = [
    {
        path: '/',
        redirect: '/timer',
        meta: {requiresAuth: true},
        component: Timer,
        children: [
            {
                path: '/timer',
                name: 'Timer',
                component: Timer
            }
        ]
    },
    {
        path: '/auth',
        name: 'Auth',
        redirect: '/login',
        component: AuthLayout,
        meta: {isGuest: true},
        children: [
            {
                path: '/register',
                name: 'Register',
                component: Register
            },
            {
                path: '/login',
                name: 'Login',
                component: Login
            },
            {
                path: '/forgot-password',
                name: 'ForgotPassword',
                component: ForgotPassword
            },
            {
                path: '/reset-password',
                name: 'ResetPassword',
                component: ResetPassword
            }
        ]
    },
    {
        path: '/profile',
        name: 'Profile',
        meta: {requiresAuth: true},
        component: Profile,
        children: [
            {
                path: '/profile/:page',
                component: Profile
            }
        ]
    },
    {
        path: '/email/notify/',
        name: 'EmailNotify',
        meta: {requiresAuth: true},
        component: EmailNotify
    },
    {
        path: '/email/verify/',
        name: 'EmailVerify',
        component: EmailVerify
    },

];

const router = createRouter({
    history: createWebHashHistory(),
    routes
})

router.beforeEach((to, from, next) => {
    if (to.meta.requiresAuth && !store.state.user.token) {
        next({name: 'Login'})
    } else if (to.meta.isGuest && store.state.user.token) {
        next({name: 'Timer'})
    } else {
        next()
    }
})

export default router