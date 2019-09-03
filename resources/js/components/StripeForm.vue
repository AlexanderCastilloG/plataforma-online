<template>
    <stripe-checkout 
        button="Suscribirme"
        buttonClass="btn btn-course"
        :stripe-key="stripe_key"
        :product="product"
    >
    </stripe-checkout>
</template>

<script>
import { StripeCheckout } from 'vue-stripe'
export default {
    components: {
        StripeCheckout
    },
    props: {
        stripe_key: '',
        name: '',
        amount: '' ,
        description: ''
    },
    //variables de forma dinámicas en nuestro componente
    computed: {
        product() {
            return {
                name: this.name,
                amount:  parseFloat(this.amount), // amount es el precio
                description: this.description
            }
        }
    },
    
}
</script>