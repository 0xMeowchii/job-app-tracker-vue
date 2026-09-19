<script setup lang="ts">
import type { HTMLAttributes } from 'vue';

defineOptions({
    inheritAttrs: false,
});

type Props = {
    className?: HTMLAttributes['class'];
};

defineProps<Props>();
</script>

<template>
   <img width="24" height="24" src="https://img.icons8.com/softteal-gradient/24/cat-footprint.png" alt="cat-footprint"/>
</template>
