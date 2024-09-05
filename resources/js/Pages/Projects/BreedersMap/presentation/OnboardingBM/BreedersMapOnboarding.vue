<template>
    <VOnboardingWrapper ref="wrapper" :steps="steps">

        <template #default="{ previous, next, step, exit, isFirst, isLast, index }">
            <VOnboardingStep>
                <div class="bg-white shadow sm:rounded-lg">
                    <div class="px-4 py-5 sm:p-6">
                        <div v-if="step" class="sm:flex sm:items-center sm:justify-between">
                            <div v-if="step.content">
                                <h3 v-if="step.content.title" class="text-lg font-medium leading-6 text-gray-900">{{ step.content.title }}</h3>
                                <div v-if="step.content.description" class="mt-2 max-w-xl text-sm text-gray-500">
                                    <p>{{ step.content.description }}</p>
                                </div>
                            </div>
                            <div class="mt-5 space-x-4 sm:mt-0 sm:ml-6 sm:flex sm:flex-shrink-0 sm:items-center relative">
                                <span class="absolute right-0 bottom-full mb-2 mr-2 text-gray-600 font-medium text-xs">{{ `${index + 1}/${steps.length}` }}</span>
                                <template v-if="!isFirst">
                                    <button @click="previous" type="button" class="inline-flex items-center justify-center rounded-md border border-transparent bg-yellow-100 px-4 py-2 font-medium text-yellow-700 hover:bg-yellow-200 focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:ring-offset-2 sm:text-sm">Previous</button>
                                </template>
                                <button @click="next" type="button" class="inline-flex items-center rounded-md border border-transparent bg-indigo-600 px-4 py-2 font-medium text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 sm:text-sm">{{ isLast ? 'Finish' : 'Next' }}</button>
                            </div>
                        </div>
                    </div>
                </div>
            </VOnboardingStep>
        </template>

    </VOnboardingWrapper>
</template>

<script>
import { defineComponent, ref } from 'vue'
import { VOnboardingWrapper, VOnboardingStep, useVOnboarding } from 'v-onboarding'
import 'v-onboarding/dist/style.css'

export default defineComponent({
    name: "BreedersMapOnboarding",
    components: {
        VOnboardingWrapper,
        VOnboardingStep
    },
    setup() {
        const wrapper = ref(null)
        const { start, goToStep, finish } = useVOnboarding(wrapper)

        const steps = [
            {
                attachTo: { element: '#bm-welcome-box' },
                content: {
                    title: "Ahoy, Researcher! Welcome Aboard!",
                    description: "Discover the vast network of breeders across the Philippines focused on biotechnology and crop improvement. Let's explore how you can make the most of this platform!"
                }
            },
            {
                attachTo: { element: '#bm-filter-dropdown' },
                content: {
                    title: "Powerful Data Filtering",
                    description: "Refine your search using our advanced filtering options to find breeders and commodities quickly."
                }
            },
            {
                attachTo: { element: '#bm-listfilter-dropdown' },
                content: {
                    title: "Select a List",
                    description: "Choose a specific list to narrow down the breeder results by category or criteria."
                }
            },
            {
                attachTo: { element: '#bm-commodityfilter-dropdown' },
                content: {
                    title: "Filter by Commodity",
                    description: "Looking for breeders specializing in a certain crop? Use this filter to select your commodity of interest."
                }
            },
            {
                attachTo: { element: '#bm-locationfilter-dropdown' },
                content: {
                    title: "Filter by Location",
                    description: "Want to focus on a specific region or area? This filter lets you group data by geographic location."
                }
            },
            {
                attachTo: { element: '#bm-cprfilter-dropdown' },
                content: {
                    title: "Drill Down to Specific Areas",
                    description: "Get even more specific by narrowing your data to individual provinces or cities."
                }
            },
            {
                attachTo: { element: '#bm-data-charts' },
                content: {
                    title: "Visualize Data with Charts",
                    description: "Use interactive charts to easily interpret breeder and crop data at a glance."
                }
            },
            {
                attachTo: { element: '#bm-coloropacity-slider' },
                content: {
                    title: "Adjust Chart Opacity",
                    description: "Change the opacity of the chart colors to better visualize overlapping data or patterns."
                }
            },
            {
                attachTo: { element: '#bm-data-table' },
                content: {
                    title: "Comprehensive Data Table",
                    description: "Get a detailed overview of all the filtered breeder data in one place."
                }
            },
            {
                attachTo: { element: '#bm-search-box' },
                content: {
                    title: "Search the Data",
                    description: "Use this search box to quickly find specific breeders, commodities, or locations."
                }
            },
            {
                attachTo: { element: '#bm-columnsfilter-dropdown' },
                content: {
                    title: "Column-Specific Search",
                    description: "Looking for details within a certain data column? This filter lets you zero in on the info you need."
                }
            },
        ]

        const startOnboarding = () => {
            start();
        }

        const goToNextStep = (index) => {
            goToStep(index);
        }

        const goToFinish = () => {
            finish();
        }

        return {
            wrapper,
            steps,
            startOnboarding,  // Expose the method to trigger onboarding
            goToNextStep,  // Expose the method to navigate to a specific step
            goToFinish,  // Expose the method to finish onboarding
        }
    },
    mounted() {
        //this.startOnboarding();

        document.getElementById('bm-qg-start').addEventListener('click', () => {
            this.startOnboarding();
        });
    }
})
</script>
