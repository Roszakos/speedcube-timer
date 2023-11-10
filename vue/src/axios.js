import axios from "axios"
import store from "./store"
import router from "./router"


const axiosClient = axios.create({
    baseURL: 'http://localhost:8000/api'
})


axiosClient.interceptors.request.use(config => {
    config.headers.Authorization = `Bearer ${store.state.user.token}`
    return config
})

axiosClient.interceptors.response.use(response => {
    return response;
 }, error => {
   if (error.response.status === 401) {
    store.state.flashData.loggedOut = true
    store.commit('logout')
    router.push({name: 'Login'})
   }
   return error;
 });

export default axiosClient 