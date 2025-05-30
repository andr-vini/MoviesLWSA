<script setup>
    defineProps({
        type: { type: String, default: 'button' },
        customClass: { type: String, default: '' }
    })

</script>
<template>
    <button :type="type" :class="['rounded-md py-1 px-3 bg-green-500 hover:bg-green-600 cursor-pointer text-white', customClass]"><slot></slot></button>
</template>