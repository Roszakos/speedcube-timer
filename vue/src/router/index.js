import { createRouter, createWebHistory } from 'vue-router'
import Timer from '../views/Timer.vue'
import Login from '../views/Login.vue'
import Register from '../views/Register.vue'
import AuthLayout from '../components/AuthLayout.vue'
import Profile from '../views/Profile.vue'
import EmailNotify from '../views/EmailNotify.vue'
import store from '../store'
import EmailVerify from '../views/EmailVerify.vue'

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
    }
];

const router = createRouter({
    history: createWebHistory(),
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