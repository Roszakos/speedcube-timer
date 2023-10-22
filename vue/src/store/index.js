import { createStore } from 'vuex'
import axiosClient from '../axios.js';

const store = createStore({
    state: {
        user: {
            data: {},
            token: sessionStorage.getItem('TOKEN')
        },
        session: {
            hash: null,
            puzzle: '3x3',
            times: []
        },
        scrambler: {
            moves: ["R", "R'", "L", "L'", "U", "U'", "D", "D'", "F", "F'", "B", "B'", "R2", "L2", "U2", "D2", "F2", "B2"],
            movesExcludedBy: {
                R: ["R", "R'", "R2"],
                L: ["L", "L'", "L2"],
                U: ["U", "U'", "U2"],
                D: ["D", "D'", "D2"],
                F: ["F", "F'", "F2"],
                B: ["B", "B'", "B2"]
            },
            excludeSustainedBy: {
                R: "L",
                L: "R",
                U: "D",
                D: "U",
                F: "B",
                B: "F"
            }
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
        },
        saveSolve({state}, {time, scramble}) {
            let data = {
                hash: state.session.hash,
                puzzle: state.session.puzzle,
                time: time,
                scramble: scramble
            }

            return axiosClient.put('/solve', data)
                .then(response => {
                    return response
                })
                .catch(err => {
                    throw err
                })
        },
        getSessionData({commit}) {
            return axiosClient.get('/session')
                .then(response => {
                    commit('setSessionData', response.data)
                    return response
                })
                .catch(err => {
                    throw err
                })
        },
        getSessionId({commit}) {
            if (sessionStorage.getItem('SESSION_ID')) {
                commit('setSessionId', sessionStorage.getItem('SESSION_ID'))
            } else {
                let result = ''
                const characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789'
                const charactersLength = characters.length
                let counter = 0
                while (counter < 22) {
                    result = result + characters.charAt(Math.floor(Math.random() * charactersLength))
                    counter = counter + 1
                }

                sessionStorage.setItem('SESSION_ID', result)
                commit('setSessionId', sessionStorage.getItem('SESSION_ID'))
            }
        },
        loadSolves({commit}, data) {
            return axiosClient.post('/solve', data)
                .then(response => {
                    if(response.data) {
                        commit('setSolves', response.data)
                        return response
                    }
                })
                .catch(err => {
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
        },
        setSessionId: (state, sessionHash) => {
            state.session.hash = sessionHash
        },
        setSolves: (state, data) => {
            state.session.times = data
        }
    },
    getters: {},
    modules: {}
})

export default store;