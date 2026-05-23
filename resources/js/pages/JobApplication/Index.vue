<script setup>
import {
    Table, TableBody, TableCell,
    TableHead, TableHeader, TableRow,
} from '@/components/ui/table';
import {
    Pagination, PaginationContent, PaginationEllipsis,
    PaginationItem, PaginationNext, PaginationPrevious,
} from '@/components/ui/pagination';
import { Button } from '@/components/ui/button';
import { Head, router } from '@inertiajs/vue3';
import { Plus } from 'lucide-vue-next';


import SearchInput from '@/components/SearchInput.vue';
import CreateApplicationModal from '@/components/JobApplication/CreateApplicationModal.vue';
import DeleteApplicationModal from '@/components/JobApplication/DeleteApplicationModal.vue';
import EditApplicationModal from '@/components/JobApplication/EditApplicationModal.vue';
import FlashMessage from '@/components/FlashMessage.vue';


const props = defineProps({
    application: {
        type: Object,
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
    filters: {
        type: Object,
        default: () => ({})
    },
})

defineOptions({
    layout: {
        breadcrumbs: [
            {
                title: 'Job Applications',
                href: '/JobApplication',
            },
        ],
    },
});

function goToPage(url) {
    if (url) router.visit(url, {
        preserveScroll: true,
        preserveState: true,  // ← add this
    });
}

// Build a clean page list with ellipsis logic
function getPageLinks() {
    return props.application.links.filter(
        link => !link.label.includes('Previous') && !link.label.includes('Next')
    );
}
</script>

<template>
    <Head title="Job Applications" />
    <FlashMessage />
    <div class="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4">

        <div class="flex justify-between">
            <h1 class="text-2xl font-bold">Manage Applications</h1>
            <CreateApplicationModal :statusOptions="props.statusOptions" :sourceOptions="props.sourceOptions">
                <Button>
                    <Plus />
                    Add Application
                </Button>
            </CreateApplicationModal>
        </div>
        <div class="mt-2 max-w-sm">
            <SearchInput v-model="props.filters.search" routeName="JobApplication.index" />
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
                <template v-if="props.application.data.length > 0">
                    <TableRow v-for="application in props.application.data" :key="application.id">
                        <TableCell>{{ application.company_name }}</TableCell>
                        <TableCell>{{ application.job_title }}</TableCell>
                        <TableCell>{{ application.location }}</TableCell>
                        <TableCell>{{ new Date(application.application_date).toLocaleDateString('en-US') }}</TableCell>
                        <TableCell>{{ application.application_status }}</TableCell>
                        <TableCell>{{ application.source }}</TableCell>
                        <TableCell>{{ application.remarks }}</TableCell>
                        <TableCell>
                            <EditApplicationModal :application="application" :statusOptions="props.statusOptions"
                                :sourceOptions="props.sourceOptions">
                                <button class="text-blue-500 hover:underline">Edit</button>
                            </EditApplicationModal>
                            <DeleteApplicationModal :application="application">
                                <button class="text-red-500 hover:underline ml-2">Delete</button>
                            </DeleteApplicationModal>
                        </TableCell>
                    </TableRow>
                </template>
                <TableRow v-else>
                    <TableCell colspan="8" class="py-12 text-center text-muted-foreground">
                        No applications found.
                    </TableCell>
                </TableRow>
            </TableBody>
        </Table>
        
        
         <!-- Pagination footer -->
        <div class="flex items-center justify-between mt-auto">

            <!-- Showing X-Y of Z -->
            <p class="text-sm text-muted-foreground">
                Showing {{ props.application.from }}–{{ props.application.to }} of {{ props.application.total }} results
            </p>

            <Pagination>
                <PaginationContent>

                    <PaginationItem class="mr-6" v-if="props.application.links.prev">
                        <PaginationPrevious @click="goToPage(props.application.links.prev)" />
                    </PaginationItem>

                    <template v-for="link in getPageLinks()" :key="link.label">
                        <!-- Ellipsis -->
                        <PaginationItem v-if="link.label === '...'">
                            <PaginationEllipsis />
                        </PaginationItem>

                        <!-- Page number button -->
                        <PaginationItem v-else>
                            <Button :variant="link.active ? 'default' : 'outline'" size="sm" :disabled="!link.url"
                                @click="goToPage(link.url)">
                                {{ link.label }}
                            </Button>
                        </PaginationItem>
                    </template>

                    <PaginationItem class="ml-5" v-if="props.application.links.next">
                        <PaginationNext @click="goToPage(props.application.links.next)" />
                    </PaginationItem>

                </PaginationContent>
            </Pagination>
        </div>
    </div>
</template>
