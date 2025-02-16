<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';

defineProps({
    requests: Array,
});
</script>

<template>
    <Head title="Prayer Requests" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Prayer Requests
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <div v-for="request in requests" :key="request.id" class="mb-4">
                            <h3 class="text-lg font-semibold">{{ request.title }}</h3>
                            <p class="text-gray-600">{{ request.request }}</p>
                            <div class="mt-2 text-sm text-gray-500">
                                Requested by: {{ request.user?.name }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template> 