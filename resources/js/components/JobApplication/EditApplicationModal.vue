<script setup lang="ts">
import {
    Dialog, DialogClose, DialogContent, DialogDescription,
    DialogFooter, DialogHeader, DialogTitle, DialogTrigger,
} from '@/components/ui/dialog';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import {
    Select,
    SelectContent,
    SelectGroup,
    SelectItem,
    SelectLabel,
    SelectTrigger,
    SelectValue,
} from '@/components/ui/select'

import InputError from '@/components/InputError.vue';

import { ref } from 'vue';
import { useForm } from '@inertiajs/vue3';
import { route } from 'ziggy-js';
import {update} from "@/routes/JobApplication";

const props = defineProps({
    application: {
        type: Object as () => {
            id: number,
            company_name: string,
            job_title: string,
            location: string,
            application_date: string,
            application_status: string,
            source: string,
            remarks: string,
        },
        required: true,
    },
    statusOptions: {
        type: Array,
        required: true,
    },
    sourceOptions: {
        type: Array,
        required: true,
    },
})

const open = ref(false);

const form = useForm({...props.application, application_date: props.application.application_date.split('T')[0] });

function handleOpenChange(value: boolean) {
    open.value = value;
    if (!value) form.reset();
}

function submit() {
    form.submit(update(props.application.id), {
        onSuccess: () => {
            open.value = false;
        },
    });
}
</script>

<template>
    <Dialog :open="open" @update:open="handleOpenChange">
        <DialogTrigger as-child>
            <slot />
        </DialogTrigger>
        <DialogContent>
            <form @submit.prevent="submit" class="space-y-6">
                <DialogHeader>
                    <DialogTitle>Edit Application</DialogTitle>
                    <DialogDescription>
                        Update the application information below.
                    </DialogDescription>
                </DialogHeader>

                <div class="grid grid-cols-2 gap-x-6 gap-y-4 mt-6">
                    <div class="flex flex-col gap-4">
                        <div class="grid gap-2">
                            <Label for="company_name">Company Name</Label>
                            <Input id="company_name" v-model="form.company_name" required />
                            <InputError :message="form.errors.company_name" />
                        </div>

                        <div class="grid gap-2">
                            <Label for="job_title">Job Title</Label>
                            <Input id="job_title" v-model="form.job_title" required />
                            <InputError :message="form.errors.job_title" />
                        </div>
                        <div class="grid gap-2">
                            <Label for="location">Location</Label>
                            <Input id="location" v-model="form.location" required />
                            <InputError :message="form.errors.location" />
                        </div>
                    </div>

                    <div class="flex flex-col gap-4">
                        <div class="grid gap-2">
                            <Label for="application_date">Date</Label>
                            <input type="date" id="application_date" v-model="form.application_date" required
                                class="w-full rounded-md border border-input bg-transparent px-3 py-2 text-sm ring-offset-background file:border-0 file:bg-transparent file:text-sm file:font-medium placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50" />
                            <InputError :message="form.errors.application_date" />
                        </div>

                        <div class="grid gap-2">
                            <Label for="application_status">Status</Label>
                            <Select v-model="form.application_status" id="application_status">
                                <SelectTrigger class="w-full">
                                    <SelectValue placeholder="Select a Status" />
                                </SelectTrigger>
                                <SelectContent>
                                    <SelectGroup>
                                        <SelectLabel>Status</SelectLabel>
                                        <SelectItem v-for="status in (statusOptions as string[])" :value="status" :key="status">
                                            {{ status }}
                                        </SelectItem>
                                    </SelectGroup>
                                </SelectContent>
                            </Select>
                            <InputError :message="form.errors.application_status" />
                        </div>

                        <div class="grid gap-2">
                            <Label for="application_source">Source</Label>
                            <Select v-model="form.source" id="application_source">
                                <SelectTrigger class="w-full">
                                    <SelectValue placeholder="Select a Source" />
                                </SelectTrigger>
                                <SelectContent>
                                    <SelectGroup>
                                        <SelectLabel>Source</SelectLabel>
                                        <SelectItem v-for="source in (sourceOptions as string[])" :value="source" :key="source">
                                            {{ source }}
                                        </SelectItem>
                                    </SelectGroup>
                                </SelectContent>
                            </Select>
                            <InputError :message="form.errors.source" />
                        </div>
                    </div>
                </div>
                <div class="mt-1">
                    <label for="remarks">Remarks</label>
                    <Input id="remarks" v-model="form.remarks" />
                    <InputError :message="form.errors.remarks" />
                </div>



                <DialogFooter class="gap-2">
                    <DialogClose as-child>
                        <Button type="button" variant="secondary">Cancel</Button>
                    </DialogClose>
                    <Button type="submit" :disabled="form.processing">
                        Update
                    </Button>
                </DialogFooter>
            </form>
        </DialogContent>
    </Dialog>
</template>