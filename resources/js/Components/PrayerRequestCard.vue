<script setup lang="ts">
import dayjs from 'dayjs';
import relativeTime from 'dayjs/plugin/relativeTime';
import PrayerButton from '@/Components/PrayerButton.vue';
dayjs.extend(relativeTime);

const props = defineProps<{
    request: {
        id: number;
        title?: string;
        content: string;
        user: {
            name?: string;
        };
        created_at: string;
        prayer_count?: number;
        is_praise?: boolean;
    };
}>();

</script>

<template>
    <div class="bg-white p-6 rounded-lg shadow">
        <h3 class="text-lg font-semibold mb-2">{{ request.title || 'Prayer Request' }}</h3>
        <p class="text-gray-700 mb-4">{{ request.content }}</p>
        <div class="text-sm text-gray-500 mb-4">
            <span>Requested by {{ request.user?.name || 'Anonymous' }}</span>
            <span class="mx-2">•</span>
            <span>{{ dayjs(request.created_at).fromNow() }}</span>
        </div>
        
        <div class="flex items-center space-x-4">
            <PrayerButton
                :request-id="request.id"
                :initial-prayer-count="request.prayer_count || 0"
                :is-praise="request.is_praise"
            />
        </div>
    </div>
</template> 