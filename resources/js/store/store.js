import { createStore } from 'vuex'
import createPersistedState from "vuex-persistedstate";
import Cookies from 'js-cookie';

export const store = createStore({
    plugins: [createPersistedState({
        storage: {
            getItem: key => Cookies.get(key),
            setItem: (key, value) => Cookies.set(key, value, { expires: 3, secure: true }),
            removeItem: key => Cookies.remove(key)
        }
    })],
    state: {
        paymentMethodId: '',
        preSubmitError: [],
        postSubmitError: [],
    },
    mutations: {
        setPaymentMethodId(state, payload) {
            state.paymentMethodId = payload
        },
        setPreSubmitError(state, payload) {
            state.preSubmitError.push(payload)
        },
        setPostSubmitError(state, payload) {
            state.postSubmitError.push(payload)
        },
    },
    // getters: {
    //     preSubmitErrors: (state) => {
    //         // return 
    //     },
    //     postSubmitErrors(state) {
    //         // return 
    //     }
    // },
    actions: {
        addPreSubmitError(context, preSubmitError) {
            context.commit('setPreSubmitError', preSubmitError);
        },
        addPaymentMethodId(context, paymentMethodId) {
            context.commit('setPaymentMethodId', paymentMethodId);
        },
        addPostSubmitError(context, postSubmitError) {
            context.commit('setPostSubmitError', postSubmitError);
        }
    }
});