<script setup>
defineProps({
    modelValue: [String, Number],
    customClass: { type: String, default: 'w-full' },
    required: { type: Boolean, default: false },
    options: { type: Array, default: [] },
    placeholder: { type: String, default: 'Todos' }
})

const emit = defineEmits(['update:modelValue']);

function onChange(event) {
    emit('update:modelValue', event.target.value);
}
</script>

<template>
    <select @change="onChange" name="" id=""
        class="border-2 border-gray-200 focus:border-green-500 focus:bg-white rounded-md px-3 py-2 outline-0 w-full">
        <option value="all">{{ placeholder }}</option>
        <option v-for="option in options" :key="option" :value="option.id">{{ option.name }}</option>
    </select>
</template>