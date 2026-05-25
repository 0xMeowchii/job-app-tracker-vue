<!-- resources/js/components/FlashMessage.vue -->
<script setup>
import { Alert, AlertTitle } from '@/components/ui/alert';
import { CheckCircle2Icon, AlertCircleIcon } from 'lucide-vue-next';
import { usePage } from '@inertiajs/vue3';
import { computed, ref, watch } from 'vue';

const page = usePage();

const showSuccess = ref(false);
const showError = ref(false);

// Watch the entire props object deeply so any Inertia visit triggers it
watch(
    () => page.props.flash,
    (newFlash) => {
        if (newFlash?.success) {
            // Reset first so the transition re-fires even for identical messages
            showSuccess.value = false;
            setTimeout(() => {
                showSuccess.value = true;
                setTimeout(() => (showSuccess.value = false), 3000);
            }, 50);
        }
        if (newFlash?.error) {
            showError.value = false;
            setTimeout(() => {
                showError.value = true;
                setTimeout(() => (showError.value = false), 3000);
            }, 50);
        }
    },
    { deep: true }
);

const flash = computed(() => page.props.flash);
</script>

<template>
    <Transition name="fade">
        <div class="m-5" v-if="showSuccess">
            <div >
                <Alert class="bg-green-100 border border-green-400 text-green-700 rounded mb-4">
                    <CheckCircle2Icon />
                    <AlertTitle> {{ flash.success }}</AlertTitle>
                </Alert>
            </div>
        </div>

    </Transition>
    <Transition name="fade">
        <div class="m-5" v-if="showError">
            <div >
                <Alert class="bg-red-100 border border-red-400 text-red-700 rounded mb-4">
                    <AlertCircleIcon />
                    <AlertTitle>{{ flash.error }}</AlertTitle>
                </Alert>
            </div>
        </div>

    </Transition>
</template>

<style scoped>
.fade-enter-active,
.fade-leave-active {
    transition: opacity 0.5s ease;
}

.fade-enter-from,
.fade-leave-to {
    opacity: 0;
}
</style>