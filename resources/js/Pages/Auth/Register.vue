<template>
    <breeze-validation-errors class="mb-4" />

    <form @submit.prevent="submit">
        <p class="mt-2 mb-6 text-lg uppercase leading-8 font-extrabold tracking-tight text-gray-500 sm:text-xs">
            Pick a subscription
        </p>    

        <select-plans v-model:plan_id="form.plan_id" :plans="plans"></select-plans>

        <p class="mt-4 mb-6 text-lg uppercase leading-8 font-extrabold tracking-tight text-gray-500 sm:text-xs">
            Add a Debit/Credit Card
        </p>    

        <card-payment 
            ref="cardElement"
            :owner="this.form.name" 
            :client-secret="clientSecret"
            @set-pre-submit-error="updatePreSubmit"
            @set-payment-method-id="updatePaymentMethod"
            @set-post-submit-error="updatePostSubmit">
        </card-payment>

        <span class="mt-12 text-sm text-red-600">
            {{preSubmitError}}
        </span>

        <p class="mt-8 mb-6 text-lg uppercase leading-8 font-extrabold tracking-tight text-gray-500 sm:text-xs">
            Choose your credentials
        </p>                  
        <div>
            <breeze-label for="name" value="Name" />
            <breeze-input id="card-holder-name" type="text" class="mt-1 block w-full" v-model="form.name" required autofocus autocomplete="name" />
        </div>

        <div class="mt-4">
            <breeze-label for="email" value="Email" />
            <breeze-input id="email" type="email" class="mt-1 block w-full" v-model="form.email" required autocomplete="username" />
        </div>

        <div class="mt-4">
            <breeze-label for="password" value="Password" />
            <breeze-input id="password" type="password" class="mt-1 block w-full" v-model="form.password" required autocomplete="new-password" />
        </div>

        <div class="mt-4">
            <breeze-label for="password_confirmation" value="Confirm Password" />
            <breeze-input id="password_confirmation" type="password" class="mt-1 block w-full" v-model="form.password_confirmation" required autocomplete="new-password" />
        </div>

        <div class="flex items-center justify-end mt-4">
            <inertia-link :href="route('login')" class="underline text-sm text-gray-600 hover:text-gray-900">
                Already registered?
            </inertia-link>

            <breeze-button class="ml-4" 
                           :class="{ 'opacity-25': form.processing }" 
                           :disabled="form.processing">
                Register
            </breeze-button>
        </div>
    </form>
</template>
<script>
    import SelectPlans from '@/Components/SelectPlans'
    import CardPayment from '@/Components/CardPayment'    
    import BreezeButton from '@/Components/Button'
    import BreezeGuestLayout from '@/Layouts/Guest'
    import BreezeInput from '@/Components/Input'
    import BreezeLabel from '@/Components/Label'
    import BreezeValidationErrors from '@/Components/ValidationErrors'    
    import { watchEffect } from '@vue/runtime-core'

    export default {
        layout: BreezeGuestLayout,
        props: ['plans', 'clientSecret'],        
        components: {
            SelectPlans,
            CardPayment,
            BreezeButton,
            BreezeInput,
            BreezeLabel,
            BreezeValidationErrors,
        },
        data() {           
            return {
                form: this.$inertia.form({
                    plan_id: '',
                    paymentMethodId: '',
                    name: '',
                    email: '',
                    password: '',
                    password_confirmation: '',
                    terms: false,
                }),
                preSubmitError: '',                 
                postSubmitError: ''
            }
        },       
        methods: {     
            updatePreSubmit(preSubmitError){
                watchEffect(() => this.preSubmitError = preSubmitError)                
            },  
            updatePaymentMethod(paymentMethodId){
                watchEffect(() => this.form.paymentMethodId = paymentMethodId)
            },  
            updatePostSubmit(postSubmitError){
                watchEffect(() => this.postSubmitError = postSubmitError)
            },                                                  
            async submit() {
                // 
                await this.$refs.cardElement.confirmPaymentAndIntent();                                     
                //    
                this.form.post(this.route('register'), {
                    onFinish: () => this.form.reset('password', 'password_confirmation'),
                }) 
            }                                
        }
    }
</script>