// resources/js/router.js

import { createRouter, createWebHistory } from 'vue-router';
import ArticleList from '@/Pages/ArticleList.vue';
import SingleArticle from '@/Pages/SingleArticle.vue';

const routes = [
    {
        path: '/',
        name: 'home',
        component: ArticleList,
    },
    {
        path: '/article/:id',
        name: 'single-article',
        component: SingleArticle,
        props: true,  // Passa l'ID dell'articolo come proprietà
    },
];

const router = createRouter({
    history: createWebHistory(),
    routes,
});

export default router;
