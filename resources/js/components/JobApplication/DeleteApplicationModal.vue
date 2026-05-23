<script setup>
import {
    AlertDialog,
    AlertDialogCancel,
    AlertDialogContent,
    AlertDialogDescription,
    AlertDialogFooter,
    AlertDialogHeader,
    AlertDialogTitle,
    AlertDialogTrigger,
} from '@/components/ui/alert-dialog'
import { Button } from '../ui/button'

import { router } from '@inertiajs/vue3'
import { route } from 'ziggy-js'


const props = defineProps({
    application: {
        type: Object,
        required: true,
    }
})

function deleteApplication() {
    router.delete(route('JobApplication.destroy', props.application.id))
}
</script>

<template>
    <AlertDialog>
        <AlertDialogTrigger as-child>
            <slot />
        </AlertDialogTrigger>
        <AlertDialogContent>
            <AlertDialogHeader>
                <AlertDialogTitle>Are you absolutely sure?</AlertDialogTitle>
                <AlertDialogDescription>
                    This will permanently delete the selected application.
                    This action cannot be undone.
                </AlertDialogDescription>
            </AlertDialogHeader>
            <AlertDialogFooter>
                <AlertDialogCancel>Cancel</AlertDialogCancel>
                <Button variant="destructive" @click="deleteApplication">Continue</Button>
            </AlertDialogFooter>
        </AlertDialogContent>
    </AlertDialog>
</template>