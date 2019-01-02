import VueRouter from 'vue-router'
import Home from './views/Home'
import Rooms from './views/Rooms'
import Guests from './views/Guests'
import Guest from './views/Guest'
import Reservations from './views/Reservations'
import Reservation from './views/Reservation'
import CreateReservation from './views/CreateReservation'

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
        path: '/reservations/create',
        component: CreateReservation
    },
    {
        path: '/reservations/:id',
        component: Reservation
    },
    {
        path: '/reservations/:id/edit',
        component: CreateReservation,
        name: 'EditReservation',
        props: true
    },
]

export default new VueRouter({
    routes
})
