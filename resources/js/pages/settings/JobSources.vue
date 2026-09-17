<script setup lang="ts">
import { Head, router, useForm } from '@inertiajs/vue3';
import { Pencil, Plus, Trash2 } from 'lucide-vue-next';
import { ref } from 'vue';
import Heading from '@/components/Heading.vue';
import InputError from '@/components/InputError.vue';
import {
    AlertDialog,
    AlertDialogCancel,
    AlertDialogContent,
    AlertDialogDescription,
    AlertDialogFooter,
    AlertDialogHeader,
    AlertDialogTitle,
} from '@/components/ui/alert-dialog';
import { Button } from '@/components/ui/button';
import {
    Dialog,
    DialogClose,
    DialogContent,
    DialogDescription,
    DialogFooter,
    DialogHeader,
    DialogTitle,
} from '@/components/ui/dialog';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { destroy, index, store, update } from '@/routes/job-sources';

type JobSource = {
    id: number;
    name: string;
    job_applications_count: number;
};

const props = defineProps<{
    sources: JobSource[];
}>();

defineOptions({
    layout: {
        breadcrumbs: [
            {
                title: 'Job sources',
                href: index(),
            },
        ],
    },
});

const createForm = useForm({
    name: '',
});

const editingSource = ref<JobSource | null>(null);
const editForm = useForm({
    name: '',
});

const sourceToDelete = ref<JobSource | null>(null);

function submitCreate() {
    createForm.submit(store(), {
        preserveScroll: true,
        onSuccess: () => createForm.reset(),
    });
}

function openEdit(source: JobSource) {
    editingSource.value = source;
    editForm.name = source.name;
    editForm.clearErrors();
}

function submitEdit() {
    if (!editingSource.value) {
        return;
    }

    editForm.submit(update(editingSource.value.id), {
        preserveScroll: true,
        onSuccess: () => {
            editingSource.value = null;
            editForm.reset();
        },
    });
}

function confirmDelete(source: JobSource) {
    sourceToDelete.value = source;
}

function deleteSource() {
    if (!sourceToDelete.value) {
        return;
    }

    const source = sourceToDelete.value;
    sourceToDelete.value = null;

    router.delete(destroy(source.id), {
        preserveScroll: true,
    });
}
</script>

<template>
    <Head title="Job sources" />

    <h1 class="sr-only">Job sources</h1>

    <div class="space-y-6">
        <Heading
            variant="small"
            title="Job sources"
            description="Create and manage the sources you use when tracking applications"
        />

        <form class="flex items-start gap-2" @submit.prevent="submitCreate">
            <div class="grid flex-1 gap-2">
                <Label for="source_name" class="sr-only">Source name</Label>
                <Input
                    id="source_name"
                    v-model="createForm.name"
                    placeholder="e.g. LinkedIn"
                    required
                />
                <InputError :message="createForm.errors.name" />
            </div>
            <Button type="submit" :disabled="createForm.processing">
                <Plus />
                Add
            </Button>
        </form>

        <div v-if="props.sources.length === 0" class="text-sm text-muted-foreground">
            You have not added any sources yet. Add one above to use it on new applications.
        </div>

        <ul v-else class="divide-y rounded-md border">
            <li
                v-for="source in props.sources"
                :key="source.id"
                class="flex items-center justify-between gap-3 px-3 py-3"
            >
                <div>
                    <p class="font-medium">{{ source.name }}</p>
                    <p class="text-sm text-muted-foreground">
                        {{ source.job_applications_count }}
                        {{ source.job_applications_count === 1 ? 'application' : 'applications' }}
                    </p>
                </div>
                <div class="flex items-center gap-1">
                    <Button
                        type="button"
                        variant="ghost"
                        size="icon"
                        :aria-label="`Edit ${source.name}`"
                        @click="openEdit(source)"
                    >
                        <Pencil class="size-4" />
                    </Button>
                    <Button
                        type="button"
                        variant="ghost"
                        size="icon"
                        :aria-label="`Delete ${source.name}`"
                        @click="confirmDelete(source)"
                    >
                        <Trash2 class="size-4" />
                    </Button>
                </div>
            </li>
        </ul>
    </div>

    <Dialog :open="editingSource !== null" @update:open="(open) => { if (!open) editingSource = null }">
        <DialogContent>
            <form class="space-y-4" @submit.prevent="submitEdit">
                <DialogHeader>
                    <DialogTitle>Edit source</DialogTitle>
                    <DialogDescription>
                        Updating this name will also update it on your applications.
                    </DialogDescription>
                </DialogHeader>
                <div class="grid gap-2">
                    <Label for="edit_source_name">Source name</Label>
                    <Input id="edit_source_name" v-model="editForm.name" required />
                    <InputError :message="editForm.errors.name" />
                </div>
                <DialogFooter>
                    <DialogClose as-child>
                        <Button type="button" variant="secondary">Cancel</Button>
                    </DialogClose>
                    <Button type="submit" :disabled="editForm.processing">Save</Button>
                </DialogFooter>
            </form>
        </DialogContent>
    </Dialog>

    <AlertDialog :open="sourceToDelete !== null" @update:open="(open) => { if (!open) sourceToDelete = null }">
        <AlertDialogContent>
            <AlertDialogHeader>
                <AlertDialogTitle>Delete this source?</AlertDialogTitle>
                <AlertDialogDescription>
                    <template v-if="sourceToDelete && sourceToDelete.job_applications_count > 0">
                        {{ sourceToDelete.name }} is used by
                        {{ sourceToDelete.job_applications_count }}
                        {{ sourceToDelete.job_applications_count === 1 ? 'application' : 'applications' }}.
                        Remove or reassign those applications before deleting this source.
                    </template>
                    <template v-else>
                        This will permanently delete {{ sourceToDelete?.name }}. This action cannot be undone.
                    </template>
                </AlertDialogDescription>
            </AlertDialogHeader>
            <AlertDialogFooter>
                <AlertDialogCancel>Cancel</AlertDialogCancel>
                <Button
                    variant="destructive"
                    :disabled="Boolean(sourceToDelete && sourceToDelete.job_applications_count > 0)"
                    @click="deleteSource"
                >
                    Delete
                </Button>
            </AlertDialogFooter>
        </AlertDialogContent>
    </AlertDialog>
</template>
