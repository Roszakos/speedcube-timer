import { createStore } from 'vuex'
import axiosClient from '../axios.js';

const store = createStore({
    state: {
        user: {
            data: {},
            token: sessionStorage.getItem('TOKEN')
        }
    },
    actions: {
        register({commit}, user) {
            return axiosClient.post('/register', user)
                .then(({data}) => {
                    commit('setUser', data)
                    return data;
                })
                .catch((err) => {
                    throw err
                })
        },
        login({commit}, credentials) {
            return axiosClient.post('/login', credentials)
                .then(({data}) => {
                    commit('setUser', data)
                    return data
                })
                .catch((err) => {
                    throw err
                })
        },
        logout({commit}) {
            return axiosClient.post('/logout')
                .then(response => {
                    commit('logout')
                    return response
                })
                .catch((err) => {
                    throw err
                })
        }
    },
    mutations: {
        setUser: (state, userData) => {
            state.user.token = userData.token
            state.user.data = userData.user
            sessionStorage.setItem('TOKEN', userData.token)
        },
        logout: (state) => {
            state.user.token = null
            state.user.data = {}
            sessionStorage.clear()
        }
    },
    getters: {},
    modules: {}
})

export default store;