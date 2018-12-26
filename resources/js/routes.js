import VueRouter from 'vue-router'
import Home from './views/Home'
import Rooms from './views/Rooms'

const routes = [
    {
        path: '/',
        component: Home
    },
    {
        path: '/rooms',
        component: Rooms
    },
]

export default new VueRouter({
    routes
})
