<script setup lang="ts">
withDefaults(
    defineProps<{
        forId: string,
        required: boolean,
    }>(),
    {
        required: false,
    },
);
</script>

<template>
    <label :for="forId" class="text-sm font-medium">
        <slot />
        <span v-if="required" class="text-red-400 ms-1">*</span>
    </label>
</template>
