<script setup>
import { ref } from 'vue';
import { useForm } from '@inertiajs/vue3';

const props = defineProps({
    requestId: {
        type: Number,
        required: true
    },
    initialPrayerCount: {
        type: Number,
        default: 0
    },
    isPraise: {
        type: Boolean,
        default: false
    },
    buttonClass: {
        type: String,
        default: 'inline-flex items-center px-4 py-2 bg-blue-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150'
    }
});

const prayerCount = ref(props.initialPrayerCount);
const form = useForm({});

const pray = () => {
    form.post(route('requests.pray', props.requestId), {
        preserveScroll: true,
        onSuccess: () => {
            prayerCount.value = form.recentlySuccessful ? props.initialPrayerCount + 1 : prayerCount.value;
        }
    });
};
</script>

<template>
    <div class="flex items-center">
        <button
            @click="pray"
            :class="buttonClass"
        >
            🙏 {{ isPraise ? 'PRAISED' : 'PRAYED' }}
        </button>
        <span class="text-sm text-gray-600 ml-2">
            {{ prayerCount }} {{ prayerCount === 1 ? 'prayer' : 'prayers' }} offered
        </span>
    </div>
</template>