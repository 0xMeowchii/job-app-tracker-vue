<script setup lang="ts">
import {
    Table, TableBody, TableCell,
    TableHead, TableHeader, TableRow,
} from '@/components/ui/table';
import { Button } from '@/components/ui/button';
import { Head } from '@inertiajs/vue3';
import { Plus } from 'lucide-vue-next';
import PlaceholderPattern from '@/components/PlaceholderPattern.vue';
import { dashboard } from '@/routes';

defineOptions({
    layout: {
        breadcrumbs: [
            {
                title: 'Job Applications',
                href: '/JobApplications',
            },
        ],
    },
});
</script>

<template>

    <Head title="Job Applications" />

    <div class="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4">
        <div class="flex justify-between">
            <h1 class="text-2xl font-bold">Manage Applications</h1>
            <Button>
                <Plus/>
                Add Application
            </Button>
        </div>
        <div class="mt-2 max-w-sm">
            <input type="text" placeholder="Search Applications..." />
        </div>

        <Table class="mt-2">
            <TableHeader>
                <TableRow>
                    <TableHead>Company</TableHead>
                    <TableHead>Job Title</TableHead>
                    <TableHead>Location</TableHead>
                    <TableHead>Date</TableHead>
                    <TableHead>Status</TableHead>
                    <TableHead>Source</TableHead>
                    <TableHead>Remarks</TableHead>
                    <TableHead>Actions</TableHead>
                </TableRow>
            </TableHeader>
            <TableBody>
                <TableRow>
                    <TableCell></TableCell>
                    <TableCell></TableCell>
                    <TableCell></TableCell>
                    <TableCell></TableCell>
                    <TableCell></TableCell>
                    <TableCell></TableCell>
                    <TableCell></TableCell>
                    <TableCell>
                        <button class="text-blue-500 hover:underline">Edit</button>
                        <button class="text-red-500 hover:underline ml-2">Delete</button>
                    </TableCell>
                </TableRow>
            </TableBody>
        </Table>
    </div>
</template>
