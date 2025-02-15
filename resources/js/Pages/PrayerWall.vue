<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import PrayerRequestCard from '@/Components/PrayerRequestCard.vue';

defineProps<{
    requests: {
        data: Array<{
            id: number;
            title: string;
            content: string;
            user: {
                name: string;
            };
            created_at: string;
        }>;
    };
}>();
</script>

<template>
    <Head title="Prayer Wall" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Prayer Wall
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900 space-y-6">
                        <div v-if="!requests.data?.length" class="text-center py-8">
                            <p class="text-gray-500">No prayer requests yet.</p>
                        </div>
                        
                        <div v-if="requests.data?.length" class="space-y-6">
                            <PrayerRequestCard
                                v-for="request in requests.data"
                                :key="request.id"
                                :request="request"
                            />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template> 