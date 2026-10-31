<script setup>
import { Head, Link } from '@inertiajs/vue3';
import PublicLayout from '../../Layouts/PublicLayout.vue';

const props = defineProps({
  projects: {
    type: Array,
    default: () => [],
  },
});
</script>

<template>
  <PublicLayout>
    <Head>
      <title>Dự Án Tiêu Biểu - Toàn Cầu Technology</title>
      <meta name="description" content="Danh mục các công trình và dự án tiêu biểu được Toàn Cầu Technology triển khai trên toàn quốc." />
    </Head>

    <!-- Header Section - High-Tech Modern IT Banner -->
    <div class="relative bg-[#061224] text-white py-14 border-b border-slate-800 overflow-hidden">
      <!-- High-Tech Cyber Grid & Glow -->
      <div class="absolute inset-0 bg-[linear-gradient(to_right,#0284c715_1px,transparent_1px),linear-gradient(to_bottom,#0284c715_1px,transparent_1px)] bg-[size:3.5rem_3.5rem] [mask-image:radial-gradient(ellipse_70%_60%_at_50%_35%,#000_60%,transparent_100%)] pointer-events-none"></div>
      <div class="absolute -top-20 -right-20 w-80 h-80 bg-cyan-500/15 rounded-full blur-3xl pointer-events-none"></div>
      <div class="absolute -bottom-20 -left-20 w-80 h-80 bg-blue-600/15 rounded-full blur-3xl pointer-events-none"></div>

      <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <span class="text-xs font-bold uppercase tracking-wider text-cyan-400 bg-cyan-400/10 px-3 py-1 rounded-full">
          Case Studies & Implementation
        </span>
        <h1 class="text-3xl sm:text-4xl font-extrabold mt-3 tracking-tight">
          Dự Án & Khách Hàng Tiêu Biểu
        </h1>
        <p class="text-slate-300 text-xs sm:text-sm mt-2 max-w-2xl leading-relaxed">
          Tổng hợp các dự án triển khai hạ tầng đám mây, kênh truyền Leased Line và truyền thanh số 4G đã đi vào vận hành ổn định thực tế.
        </p>
      </div>
    </div>

    <div class="py-12 bg-slate-50">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
          <div 
            v-for="project in projects" 
            :key="project.id"
            class="bg-white rounded-3xl border border-slate-200 p-7 shadow-sm hover:shadow-lg transition space-y-4"
          >
            <div class="h-48 bg-slate-100 rounded-2xl flex items-center justify-center text-slate-400">
              <svg class="w-16 h-16" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
              </svg>
            </div>
            <div>
              <span class="text-xs font-bold text-[#0284C7] uppercase bg-cyan-50 px-2.5 py-1 rounded-md">
                {{ project.client_name }}
              </span>
              <h2 class="text-lg font-bold text-slate-900 mt-2">{{ project.title }}</h2>
              <p class="text-xs text-slate-600 mt-2 leading-relaxed">{{ project.description }}</p>
            </div>
          </div>
        </div>

      </div>
    </div>
  </PublicLayout>
</template>
