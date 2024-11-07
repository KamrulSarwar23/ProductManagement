import { createRouter, createWebHistory} from 'vue-router'

import productIndex from '../components/products/Index.vue'
import productCreate from '../components/products/Create.vue'
import NotFound from '../components/NotFound.vue'
// import ProductEdit from '../components/products/Edit.vue'


const routes = [
    {
        path:'/',
        name: 'products.index',
        component:productIndex
    },

    {
        path:'/products/create',
        name: 'products.create',
        component:productCreate
    },

    
    {
        path:'/products/:id/edit',
        name: 'products.edit',
        component:productCreate
    },


    {
        path:'/:pathMatch(.*)*',
        name:'Notfound',
        component:NotFound
    }
]

const router = createRouter({
    history:createWebHistory(),
    routes
});

export default router