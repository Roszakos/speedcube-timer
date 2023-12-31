import { createStore } from 'vuex'
import axiosClient from '../axios.js';

const store = createStore({
    state: {
        user: {
            data: JSON.parse(localStorage.getItem('user')),
            token: localStorage.getItem('TOKEN'),
            verifyEmail: false
        },
        session: {
            loading: false,
            hash: null,
            puzzle: '3x3',
            times: []
        },
        currentSolve: {
            time: null,
            scramble: ''
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
        },
        profile: {
            sessions: []
        },
        notification: {
            show: false,
            type: null,
            message: null
        },
        flashData: {
            accountDeleted: false,
            loggedOut: false
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
                .then((response) => {
                    if (response.status === 200) {
                    commit('setUser', response.data)
                    }
                    return response
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

            return axiosClient.post('/solve', data)
                .then(response => {
                    return response
                })
                .catch(err => {
                    throw err
                })
        },
        updateSolve({commit}, {hash, plus2, dnf}) {
            const data = {
                plus2: plus2,
                dnf: dnf
            }
            return axiosClient.put(`/solve/${hash}`, data)
                .then(response => {
                    return response
                })
                .catch(err => {
                    throw err
                })
        },
        deleteSolve({commit}, solveHash) {
            return axiosClient.delete(`/solve/${solveHash}`)
                .then(response => {
                    return response
                })
                .catch(err => {
                    throw err
                })
        },
        getSessionId({commit}) {
            if (localStorage.getItem('SESSION_ID')) {
                commit('setSessionId', localStorage.getItem('SESSION_ID'))
                return true
            } else {
                let result = ''
                const characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789'
                const charactersLength = characters.length
                let counter = 0
                while (counter < 22) {
                    result = result + characters.charAt(Math.floor(Math.random() * charactersLength))
                    counter = counter + 1
                }

                localStorage.setItem('SESSION_ID', result)
                commit('setSessionId', localStorage.getItem('SESSION_ID'))
                return false
            }
        },
        createNewSessionId({commit}) {
            let result = ''
            const characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789'
            const charactersLength = characters.length
            let counter = 0
            while (counter < 22) {
                result = result + characters.charAt(Math.floor(Math.random() * charactersLength))
                counter = counter + 1
            }

            localStorage.setItem('SESSION_ID', result)
            commit('setSessionId', localStorage.getItem('SESSION_ID'))
        },
        loadSolves({commit}, sessionHash) {
            commit('setSessionLoading', true)
            return axiosClient.get(`/session/${sessionHash}`)
                .then(response => {
                    if(response.data) {
                        commit('setSolves', response.data)
                        commit('setSessionLoading', false)
                        return true
                    } else {
                        commit('setSolves', [])
                        commit('setSessionLoading', false)
                        return false
                    }
                })
                .catch(err => {
                    commit('setSessionLoading', false)
                    if (err.response.status === 401) {
                        commit('logout')
                        return 401
                    }
                    //throw err
                })
        },
        getProfileData({commit}) {
            return axiosClient.get(`/session`)
                .then(response => {
                    commit('setProfileData', response.data)
                    return response
                })
                .catch(err => {
                    if (err.response.status === 401) {
                        commit('logout')
                        return 401
                    }
                    throw err
                })
        },
        updateUser({commit}, userData) {
            return axiosClient.put(`/user`, userData)
                .then(response => {
                    commit('setUser', response.data)
                    return response
                })
                .catch(err => {
                    throw err
                })
        },
        changePassword({commit}, data) {
            return axiosClient.put(`/password`, data)
                .then(response => {
                    return response
                })
                .catch(err => {
                    throw err
                })
        },
        deleteAccount({commit}, data) {
            return axiosClient.post(`/user`, data)
                .then(response => {
                    return response
                })
                .catch(err => {
                    throw err
                })
        },
        getUserData({commit}) {
            return axiosClient.get(`/user`)
                .then(() => {
                    commit('setUser')
                })
        },
        verifyEmail({commit}, url) {
            return axiosClient.get(url)
                .then(response => {
                    return response
                }) 
                .catch(err => {
                    throw err
                })
        },
        resendEmailVerificationLink({commit}) {
            return axiosClient.post(`/email/verification-notification`)
                .then((response) => {
                    return response
                })
        },
        sendPasswordResetLink({commit}, email) {
            return axiosClient.post(`/forgot-password`, {email: email})
                .then((response) => {
                    return response
                })
        },
        resetPassword({commit}, data) {
            return axiosClient.post(`/reset-password`, data)
                .then((response) => {
                    return response
                })
        }
    },
    mutations: {
        setUser: (state, userData) => {
            if(userData.token) {
                state.user.token = userData.token
                localStorage.setItem('TOKEN', userData.token)
            }
            state.user.data = userData.user
            localStorage.setItem('user', JSON.stringify(userData.user))
        },
        logout: (state) => {
            state.user.token = null
            state.user.data = {}
            localStorage.clear()
        },
        setSessionId: (state, sessionHash) => {
            state.session.hash = sessionHash
        },
        setSolves: (state, data) => {
            state.session.times = data
        },
        setProfileUserData: (state, data) => {
            state.profile.user = data
        },
        setProfileData: (state, data) => {
            state.profile.sessions = data.sessions
        },
        setSessionLoading: (state, status) => {
            state.session.loading = status
        },
        notify: (state, {type, message}) => {
            state.notification.type = type,
            state.notification.message = message,
            state.notification.show = true
            setTimeout(() => {
                state.notification.show = false
            }, 3000)
        }
    },
    getters: {},
    modules: {}
})

export default store;