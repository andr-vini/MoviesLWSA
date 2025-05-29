<script setup>
    import { ref } from 'vue'
    import InputDefault from '@components/inputs/InputDefault.vue'

    defineProps({
        modelValue: [String, Number]
    })

    const emit = defineEmits(['update:modelValue'])

    let showPass = ref(false);

    function togglePass(){
        showPass.value = !showPass.value
    }
</script>

<template>
    <div class="relative">
        <InputDefault :modelValue="modelValue" @update:modelValue="emit('update:modelValue', $event)" :type="showPass ? 'text' : 'password'" placeholder="****"/>
        <span class="absolute right-0 hover:bg-gray-200 px-3.5 py-3.5 rounded-r-md cursor-pointer" @click="togglePass">
            <svg v-show="showPass" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-4 w-4"><path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"></path><circle cx="12" cy="12" r="3"></circle></svg>
            <svg v-show="!showPass" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-4 w-4"><path d="M9.88 9.88a3 3 0 1 0 4.24 4.24"></path><path d="M10.73 5.08A10.43 10.43 0 0 1 12 5c7 0 10 7 10 7a13.16 13.16 0 0 1-1.67 2.68"></path><path d="M6.61 6.61A13.526 13.526 0 0 0 2 12s3 7 10 7a9.74 9.74 0 0 0 5.39-1.61"></path><line x1="2" x2="22" y1="2" y2="22"></line></svg>
        </span>
    </div>
</template>