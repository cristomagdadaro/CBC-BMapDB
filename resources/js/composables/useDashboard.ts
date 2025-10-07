// A lightweight, reusable composable for standardized dashboard fetching
// Provides: data, isLoading, lastUpdated, refresh
import { ref, onMounted } from 'vue';

export function useDashboard<T>(fetcher: () => Promise<T>) {
    const data = ref<T | null>(null);
    const isLoading = ref<boolean>(true);
    const lastUpdated = ref<string | null>(null);
    const error = ref<unknown | null>(null);

    const refresh = async () => {
        try {
            isLoading.value = true;
            error.value = null;
            const result = await fetcher();
            //@ts-ignore
            data.value = result as T;
            lastUpdated.value = new Date().toISOString();
        } catch (e) {
            console.error('Dashboard refresh failed:', e);
            error.value = e;
        } finally {
            isLoading.value = false;
        }
    };

    onMounted(refresh);

    return {
        data,
        isLoading,
        lastUpdated,
        error,
        refresh,
    };
}

