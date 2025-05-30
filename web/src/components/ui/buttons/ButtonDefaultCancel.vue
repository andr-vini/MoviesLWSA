<script setup>
    defineProps({
        type: { type: String, default: 'button' },
        customClass: { type: String, default: 'bg-gray-200 hover:bg-gray-300' }
    })

</script>
<template>
    <button :type="type" :class="['rounded-md py-1 px-3 cursor-pointer', customClass]"><slot></slot></button>
</template>