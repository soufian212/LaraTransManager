<script setup>
import Logo from '@/assets/Logo.vue';
import { Link } from '@inertiajs/vue3'
import { Toast, useToast } from 'primevue';
import { watchEffect } from 'vue';
const props = defineProps({
    toast: Object,

});
const toast = useToast();



watchEffect(() => {
    if (props.toast) {
        toast.add({
            severity: props.toast.severity,
            summary: props.toast.summary,
            detail: props.toast.detail,
            life: props.toast.lifetime || 5000,
        });
    }
});

</script>

<template>
        <Toast />

    <div class="p-4 bg-white shadow-lg ">
    <div class="container mx-auto max-w-7xl flex items-center ">


        <Logo class="w-14 mr-8"  />

        <div class="flex items-center gap-4">
            <Link class="p-2 rounded-lg  shadow" :href="route('ltm.translations')">Translations</Link>
        </div>

    </div>
</div>
    <main class="container mx-auto max-w-7xl p-4">
        <slot />
    </main>
</template>