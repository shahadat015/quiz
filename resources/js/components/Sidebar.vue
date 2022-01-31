<template>
    <aside class="z-20 hidden w-64 overflow-y-auto bg-white dark:bg-gray-800 md:block flex-shrink-0">
        <div class="py-4 text-gray-500 dark:text-gray-400">
            <router-link class="flex justify-center" :to="{ name: 'dashboard' }">
                <img class="w-32 h-10 object-cover" src="/images/logo.png" alt="">
            </router-link>
            <ul class="mt-6">
                <template v-for="menu in menus" :key="menu.title">
                    <li class="relative px-6 py-3" v-if="! menu.submenus && can(menu.role)" @click="isPagesMenuOpen = false">
                        <span v-if="isActiveMenu(menu.name)" class="absolute inset-y-0 left-0 w-1 bg-purple-600 rounded-tr-lg rounded-br-lg" aria-hidden="true"></span>
                        <router-link :to="{ name: menu.name }" class="inline-flex items-center w-full text-sm font-semibold text-gray-600 transition-colors duration-150 hover:text-gray-800 dark:hover:text-gray-200" :class="isActiveMenu(menu.name) ? 'dark:text-gray-100 text-gray-800' : 'dark:text-gray-400'">
                            <svg class="w-5 h-5" aria-hidden="true" fill="none" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24" stroke="currentColor" v-html="menu.icon"></svg>
                            <span class="ml-4">{{ menu.title }}</span>
                        </router-link>
                    </li>
                    <li class="relative px-6 py-3" v-else-if="can(menu.role)">
                        <button class="inline-flex items-center justify-between w-full text-sm font-semibold transition-colors duration-150 hover:text-gray-800 dark:hover:text-gray-200" :class="{'dark:text-gray-100 text-gray-800': isPagesMenuOpen == menu.name}" @click="togglePagesMenu(menu.name)" aria-haspopup="true">
                            <span class="inline-flex items-center">
                                <svg class="w-5 h-5" aria-hidden="true" fill="none" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24" stroke="currentColor" v-html="menu.icon"></svg>
                                <span class="ml-4">{{ menu.title }}</span>
                            </span>
                            <svg class="w-4 h-4" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20">
                                <path v-if="isPagesMenuOpen == menu.name" fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                <path v-else fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                            </svg>
                        </button>
                        <template v-if="isPagesMenuOpen == menu.name">
                            <ul
                                class="p-2 mt-2 space-y-2 overflow-hidden text-sm font-medium text-gray-500 rounded-md shadow-inner bg-gray-50 dark:text-gray-400 dark:bg-gray-900"
                                aria-label="submenu"
                            >
                                
                                <li v-for="submenu in menu.submenus" :key="submenu.title" class="px-2 py-1 transition-colors duration-150 hover:text-gray-800 dark:hover:text-gray-200">
                                    <span v-if="isActiveMenu(submenu.name)" class="absolute inset-y-0 left-0 w-1 bg-purple-600 rounded-tr-lg rounded-br-lg" aria-hidden="true"></span>
                                    <router-link :to="{ name: submenu.name }" class="w-full">{{ submenu.title }}</router-link>
                                </li>
                            </ul>
                        </template>
                    </li>
                </template>
                
                <li class="relative px-6 py-3">
                    <button @click="logout" class="inline-flex items-center w-full text-sm font-semibold text-gray-800 transition-colors duration-150 hover:text-gray-800 dark:hover:text-gray-200 dark:text-gray-400">
                        <svg class="w-5 h-5" aria-hidden="true" fill="none" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24" stroke="currentColor">
                            <path d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path>
                        </svg>
                        <span class="ml-4">Logout</span>
                    </button>
                </li>
            </ul>
        </div>
    </aside>
    <!-- Mobile sidebar -->
    <!-- Backdrop -->
    <transition
        enter-active-class="transition ease-in-out duration-150"
        enter-from-class="opacity-0"
        enter-to-class="opacity-100"
        leave-active-class="transition ease-in-out duration-150"
        leave-from-class="opacity-100"
        leave-to-class="opacity-0"
    >
        <div
            v-show="isSideMenuOpen"
            @click.self="closeSideMenu"
            class="fixed inset-0 z-10 flex items-end bg-black bg-opacity-50 sm:items-center sm:justify-center"
        ></div>
        
    </transition>
    <transition
        enter-active-class="transition ease-in-out duration-150"
        enter-from-class="opacity-0 transform -translate-x-20"
        enter-to-class="opacity-100"
        leave-active-class="transition ease-in-out duration-150"
        leave-from-class="opacity-100"
        leave-to-class="opacity-0 transform -translate-x-20"
    >

        <aside
            class="fixed inset-y-0 z-20 flex-shrink-0 w-64 mt-16 overflow-y-auto bg-white dark:bg-gray-800 md:hidden"
            v-show="isSideMenuOpen"
        >
            <div class="py-4 text-gray-500 dark:text-gray-400">
                <a class="ml-6 text-lg font-bold text-gray-800 dark:text-gray-200" href="#">
                    eCourier
                </a>
                <ul class="mt-6">
                    <template v-for="menu in menus" :key="menu.name">
                        <li class="relative px-6 py-3" v-if="! menu.submenus && can(menu.role)">
                            <span v-if="isActiveMenu(menu.name)" class="absolute inset-y-0 left-0 w-1 bg-purple-600 rounded-tr-lg rounded-br-lg" aria-hidden="true"></span>
                            <router-link :to="{ name: menu.name }" class="inline-flex items-center w-full text-sm font-semibold text-gray-800 transition-colors duration-150 hover:text-gray-800 dark:hover:text-gray-200 dark:text-gray-100">
                                <svg class="w-5 h-5" aria-hidden="true" fill="none" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24" stroke="currentColor" v-html="menu.icon"></svg>
                                <span class="ml-4">{{ menu.title }}</span>
                            </router-link>
                        </li>
                        <li class="relative px-6 py-3" v-else-if="can(menu.role)">
                            <button class="inline-flex items-center justify-between w-full text-sm font-semibold transition-colors duration-150 hover:text-gray-800 dark:hover:text-gray-200" :class="{'dark:text-gray-100 text-gray-800': isPagesMenuOpen == menu.name}" @click="togglePagesMenu(menu.name)" aria-haspopup="true">
                                <span class="inline-flex items-center">
                                    <svg class="w-5 h-5" aria-hidden="true" fill="none" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24" stroke="currentColor" v-html="menu.icon"></svg>
                                    <span class="ml-4">{{ menu.title }}</span>
                                </span>
                                <svg class="w-4 h-4" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20">
                                    <path v-if="isPagesMenuOpen == menu.name" fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                    <path v-else fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                                </svg>
                            </button>
                            <template v-if="isPagesMenuOpen == menu.name">
                                <ul
                                    class="p-2 mt-2 space-y-2 overflow-hidden text-sm font-medium text-gray-500 rounded-md shadow-inner bg-gray-50 dark:text-gray-400 dark:bg-gray-900"
                                    aria-label="submenu"
                                >
                                    <li v-for="submenu in menu.submenus" :key="submenu.name" class="px-2 py-1 transition-colors duration-150 hover:text-gray-800 dark:hover:text-gray-200">
                                        <span v-if="isActiveMenu(submenu.name)" class="absolute inset-y-0 left-0 w-1 bg-purple-600 rounded-tr-lg rounded-br-lg" aria-hidden="true"></span>
                                        <router-link :to="{ name: submenu.name }" class="w-full">{{ submenu.title }}</router-link>
                                    </li>
                                </ul>
                            </template>
                        </li>
                    </template>
                    <li class="relative px-6 py-3">
                        <button @click="logout" class="inline-flex items-center w-full text-sm font-semibold text-gray-800 transition-colors duration-150 hover:text-gray-800 dark:hover:text-gray-200 dark:text-gray-400">
                            <svg class="w-5 h-5" aria-hidden="true" fill="none" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24" stroke="currentColor">
                                <path d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path>
                            </svg>
                            <span class="ml-4">Logout</span>
                        </button>
                    </li>
                </ul>
            </div>
        </aside>
    
    </transition>
