<script setup>
    defineProps({
        type: { type: String, default: 'button' },
        customClass: { type: String, default: 'bg-green-500 text-white' }
    })

</script>
<template>
    <button :type="type" :class="['rounded-md py-2 w-full', customClass]"><slot></slot></button>
</template>