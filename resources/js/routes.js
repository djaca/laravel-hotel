import VueRouter from 'vue-router'
import Home from './views/Home'
import Rooms from './views/Rooms'
import Guests from './views/Guests'

const routes = [
    {
        path: '/',
        component: Home
    },
    {
        path: '/rooms',
        component: Rooms
    },
    {
        path: '/guests',
        component: Guests
    },
]

export default new VueRouter({
    routes
})
