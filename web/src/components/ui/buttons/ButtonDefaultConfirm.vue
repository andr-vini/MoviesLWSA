<script setup>
defineProps({
    type: { type: String, default: 'button' },
    customClass: { type: String, default: '' },
    disabled: { type: Boolean, default: false }
})

</script>
<template>
    <button :type="type" :disabled="disabled"
        :class="['rounded-md py-1 px-3 bg-green-500 hover:bg-green-600 cursor-pointer text-white disabled:bg-green-300', customClass]">
        <slot></slot>
    </button>
</template>