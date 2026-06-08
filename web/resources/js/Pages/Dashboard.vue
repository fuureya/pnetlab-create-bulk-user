<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
</script>

<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <template #header>
            <h2
                class="text-2xl font-bold leading-tight text-gray-900 font-['Red_Hat_Display']"
            >
                Property Search
            </h2>
        </template>

        <div class="py-8 font-['DM_Sans'] bg-gray-50 min-h-screen">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8 flex flex-col lg:flex-row gap-8">
                <!-- Main Content: Listings -->
                <div class="w-full lg:w-2/3">
                    <!-- Filters -->
                    <div class="mb-6 flex gap-2 overflow-x-auto pb-2">
                        <button class="px-4 py-1.5 bg-blue-600 text-white rounded-md font-medium text-sm shadow-sm">All Listings</button>
                        <button class="px-4 py-1.5 bg-white border border-gray-200 text-gray-900 rounded-md font-medium text-sm hover:bg-gray-50 transition">For Sale</button>
                        <button class="px-4 py-1.5 bg-white border border-gray-200 text-gray-900 rounded-md font-medium text-sm hover:bg-gray-50 transition">For Rent</button>
                    </div>

                    <!-- Grid -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                        <!-- Card 1 -->
                        <div class="bg-white border border-gray-200 shadow-sm rounded-lg overflow-hidden flex flex-col transition hover:shadow-md hover:-translate-y-1 duration-200">
                            <div class="relative w-full aspect-[16/10] bg-gray-200">
                                <!-- Image placeholder -->
                                <img src="https://images.unsplash.com/photo-1564013799919-ab600027ffc6?auto=format&fit=crop&q=80&w=800" alt="House" class="w-full h-full object-cover" />
                                <div class="absolute top-0 right-0 bg-green-600 text-white px-3 py-1 rounded-bl-md font-bold text-sm shadow-sm">
                                    $450,000
                                </div>
                                <div class="absolute bottom-2 left-2 bg-white/90 text-blue-600 text-xs font-bold px-2 py-1 rounded shadow-sm">
                                    FEATURED
                                </div>
                            </div>
                            <div class="p-4 flex flex-col gap-1">
                                <h3 class="font-['Red_Hat_Display'] font-semibold text-lg text-gray-900">123 Maple Street</h3>
                                <p class="text-sm text-gray-500">Springfield, IL 62704</p>
                                <div class="flex gap-4 text-sm text-gray-700 mt-3 border-t border-gray-100 pt-3">
                                    <span class="flex items-center gap-1"><strong class="text-gray-900">3</strong> Beds</span>
                                    <span class="flex items-center gap-1"><strong class="text-gray-900">2</strong> Baths</span>
                                    <span class="flex items-center gap-1"><strong class="text-gray-900">1,500</strong> sqft</span>
                                </div>
                            </div>
                        </div>

                        <!-- Card 2 -->
                        <div class="bg-white border border-gray-200 shadow-sm rounded-lg overflow-hidden flex flex-col transition hover:shadow-md hover:-translate-y-1 duration-200">
                            <div class="relative w-full aspect-[16/10] bg-gray-200">
                                <!-- Image placeholder -->
                                <img src="https://images.unsplash.com/photo-1600596542815-ffad4c1539a9?auto=format&fit=crop&q=80&w=800" alt="House" class="w-full h-full object-cover" />
                                <div class="absolute top-0 right-0 bg-green-600 text-white px-3 py-1 rounded-bl-md font-bold text-sm shadow-sm">
                                    $850,000
                                </div>
                            </div>
                            <div class="p-4 flex flex-col gap-1">
                                <h3 class="font-['Red_Hat_Display'] font-semibold text-lg text-gray-900">456 Oak Avenue</h3>
                                <p class="text-sm text-gray-500">Chicago, IL 60601</p>
                                <div class="flex gap-4 text-sm text-gray-700 mt-3 border-t border-gray-100 pt-3">
                                    <span class="flex items-center gap-1"><strong class="text-gray-900">4</strong> Beds</span>
                                    <span class="flex items-center gap-1"><strong class="text-gray-900">3</strong> Baths</span>
                                    <span class="flex items-center gap-1"><strong class="text-gray-900">2,800</strong> sqft</span>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Card 3 -->
                        <div class="bg-white border border-gray-200 shadow-sm rounded-lg overflow-hidden flex flex-col transition hover:shadow-md hover:-translate-y-1 duration-200">
                            <div class="relative w-full aspect-[16/10] bg-gray-200">
                                <img src="https://images.unsplash.com/photo-1512917774080-9991f1c4c750?auto=format&fit=crop&q=80&w=800" alt="House" class="w-full h-full object-cover" />
                                <div class="absolute top-0 right-0 bg-green-600 text-white px-3 py-1 rounded-bl-md font-bold text-sm shadow-sm">
                                    $1,200,000
                                </div>
                            </div>
                            <div class="p-4 flex flex-col gap-1">
                                <h3 class="font-['Red_Hat_Display'] font-semibold text-lg text-gray-900">789 Pine Lane</h3>
                                <p class="text-sm text-gray-500">Evanston, IL 60201</p>
                                <div class="flex gap-4 text-sm text-gray-700 mt-3 border-t border-gray-100 pt-3">
                                    <span class="flex items-center gap-1"><strong class="text-gray-900">5</strong> Beds</span>
                                    <span class="flex items-center gap-1"><strong class="text-gray-900">4</strong> Baths</span>
                                    <span class="flex items-center gap-1"><strong class="text-gray-900">4,200</strong> sqft</span>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

                <!-- Sidebar: Map -->
                <div class="w-full lg:w-1/3">
                    <div class="bg-gray-100 shadow-lg rounded-2xl h-[600px] w-full border border-gray-200 flex items-center justify-center relative overflow-hidden sticky top-8">
                        <!-- Fake Map Background -->
                        <div class="absolute inset-0 bg-[url('https://www.transparenttextures.com/patterns/cartographer.png')] bg-blue-50 opacity-60"></div>
                        
                        <!-- Map Pins -->
                        <div class="absolute top-1/4 left-1/4">
                            <div class="relative flex items-center justify-center">
                                <div class="w-4 h-4 bg-blue-600 rounded-full border-2 border-white shadow-md z-10"></div>
                                <div class="absolute w-8 h-8 bg-blue-600 rounded-full opacity-20 animate-ping"></div>
                            </div>
                        </div>
                        <div class="absolute top-1/2 left-2/3">
                            <div class="relative flex items-center justify-center">
                                <div class="w-4 h-4 bg-blue-600 rounded-full border-2 border-white shadow-md z-10"></div>
                            </div>
                        </div>
                        <div class="absolute bottom-1/3 left-1/2">
                            <div class="relative flex items-center justify-center">
                                <div class="w-4 h-4 bg-blue-600 rounded-full border-2 border-white shadow-md z-10"></div>
                            </div>
                        </div>

                        <div class="z-10 flex flex-col items-center bg-white/90 px-4 py-3 rounded-lg shadow-sm backdrop-blur-sm border border-gray-200">
                            <svg class="w-8 h-8 text-blue-600 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7" /></svg>
                            <span class="text-gray-800 font-medium text-sm">Interactive Map</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
