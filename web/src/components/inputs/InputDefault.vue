<script setup>
    defineProps({
        modelValue: [String, Number],
        type: { type: String, default: 'text' },
        customClass: { type: String, default: 'w-full' },
        placeholder: { type: String, default: '' },
        required: { type: Boolean, default: false }
    })

    defineEmits(['update:modelValue'])
</script>

<template>
    <input 
        :type="type"
        :class="['border-gray-200 focus:border-green-500 border-2 rounded-md px-3 py-2 outline-0 focus:bg-white', customClass]"
        :value="modelValue"
        :placeholder="placeholder"
        :required="required"
        @input="$emit('update:modelValue', $event.target.value)"
    />
</template>