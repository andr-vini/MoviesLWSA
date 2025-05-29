<script setup>
    defineProps({
        customClass: {
            type: String,
            default: ''
        }
    })
</script>

<template>
    <!-- WF = Width Fixed -->
    <div :class="['px-4 py-5 rounded-lg shadow-md', customClass]">
        <slot></slot>
    </div>
</template>