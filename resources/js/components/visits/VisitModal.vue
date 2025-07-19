<script setup lang="ts">
const emit = defineEmits(['closeModal']);
</script>

<template>
    <section class="modal">
        <div
            class="modal-overlay"
            @click="emit('closeModal')"
        />

        <div class="modal__window modal-window">
            <div class="modal-window__wrapper">
                <div class="modal-window__container">
                    <div class="modal-window-content">
                        <div class="modal-window__block">
                            <div class="flex justify-center items-center">
                                <h1>Создание визита</h1>
                            </div>
                        </div>
                    </div>
                    <button
                        type="button"
                        class="cursor-pointer ring-offset-background focus:ring-ring data-[state=open]:bg-accent data-[state=open]:text-muted-foreground absolute top-4 right-4 rounded-xs opacity-70 transition-opacity hover:opacity-100 focus:ring-2 focus:ring-offset-2 focus:outline-hidden disabled:pointer-events-none [&amp;_svg]:pointer-events-none [&amp;_svg]:shrink-0 [&amp;_svg:not([class*='size-'])]:size-4"
                        @click="emit('closeModal')"
                    >
                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            width="24"
                            height="24"
                            viewBox="0 0 24 24"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="2"
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            class="lucide lucide-xicon"
                        >
                            <path d="M18 6 6 18" />
                            <path d="m6 6 12 12" />
                        </svg>
                        <span class="sr-only">Close</span>
                    </button>
                </div>
            </div>
        </div>
    </section>
</template>
