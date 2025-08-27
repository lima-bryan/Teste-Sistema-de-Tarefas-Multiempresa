<template>
    <b-button :variant="variant" 
    :size="size" :block="block" 
    :disabled="disabled" @click="$emit('click')">
        
        <slot></slot>
    </b-button>
</template>

<script>
export default {
    name: "BaseButton",
    props: {
        variant: {
            type: String,
            default: "primary", // primary, success, danger, warning...
        },
        size: {
            type: String,
            default: "md", // sm, md, lg
        },
        block: {
            type: Boolean,
            default: false, // botão ocupar 100% da largura
        },
        disabled: {
            type: Boolean,
            default: false,
        },
    },
};
</script>