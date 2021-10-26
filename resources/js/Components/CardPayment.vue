<template>
    <div ref="cardElement" class="card-input"></div>
</template>

<script>
    let stripe = Stripe('pk_test_51Ia114B1T3jSXiPmvGnAzQyUAt2GFFYXnIOPPmVFYg80d6oxCeZIwvboAgufhgroEzSITW04XOLJERo3bKOAdzQB00YzrxttCO');
    let elements = stripe.elements();
    let cardElement = undefined;
    let cardStyle = {
        base: {
            color: 'black',
            fontSize: '17px',
            fontWeight: '400',
            fontFamily: '"Helvetica Neue", Helvetica, sans-serif',
            fontSmoothing: 'antialiased',              
            '::placeholder': { color: '#999999'}
        },
        invalid: {
            color: '#E53A40',
            iconColor: '#fa755a'
        }
    }          
    // 
export default {
    props: ['clientSecret','owner'], 
    emits: ['setPreSubmitError', 'setPaymentMethodId', 'setPostSubmitError'],
    mounted(){          
        this.createCardElement()              
    },
    methods: {
        // 
        createCardElement(){
            let self = this;     
            cardElement = elements.create('card', {style: cardStyle, hidePostalCode: true});  
            cardElement.mount(this.$refs.cardElement);                    
            // realtime validate
            cardElement.addEventListener('change', function(event) {                
                if (event.error) {
                    self.$emit('setPreSubmitError', event.error.message)                                                     
                } else {
                    self.$emit('setPreSubmitError', '')                                                   
                }
            }); 
        },
        // 
        async confirmPaymentAndIntent(){
            let self = this;              
            await stripe.confirmCardSetup(
                this.clientSecret.client_secret, {
                payment_method: {
                    card: cardElement,
                    billing_details: { name: this.owner }                    
                }
            })
            .then(function(result) {
                // Handle result.error or result.setupIntent
                if (result.error) {
                    // Handle result.error      
                    self.$emit('setPostSubmitError', error)                                                                              
                } 
                else {
                    // Handle result.paymentIntent
                    self.$emit('setPaymentMethodId', result.setupIntent.payment_method)                    
                }                  
            });                                                              
        },              
    }
    //  
}
</script>
<style scoped>
    *,
    *::before,
    *::after {
    box-sizing: border-box;
    }
    .card-input{
        -webkit-appearance: none;
        -moz-appearance: none;
        padding-top: 0.5rem;
        padding-right: 0.75rem;
        padding-bottom: 0.5rem;
        padding-left: 0.75rem;
        border-radius: 0.375rem;
        border: 1px solid rgb(209, 206, 206);        
    }
</style>
