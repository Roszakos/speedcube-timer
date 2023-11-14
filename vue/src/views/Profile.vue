
<template>
  <Disclosure as="nav" class="bg-slate-600" v-slot="{ open }">
    <div class="mx-auto max-w-full px-4 sm:px-6 lg:px-8">
      <div class="flex h-16 items-center justify-between">
        <div class="flex items-center">
          <router-link :to="{ name: 'Timer' }" class="flex-shrink-0 flex">
            <img class="h-8 w-8" src="https://cdn-icons-png.flaticon.com/512/3430/3430812.png" alt="Speedcube Timer" />
            <span class="text-[#e3c376] ml-4 font-bold text-lg">SpeedcubeTimer.org</span>
          </router-link>
        </div>
        <router-link :to="{ name: 'Timer' }"
          class="text-white text-2xl hidden items-center bg-slate-500/80 rounded-md px-4 py-1 min-[400px]:flex">
          <ArrowLeftOnRectangleIcon class="mr-2 w-7 h-7" />
          <span>Timer</span>
        </router-link>
      </div>
    </div>
  </Disclosure>
  <div
    class="max-w-screen-2xl lg:w-[80%] w-full m-auto lg:mt-24 lg:mb-24 xl:h-[55rem] lg:h-[60rem] h-[58rem]  text-center bg-white relative">
    <div class="flex py-12 h-full">
      <div class="border-r-2 h-full w-[30%] m-auto hidden lg:block">
        <div class="grid grid-cols-1 items-center mt-32">
          <div v-if="profileData" class="hidden lg:block">
            <div
              class="bg-gray-400/50 rounded-full flex flex-col m-auto items-center justify-center relative lg:w-[150px] lg:h-[150px] w-[120px] h-[120px]">
              <div v-if="profileData.user.image">
                <img :src="profileData.user.image" alt="user_profile_image"
                  class="lg:w-[150px] lg:h-[150px] w-[120px] h-[120px] rounded-full ">
              </div>
              <UserIcon v-else class="w-[50%] h-[50%] text-gray-500 absolute" />
            </div>
            <div class="lg:text-2xl text-xl font-bold mt-7">{{ profileData.user.nickname }}</div>
            <div class="lg:text-md text-sm font-semibold mt-1">{{ profileData.user.email }}</div>
          </div>
          <div class="m-auto">
            <div
              class="mt-8 xl:w-48 lg:w-40 w-36 rounded-md bg-white shadow-xl ring-1 ring-black ring-opacity-20 focus:outline-none">
              <div class="cursor-pointer block px-4 py-2 text-sm  rounded-t-md  hover:text-black hover:bg-sky-400/90"
                :class="[route.params.page == 'overview' ? 'bg-sky-300' : '']" @click="router.push('/profile/overview')">
                Overview
              </div>
              <div class="cursor-pointer block px-4 py-2 text-sm hover:text-black hover:bg-sky-400/90"
                :class="[route.params.page == 'sessions' ? 'bg-sky-300' : '']" @click="router.push('/profile/sessions')">
                Sessions
              </div>
              <div class="cursor-pointer block px-4 py-2 text-sm  hover:text-black hover:bg-sky-400/90"
                :class="[route.params.page == 'settings' ? 'bg-sky-300' : '']" @click="router.push('/profile/settings')">
                Settings
              </div>
              <div class="cursor-pointer block px-4 py-2 text-sm  hover:text-black hover:bg-sky-400/90"
                :class="[route.params.page == 'changePassword' ? 'bg-sky-300' : '']"
                @click="router.push('/profile/changePassword')">
                Change password
              </div>
              <div class="cursor-pointer block px-4 py-2 text-sm  rounded-b-md  hover:text-black hover:bg-red-700/80"
                :class="[route.params.page == 'deleteAccount' ? 'bg-red-600' : 'bg-red-500 ']"
                @click="router.push('/profile/deleteAccount')">
                Delete account
              </div>
            </div>
          </div>
        </div>
      </div>

      <div v-if="!loadingData" class="w-full h-full">
        <ProfileOverviewComponent v-if="route.params.page == 'overview'" :sessions="profileData.sessions" />
        <ProfileSessionsComponent v-if="route.params.page == 'sessions'" :sessions="profileData.sessions" />
        <ProfileSettingsComponent v-if="route.params.page == 'settings'" :user="profileData.user"
          @notify="showNotification" />
        <ProfilePasswordChange v-if="route.params.page == 'changePassword'" @notify="showNotification" />
        <ProfileAccountDelete v-if="route.params.page == 'deleteAccount'" />
      </div>
      <div v-else class="w-full h-full flex items-center justify-center text-4xl">
        <div class="animate-spin">
          <svg class="w-20 h-20" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
            <path d="M12,1A11,11,0,1,0,23,12,11,11,0,0,0,12,1Zm0,19a8,8,0,1,1,8-8A8,8,0,0,1,12,20Z" opacity=".25" />
            <path
              d="M12,4a8,8,0,0,1,7.89,6.7A1.53,1.53,0,0,0,21.38,12h0a1.5,1.5,0,0,0,1.48-1.75,11,11,0,0,0-21.72,0A1.5,1.5,0,0,0,2.62,12h0a1.53,1.53,0,0,0,1.49-1.3A8,8,0,0,1,12,4Z"
              class="spinner_z9k8" />
          </svg>
        </div>
      </div>
    </div>
    <div
      class="lg:hidden flex flex-row fixed bottom-0 w-full h-[50px] justify-between bg-gray-300  items-center border-black overflow-x-auto">
      <div
        class="cursor-pointer px-8 w-full h-full flex justify-center items-center text-sm border-black hover:text-black hover:bg-sky-400/90"
        :class="[route.params.page == 'overview' ? 'bg-sky-300' : '']" @click="router.push('/profile/overview')">
        Overview
      </div>
      <div
        class="cursor-pointer px-8 h-full flex justify-center items-center w-full text-sm hover:text-black hover:bg-sky-400/90"
        :class="[route.params.page == 'sessions' ? 'bg-sky-300' : '']" @click="router.push('/profile/sessions')">
        Sessions
      </div>
      <div
        class="cursor-pointer px-8 h-full flex justify-center items-center w-full text-sm   hover:text-black hover:bg-sky-400/90"
        :class="[route.params.page == 'settings' ? 'bg-sky-300' : '']" @click="router.push('/profile/settings')">
        Settings
      </div>
      <div
        class="cursor-pointer px-8 h-full flex justify-center items-center w-full text-sm flex-grow hover:text-black hover:bg-sky-400/90"
        :class="[route.params.page == 'changePassword' ? 'bg-sky-300' : '']"
        @click="router.push('/profile/changePassword')">
        Change password
      </div>
      <div
        class="cursor-pointer px-8 h-full flex justify-center items-center w-full text-sm hover:text-black hover:bg-red-700/80"
        :class="[route.params.page == 'deleteAccount' ? 'bg-red-600' : 'bg-red-500 ']"
        @click="router.push('/profile/deleteAccount')">
        Delete account
      </div>
    </div>
  </div>
  <Notification />
