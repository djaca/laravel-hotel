require('./bootstrap');
import router from './routes'
import Navbar from './components/Navbar'
import Sidebar from './components/Sidebar'

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

const app = new Vue({
    el: '#app',
    router,
    components: {
        Navbar,
        Sidebar,
    }
});
