import {createRouter, createWebHistory} from "vue-router";
import invoiceIndex from '../components/invoices/index.vue'
import notFound from '../components/NoutFound.vue'
import invoiceNew from '../components/invoices/new.vue'
import showInvoice from '../components/invoices/show.vue'
import editInvoice from '../components/invoices/edit.vue'

const routes = [
    {
        path: '/',
        component: invoiceIndex
    },
    {
        path: '/invoice/new',
        component: invoiceNew
    },
    {
      path: '/invoice/show/:id',
      component: showInvoice,
      props: true
    },
    {
      path: '/invoice/edit/:id',
      component: editInvoice,
      props: true
    },
    {
        path: '/:pathMatch(.*)*',
        component: notFound
    }
]
const router = createRouter({
    history: createWebHistory(),
    routes
})

export default router
