<script setup>
import { Head, Link } from '@inertiajs/vue3';
import { dashboard } from '@/routes';
import { index as jobSources } from '@/routes/job-sources';
import { computed } from 'vue'

defineOptions({
    layout: {
        breadcrumbs: [
            {
                title: 'Dashboard',
                href: dashboard(),
            },
        ],
    },
});

const STATUS_COLORS = {
    'no response': 'bg-slate-400',
    withdraw: 'bg-amber-500',
    waiting: 'bg-blue-500',
    rejected: 'bg-rose-500',
    hired: 'bg-emerald-500',
}

const SOURCE_COLORS = [
    'bg-indigo-500',
    'bg-sky-500',
    'bg-violet-500',
    'bg-teal-500',
    'bg-orange-500',
    'bg-pink-500',
]

const props = defineProps({
    totals: {
        type: Object,
        required: true,
    },
    chartData: {
        type: Array,
        required: true,
    },
    sourceChartData: {
        type: Array,
        default: () => [],
    },
})

const maxCount = computed(() => {
    return Math.max(...props.chartData.map(item => item.count), 1)
})

const maxSourceCount = computed(() => {
    return Math.max(...props.sourceChartData.map(item => item.count), 1)
})


</script>

<template>

    <Head title="Dashboard" />

    <div class="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4">
        <div class="py-3">
            <div class="mx-auto grid max-w-7xl gap-6 px-4 sm:px-6 lg:px-8">
                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
                    <div class="rounded-lg bg-white p-4 shadow-sm">
                        <p class="text-sm text-gray-500">Total Applications</p>
                        <p class="mt-1 text-3xl font-semibold text-gray-900">{{ totals.total }}</p>
                    </div>
                    <div class="rounded-lg bg-white p-4 shadow-sm">
                        <p class="text-sm text-gray-500">Hired</p>
                        <p class="mt-1 text-3xl font-semibold text-emerald-600">{{ totals.hired }}</p>
                    </div>
                    <div class="rounded-lg bg-white p-4 shadow-sm">
                        <p class="text-sm text-gray-500">Rejected</p>
                        <p class="mt-1 text-3xl font-semibold text-rose-600">{{ totals.rejected }}</p>
                    </div>
                    <div class="rounded-lg bg-white p-4 shadow-sm">
                        <p class="text-sm text-gray-500">Pending</p>
                        <p class="mt-1 text-3xl font-semibold text-blue-600">{{ totals.waiting + totals.no_response }}
                        </p>
                    </div>
                </div>
                <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
                    <div class="rounded-lg bg-white p-6 shadow-sm h-auto">
                        <h3 class="text-lg font-semibold text-gray-900">Application Status Overview</h3>
                        <p class="mt-1 text-sm text-gray-500">
                            Simple visual breakdown of your applications by status.
                        </p>
                        <div class="mt-6 space-y-4">
                            <div v-for="item in chartData" :key="item.status">
                                <div class="mb-1 flex items-center justify-between text-sm">
                                    <span class="font-medium text-gray-700">
                                        {{ item.status }}
                                    </span>

                                    <span class="text-gray-600">
                                        {{ item.count }}
                                    </span>
                                </div>

                                <div class="h-3 w-full rounded-full bg-gray-200">
                                    <div class="h-3 rounded-full" :class="STATUS_COLORS[item.status] ?? 'bg-gray-500'"
                                        :style="{ width: `${(item.count / maxCount) * 100}%` }" />
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="rounded-lg bg-white p-6 shadow-sm h-auto">
                        <h3 class="text-lg font-semibold text-gray-900">Applications by Source</h3>
                        <p class="mt-1 text-sm text-gray-500">
                            See which job boards and channels you apply through most.
                        </p>
                        <div v-if="sourceChartData.length === 0" class="mt-6 text-sm text-gray-500">
                            No sources yet.
                            <Link :href="jobSources()" class="font-medium text-gray-900 underline">
                                Add sources in Settings
                            </Link>
                            to start tracking them here.
                        </div>
                        <div v-else class="mt-6 space-y-4">
                            <div v-for="(item, index) in sourceChartData" :key="item.source">
                                <div class="mb-1 flex items-center justify-between text-sm">
                                    <span class="font-medium text-gray-700">
                                        {{ item.source }}
                                    </span>

                                    <span class="text-gray-600">
                                        {{ item.count }}
                                    </span>
                                </div>

                                <div class="h-3 w-full rounded-full bg-gray-200">
                                    <div class="h-3 rounded-full"
                                        :class="SOURCE_COLORS[index % SOURCE_COLORS.length]"
                                        :style="{ width: `${(item.count / maxSourceCount) * 100}%` }" />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
