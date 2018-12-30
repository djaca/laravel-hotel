import VueRouter from 'vue-router'
import Home from './views/Home'
import Rooms from './views/Rooms'
import Guests from './views/Guests'
import Guest from './views/Guest'
import Reservations from './views/Reservations'
import Reservation from './views/Reservation'

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
    {
        path: '/reservations',
        component: Reservations
    },
    {
        path: '/reservations/:id',
        component: Reservation
    },
]

export default new VueRouter({
    routes
})
