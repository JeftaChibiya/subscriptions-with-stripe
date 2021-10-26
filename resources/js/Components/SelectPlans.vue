<template>
    <select :value="plan_id" @input="$emit('update:plan_id', $event.target.value)" class="mb-4">
        <option disabled value="">
            Select a plan
        </option>
        <option v-for="plan in plans" v-bind:value="plan.id" :key="plan.id">
            {{ plan.name }}
        </option>
    </select> 
</template>
<script>
    export default {
        props: ['plan_id','plans'],
        emits: ['update:plan_id'],
    }
</script>
<style scoped>
    select {
        appearance: none;
        background-color: transparent;
        border: 1px solid rgb(209, 206, 206);
        padding-top: 0.5rem 0.75rem 0.5rem 0.75rem;
        border-radius: 0.375rem;
        width: 100%;
        font-family: inherit;
        font-size: inherit;
        cursor: inherit;
        line-height: inherit;
    }
</style>