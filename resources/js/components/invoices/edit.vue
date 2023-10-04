<script setup>

import {onMounted, ref} from "vue";
import router from "../../router";

let allcustomers = ref([])
let customer_id = ref([])
let listCart = ref([])
const showModal = ref(false)
const hideModal = ref(true)
let listproducts = ref([])

let invoice = ref({
    id: ''
})

const props = defineProps({
    id: {
        type: String,
        default: ''
    }
})

onMounted(async () => {
    getInvoice()
    getAllCustomers()
    getproducts()
})

const getInvoice = async () => {
    let response = await axios(`/api/invoice/${props.id}`)
    // console.log('invoice', response.data.invoice)
    invoice.value = response.data.invoice
}

const getAllCustomers = async () => {
    let response = await axios.get('/api/customers')
    // console.log('customers', response)
    allcustomers.value = response.data.customers
}

const deleteInvoiceItem = async (id, i) => {
    invoice.value.invoice_items.splice(i, 1)
    if (id != undefined) {
        await axios.delete('/api/invoice/invoice_item/' + id);
    }
}

const addCart = (item) => {
    const itemcart = {
        product_id: item.id,
        item_code: item.item_code,
        description: item.description,
        unit_price: item.unit_price,
        quantity: 1,
    }
    //listCart.value.push(itemcart)
    invoice.value.invoice_items.push(itemcart)
    closeModal()
}

const getproducts = async () => {
    let response = await axios.get('/api/products')
    listproducts.value = response.data.products
}

const openModal = () => {
    showModal.value = !showModal.value

}
const closeModal = () => {
    showModal.value = !showModal.value
}

const SubTotal = () => {
    let total = 0
    if (invoice.value.invoice_items) {
        invoice.value.invoice_items.map((data) => {
            total = total + (data.quantity * data.unit_price)
        })
    }
    return total
}

const Total = () => {
    return SubTotal() - invoice.value.discount
}

const onEdit = async (id) => {
    console.log(id);
    if(invoice.value.invoice_items.length > 0 ){
        let subTotal = 0
        subTotal = SubTotal()

        let total = 0
        total = Total()

        console.log(JSON.stringify(invoice.value));

        const formData = new FormData();
        formData.append('invoice_items', JSON.stringify(invoice.value.invoice_items))
        formData.append('customer_id', invoice.value.customer_id)
        formData.append('date', invoice.value.date)
        formData.append('due_date', invoice.value.due_date)
        formData.append('number', invoice.value.number)
        formData.append('reference', invoice.value.reference)
        formData.append('discount', invoice.value.discount)
        formData.append('sub_total', subTotal)
        formData.append('total', total)
        formData.append('terms_and_conditions', invoice.value.terms_and_conditions)

        await axios.post(`/api/invoice/${invoice.value.id}`, formData)
        invoice.value.invoice_items = []
        router.push('/')
    }
}




</script>
<template>
    <div class="container">
        <div class="invoices">

            <div class="card__header">
                <div>
                    <h2 class="invoice__title">Edit Invoice</h2>
                </div>
                <div>

                </div>
            </div>

            <div class="card__content">
                <div class="card__content--header">
                    <div>
                        <p class="my-1">Customer</p>
                        <select name="" id="" class="input" v-model="invoice.customer_id">
                            <option value="" disabled>Select Customer</option>
                            <option :value="customer.id" v-for="customer in allcustomers" :key="customer.id">
                                {{ customer.firstname }}
                            </option>
                        </select>
                    </div>
                    <div>
                        <p class="my-1">Date</p>
                        <input id="date" placeholder="dd-mm-yyyy" type="date" class="input" v-model="invoice.date">
                        <!---->
                        <p class="my-1">Due Date</p>
                        <input id="due_date" type="date" class="input" v-model="invoice.due_date">
                    </div>
                    <div>
                        <p class="my-1">Numero</p>
                        <input type="text" class="input" v-model="invoice.number">
                        <p class="my-1">Reference(Optional)</p>
                        <input type="text" class="input" v-model="invoice.reference">
                    </div>
                </div>
                <br><br>
                <div class="table">

                    <div class="table--heading2">
                        <p>Item Description</p>
                        <p>Unit Price</p>
                        <p>Qty</p>
                        <p>Total</p>
                        <p></p>
                    </div>

                    <!-- item 1 -->
                    <div class="table--items2" v-for="(itemcart, i) in invoice.invoice_items" :key="itemcart.id">
                        <p v-if="itemcart.product">
                            #{{ itemcart.product.item_code }} {{ itemcart.product.description }}
                        </p>
                        <p v-else>
                            #{{ itemcart.item_code }} {{ itemcart.description }}
                        </p>
                        <p>
                            <input type="text" class="input" v-model="itemcart.unit_price">
                        </p>
                        <p>
                            <input type="text" class="input" v-model="itemcart.quantity">
                        </p>
                        <p>
                            $ {{ itemcart.quantity * itemcart.unit_price }}
                        </p>
                        <p style="color: red; font-size: 24px;cursor: pointer;"
                           @click="deleteInvoiceItem(itemcart.id, i)">
                            &times;
                        </p>
                    </div>
                    <div style="padding: 10px 30px !important;">
                        <button class="btn btn-sm btn__open--modal" @click="openModal()">Add New Line</button>
                    </div>
                </div>

                <div class="table__footer">
                    <div class="document-footer">
                        <p>Terms and Conditions</p>
                        <textarea cols="50" rows="7" class="textarea" v-model="invoice.terms_and_conditions"></textarea>
                    </div>
                    <div>
                        <div class="table__footer--subtotal">
                            <p>Sub Total</p>
                            <span>$ {{ SubTotal() }}</span>
                        </div>
                        <div class="table__footer--discount">
                            <p>Discount</p>
                            <input type="text" class="input" v-model="invoice.discount">
                        </div>
                        <div class="table__footer--total">
                            <p>Grand Total</p>
                            <span>$ {{  Total() }}</span>
                        </div>
                    </div>
                </div>


            </div>
            <div class="card__header" style="margin-top: 40px;">
                <div>

                </div>
                <div>
                    <a class="btn btn-secondary"  @click="onEdit(invoice.id)">
                        Save
                    </a>
                </div>
            </div>

        </div>

        <!--==================== add modal items ====================-->
        <div class="modal main__modal " :class="{show: showModal}">
            <div class="modal__content">
                <span class="modal__close btn__close--modal" @click="closeModal()">×</span>
                <h3 class="modal__title">Add Item</h3>
                <hr>
                <br>
                <div class="modal__items">
                    <ul style="list-style: none">
                        <li v-for="(item, i) in listproducts" :key="item.id"
                            style="display: grid;grid-template-columns:30px 350px 15px; align-items: center;padding-bottom: 5px;">
                            <p>{{ i + 1 }}</p>
                            <a href="#">{{ item.item_code }} {{ item.description }}</a>
                            <button @click="addCart(item)"
                                    style="border: 1px solid #e0e0e0; width: 35px; height: 35px; cursor: pointer">
                                +
                            </button>
                        </li>
                    </ul>
                </div>
                <br>
                <hr>
                <div class="model__footer">
                    <button class="btn btn-light mr-2 btn__close--modal" @click="closeModal()">
                        Cancel
                    </button>
                    <button class="btn btn-light btn__close--modal ">Save</button>
                </div>
            </div>
        </div>
    </div>
</template>
