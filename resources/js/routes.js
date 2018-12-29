import VueRouter from 'vue-router'
import Home from './views/Home'
import Rooms from './views/Rooms'
import Guests from './views/Guests'
import Guest from './views/Guest'

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
    {
        path: '/guests/:id',
        component: Guest
    },
]

export default new VueRouter({
    routes
})
