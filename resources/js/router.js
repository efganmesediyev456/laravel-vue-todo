import Vue from 'vue'
import Router from 'vue-router'
import tags from './admin/pages/tag.vue'
import category from './admin/pages/category.vue'


Vue.use(Router)


const routes=[
    {
        path:'/tags',
        component:tags
    },

    {
        path:'/category',
        component:category
    },
    


   
    
]

export default new Router({
    mode:'history',
    routes
})