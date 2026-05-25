<script setup>
import { InputGroup, InputGroupAddon, InputGroupInput } from '@/components/ui/input-group';
import { SearchIcon } from 'lucide-vue-next';
import { ref, watch } from 'vue';
import { router } from '@inertiajs/vue3';
import { route } from 'ziggy-js';

const props = defineProps({
    initialSearch: { type: String, default: '' },
    routeName: { type: String, required: true }
});

const search = ref(props.initialSearch);

// Debounce so it doesn't fire on every keystroke
let timeout = null;
watch(search, (value) => {
    clearTimeout(timeout);
    timeout = setTimeout(() => {
        router.get(route(props.routeName), { search: value }, {
            preserveState: true,
            preserveScroll: true,
            replace: true, // avoids polluting browser history
        });
    }, 400);
});
</script>

<template>
    <InputGroup>
        <InputGroupInput v-model="search" placeholder="Search..." />
        <InputGroupAddon>
            <SearchIcon />
        </InputGroupAddon>
    </InputGroup>
</template>