</template>

<script setup>
import { ref, computed } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import { UserIcon, ArrowLeftOnRectangleIcon } from '@heroicons/vue/24/outline';
import { Disclosure } from '@headlessui/vue'
import store from '../store';
import ProfileOverviewComponent from '../components/profile/ProfileOverviewComponent.vue'
import ProfileSessionsComponent from '../components/profile/ProfileSessionsComponent.vue'
import ProfileSettingsComponent from '../components/profile/ProfileSettingsComponent.vue'
import ProfilePasswordChange from '../components/profile/ProfilePasswordChange.vue'
import ProfileAccountDelete from '../components/profile/ProfileAccountDelete.vue'
import Notification from '../components/Notification.vue';


const profileData = ref({
  user: computed(() => store.state.user.data),
  sessions: null
})

const loadingData = ref(true)

const route = useRoute()
const router = useRouter()

if (!['overview', 'sessions', 'settings', 'passwordChange', 'accountDelete'].includes(route.params.page)) {
  router.push('/profile/overview')
}

changeBgColor()

store.dispatch('getProfileData')
  .then((response) => {
    if (response === 401) {
      router.go('/login')
    }
    loadingData.value = false
    profileData.value.sessions = store.state.profile.sessions
  })

function showNotification(message) {
  store.commit('notify', {
    type: 'success',
    message: message
  })
}

function changeBgColor() {
  document.body.style.backgroundColor = 'white'
}


</script>

<style>
.center-screen {
  display: flex;
  justify-content: center;
  align-items: center;
  text-align: center;
  min-height: 100vh;
}
</style>