</template>
<script>
    import { mapGetters, mapActions } from "vuex";

    export default {
        data(){
            return {
                isPagesMenuOpen: false,
                menus: [
                    {
                        title: 'Dashboard',
                        name: 'dashboard',
                        submenus: false,
                        icon: '<path d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>',
                        role: ['admin', 'merchant'],
                    },
                    {
                        title: 'Quiz',
                        icon: '<svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>',
                        name: 'parcel',
                        role: 'admin',
                        submenus: [
                            {
                                title: 'Add new Quiz',
                                name: 'quizzes.create'
                            },
                            {
                                title: 'All Quizzes',
                                name: 'quizzes'
                            }
                        ]
                    },
                ]
            }
        },

        computed: {
            ...mapGetters({
                isSideMenuOpen: "theme/getSideMenu",
                user: 'auth/user',
            })
        },
        
        methods: {
            ...mapActions({
                signOut: 'auth/signOut',
                closeSideMenu: 'theme/closeSideMenu'
            }),
            async logout(){
                try {
                    await this.signOut();
                    this.$router.replace({ name: 'login' });
                } catch(error) {
                    console.log(error);
                }
            },
            isActiveMenu(menu){
                return this.$route.name == menu;
            },
            togglePagesMenu(name){
                if(this.isPagesMenuOpen == name){
                    this.isPagesMenuOpen = false;
                }else{
                    this.isPagesMenuOpen = name;
                }
            }
        }

    }
</script>