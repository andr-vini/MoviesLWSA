<script setup>
    defineProps({
        textButton: { type: String, default: 'Enviar'},
        type: { type: String, default: 'button' }
    })

</script>
<template>
    <button :type="type" class="bg-green-500 rounded-md py-2 w-full text-white"> {{ textButton }}</button>
</template>