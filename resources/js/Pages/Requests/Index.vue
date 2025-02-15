<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Request from '@/Components/Request.vue';

defineProps(['requests']);
</script>
 
<template>
    <Head title="My Prayer Requests" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                My Prayer Requests
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <div class="mb-6">
                            <Link
                                :href="route('requests.create')"
                                class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700"
                            >
                                New Prayer Request
                            </Link>
                        </div>

                        <div v-if="requests" class="mt-6 space-y-6 bg-gray-100 p-4 rounded-md">
                            <Request v-for="request in Array.isArray(requests) ? requests : [requests]" :key="request.id" :request="request" />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>