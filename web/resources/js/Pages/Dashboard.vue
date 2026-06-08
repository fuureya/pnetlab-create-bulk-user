<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
</script>

<template>
    <Head title="Monitor - WattVision" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-2xl font-semibold leading-tight text-[#111827] tracking-wide">
                        Monitoreo Global
                    </h2>
                    <p class="text-[#6B7280] text-[14px] mt-1">Datos en tiempo real de consumo eléctrico</p>
                </div>
                <div class="flex items-center gap-2 px-4 py-2 bg-white rounded-md border border-[#E5E7EB] shadow-sm">
                    <div class="w-2.5 h-2.5 rounded-full bg-[#22C55E] animate-pulse"></div>
                    <span class="text-[#22C55E] text-[14px] font-semibold">En vivo</span>
                </div>
            </div>
        </template>

        <!-- Grid Layout: 12 columns -->
        <div class="grid grid-cols-12 gap-6 mt-2">
            
            <!-- Top KPIs (3 cards, 4 cols each) -->
            <div class="col-span-12 md:col-span-4 bg-white rounded-[16px] border border-[#E5E7EB] shadow-sm p-5 flex flex-col justify-between">
                <div class="flex items-center justify-between text-[#6B7280] text-[14px] mb-2">
                    <span>Consumo Actual</span>
                    <svg class="w-5 h-5 text-[#06B6D4]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" /></svg>
                </div>
                <div class="font-['JetBrains_Mono'] font-bold text-3xl text-[#111827]">
                    3,450 <span class="text-[16px] text-[#06B6D4]">W</span>
                </div>
                <div class="mt-2 text-[12px] text-[#22C55E] flex items-center gap-1">
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 17h8m0 0V9m0 8l-8-8-4 4-6-6" /></svg>
                    -2.4% vs ayer
                </div>
            </div>

            <div class="col-span-12 md:col-span-4 bg-white rounded-[16px] border border-[#E5E7EB] shadow-sm p-5 flex flex-col justify-between">
                <div class="flex items-center justify-between text-[#6B7280] text-[14px] mb-2">
                    <span>Acumulado del Mes</span>
                    <svg class="w-5 h-5 text-[#06B6D4]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" /></svg>
                </div>
                <div class="font-['JetBrains_Mono'] font-bold text-3xl text-[#111827]">
                    245.8 <span class="text-[16px] text-[#6B7280]">kWh</span>
                </div>
                <div class="mt-2 text-[14px] text-[#6B7280]">
                    <span class="font-semibold text-[#111827]">~ 185.00</span> Bs.
                </div>
            </div>

            <div class="col-span-12 md:col-span-4 bg-white rounded-[16px] border border-[#E5E7EB] shadow-sm p-5 flex flex-col justify-between">
                <div class="flex items-center justify-between text-[#6B7280] text-[14px] mb-2">
                    <span>Pico Máximo (Hoy)</span>
                    <svg class="w-5 h-5 text-[#EF4444]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                </div>
                <div class="font-['JetBrains_Mono'] font-bold text-3xl text-[#111827]">
                    5,120 <span class="text-[16px] text-[#EF4444]">W</span>
                </div>
                <div class="mt-2 text-[12px] text-[#6B7280]">
                    Registrado a las 14:30
                </div>
            </div>

            <!-- Main Chart & Alerts -->
            <!-- Chart (8 cols) -->
            <div class="col-span-12 lg:col-span-8 bg-white rounded-[16px] border border-[#E5E7EB] shadow-sm p-5 min-h-[400px] flex flex-col">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-[#111827] font-semibold text-[16px]">Curva de Consumo (Últimas 24h)</h3>
                    <div class="flex gap-2">
                        <button class="px-3 py-1 bg-[#F3F4F6] rounded text-[12px] text-[#6B7280] hover:text-[#111827]">Día</button>
                        <button class="px-3 py-1 bg-[#06B6D4]/10 text-[#0891B2] rounded border border-[#06B6D4]/20 text-[12px] font-medium">Semana</button>
                    </div>
                </div>
                <!-- Fake Chart Area -->
                <div class="flex-1 relative border-l border-b border-[#E5E7EB] mb-6 flex items-end">
                    <!-- Grid Lines -->
                    <div class="absolute inset-0 flex flex-col justify-between pointer-events-none">
                        <div class="border-t border-[#F3F4F6] w-full h-0"></div>
                        <div class="border-t border-[#F3F4F6] w-full h-0"></div>
                        <div class="border-t border-[#F3F4F6] w-full h-0"></div>
                        <div class="border-t border-[#F3F4F6] w-full h-0"></div>
                    </div>
                    <!-- Area Chart Visual -->
                    <div class="w-full h-2/3 bg-gradient-to-t from-[#22C55E]/10 to-transparent relative mt-auto border-t-2 border-[#06B6D4]" style="clip-path: polygon(0 100%, 0 60%, 20% 50%, 40% 80%, 60% 40%, 80% 90%, 100% 20%, 100% 100%);">
                    </div>
                    <!-- Data Points -->
                    <div class="absolute left-[20%] top-[66%] w-2 h-2 bg-[#06B6D4] rounded-full shadow-[0_0_8px_rgba(6,182,212,0.5)]"></div>
                    <div class="absolute left-[40%] top-[76%] w-2 h-2 bg-[#06B6D4] rounded-full shadow-[0_0_8px_rgba(6,182,212,0.5)]"></div>
                    <div class="absolute left-[60%] top-[62%] w-2 h-2 bg-[#06B6D4] rounded-full shadow-[0_0_8px_rgba(6,182,212,0.5)]"></div>
                </div>
                <!-- X Axis Labels -->
                <div class="flex justify-between text-[12px] text-[#6B7280] px-2">
                    <span>00:00</span>
                    <span>06:00</span>
                    <span>12:00</span>
                    <span>18:00</span>
                    <span>24:00</span>
                </div>
            </div>

            <!-- Alerts (4 cols) -->
            <div class="col-span-12 lg:col-span-4 flex flex-col gap-4">
                <div class="bg-white rounded-[16px] border border-[#E5E7EB] shadow-sm p-5 flex-1">
                    <h3 class="text-[#111827] font-semibold text-[16px] mb-4">Alertas Recientes</h3>
                    
                    <div class="flex flex-col gap-3">
                        <!-- Alert Item -->
                        <div class="bg-[#FEF2F2] border-l-[4px] border-[#EF4444] p-3 rounded-r-md">
                            <div class="flex justify-between items-start">
                                <span class="text-[#DC2626] text-[14px] font-semibold">Pico Anormal</span>
                                <span class="text-[#EF4444]/70 text-[12px]">Hace 2 min</span>
                            </div>
                            <p class="text-[#B91C1C] text-[12px] mt-1">Se detectó consumo superior a 5kW en el Sector Taller.</p>
                        </div>
                        
                        <!-- Alert Item -->
                        <div class="bg-[#ECFEFF] border-l-[4px] border-[#06B6D4] p-3 rounded-r-md">
                            <div class="flex justify-between items-start">
                                <span class="text-[#0891B2] text-[14px] font-semibold">Corte Programado</span>
                                <span class="text-[#6B7280] text-[12px]">Hace 1 hr</span>
                            </div>
                            <p class="text-[#4B5563] text-[12px] mt-1">Mantenimiento de red estimado para mañana a las 14:00.</p>
                        </div>

                        <!-- Alert Item -->
                        <div class="bg-[#FEF2F2] border-l-[4px] border-[#EF4444] p-3 rounded-r-md">
                            <div class="flex justify-between items-start">
                                <span class="text-[#DC2626] text-[14px] font-semibold">Consumo Vampiro</span>
                                <span class="text-[#EF4444]/70 text-[12px]">Ayer</span>
                            </div>
                            <p class="text-[#B91C1C] text-[12px] mt-1">Equipos en stand-by superan los 200W constantes.</p>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </AuthenticatedLayout>
</template>
