
<template>
  <div class="max-w-screen-2xl w-[70%] m-auto mt-24 h-[50rem] text-center bg-white">
    <div class="flex py-12 h-full">
      <div class="border-r-2 h-full basis-[28%] pl-12 pr-6">
        <div class="grid grid-cols-1 items-center mt-32">
          <div v-if="profileData">
            <div
              class="bg-gray-400/50 rounded-full flex flex-col m-auto items-center justify-center relative w-[150px] h-[150px]">
              <div v-if="profileData.user.image">
                <img :src="profileData.user.image" alt="user_profile_image" class="w-[150px] h-[150px] rounded-full ">
              </div>
              <UserIcon v-else class="w-[50%] h-[50%] text-gray-500 absolute" />
            </div>
            <div class="text-2xl font-bold mt-7">{{ profileData.user.nickname }}</div>
            <div class="text-md font-semibold mt-1">{{ profileData.user.email }}</div>
          </div>
          <div class="m-auto">
            <div class="mt-8 w-48  rounded-md bg-white shadow-xl ring-1 ring-black ring-opacity-20 focus:outline-none">
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
      <div v-if="profileData.sessions" class="w-full h-full">
        <ProfileOverviewComponent v-if="route.params.page == 'overview'" :sessions="profileData.sessions" />
        <ProfileSessionsComponent v-if="route.params.page == 'sessions'" :sessions="profileData.sessions" />
        <ProfileSettingsComponent v-if="route.params.page == 'settings'" :user="profileData.user" />
        <ProfilePasswordChange v-if="route.params.page == 'changePassword'" />
        <ProfileAccountDelete v-if="route.params.page == 'deleteAccount'" />
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import { UserIcon } from '@heroicons/vue/24/outline';
import store from '../store';
import ProfileOverviewComponent from '../components/profile/ProfileOverviewComponent.vue'
import ProfileSessionsComponent from '../components/profile/ProfileSessionsComponent.vue'
import ProfileSettingsComponent from '../components/profile/ProfileSettingsComponent.vue'
import ProfilePasswordChange from '../components/profile/ProfilePasswordChange.vue'
import ProfileAccountDelete from '../components/profile/ProfileAccountDelete.vue'


const profileData = ref({
  user: computed(() => store.state.user.data),
  sessions: null
})

const route = useRoute()
const router = useRouter()

if (!['overview', 'sessions', 'settings', 'passwordChange', 'accountDelete'].includes(route.params.page)) {
  router.push('/profile/overview')
}

store.dispatch('getProfileData')
  .then(() => {
    profileData.value.sessions = store.state.profile.sessions
  })

changeBgColor()

// const Page = ref({
//   currentPage: 'overview',
//   title: 'Overview',

//   showOverview: function () {
//     this.currentPage = 'overview'
//     this.title = 'Overview'
//     router.push('/profile')
//   },
//   showSessions: function () {

//     this.title = 'Your solving sessions'
//     router.push('/profile/sessions')
//   },
//   showSettings: function () {
//     this.currentPage = 'settings'
//     this.title = 'Account Settings'
//     router.push('/profile/settings')
//   },
//   showPasswordChange: function () {
//     this.currentPage = 'passwordChange'
//     this.title = 'Change Your Password'
//     router.push('/profile/sessions')
//   },
//   showDeleteAccount: function () {
//     this.currentPage = 'deleteAccount'
//     this.title = 'Delete your account'
//   }
// })

function changeBgColor() {
  document.body.style.backgroundColor = 'rgb(125 211 252 / 0.3)'
}


</script>


