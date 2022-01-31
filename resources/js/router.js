import { createRouter, createWebHistory } from 'vue-router';
import store from './store';
import Dashboard from './pages/Dashboard.vue';
import Login from './pages/Auth/Login.vue';
import Register from './pages/Auth/Register.vue';

import Quizzes from './pages/Quizzes/Index.vue';
import CreateQuiz from './pages/Quizzes/Create.vue';
import EditQuiz from './pages/Quizzes/Edit.vue';

import Unauthorize from './pages/Errors/403.vue';
import NotFound from './pages/Errors/404.vue';

const routes = [
    { path: '/', name: 'dashboard', component: Dashboard, meta: { roles: ['admin'] } },
    { path: '/login', name: 'login', component: Login },
    { path: '/register', name: 'register', component: Register },
    
    { path: '/quizzes', name: 'quizzes', component: Quizzes, meta: { roles: ['admin'] } },
    { path: '/quizzes/create', name: 'quizzes.create', component: CreateQuiz, meta: { roles: ['admin'] } },
    { path: '/quizzes/:id/edit', name: 'quizzes.edit', component: EditQuiz, meta: { roles: ['admin'] } },
    
    { path: '/unauthorize', name: 'unauthorize', component: Unauthorize },
    { path: '/:catchAll(.*)', name: 'not-found', component: NotFound },
];

const router = createRouter({
    history: createWebHistory(),
    routes,
});

router.beforeEach((to, from, next) => {
    const user = store.getters['auth/user'];
    const isAuthenticated = store.getters['auth/authenticated'];
    if (to.name == 'register' && isAuthenticated) next({ name: 'dashboard' })
    else if (to.name == 'login' && isAuthenticated) next({ name: 'dashboard' })
    else if (to.name !== 'login' && to.name !== 'register' && !isAuthenticated) next({ name: 'login' })
    else if (to.name !== 'unauthorize' && isAuthenticated && to.meta.roles && ! to.meta.roles.includes(user.role)) next({ name: 'unauthorize' })
    else next()
});

export default router;