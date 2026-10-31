<script setup>
import { Head, Link } from '@inertiajs/vue3';
import PublicLayout from '../../Layouts/PublicLayout.vue';

const props = defineProps({
  posts: {
    type: Object,
    default: () => ({ data: [] }),
  },
});
</script>

<template>
  <PublicLayout>
    <Head>
      <title>Tin Tức & Kiến Thức Kỹ Thuật - Toàn Cầu Tech</title>
      <meta name="description" content="Cập nhật tin tức công nghệ hạ tầng số, hướng dẫn định tuyến BGP, bảo mật mạng và xu hướng IoT truyền thanh số." />
    </Head>

    <div class="bg-[#0F294A] text-white py-14 border-b border-slate-700">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <span class="text-xs font-bold uppercase tracking-wider text-cyan-400 bg-cyan-400/10 px-3 py-1 rounded-full">
          Tech Insights & Whitepapers
        </span>
        <h1 class="text-3xl sm:text-4xl font-extrabold mt-3 tracking-tight">
          Tin Tức & Phân Tích Kỹ Thuật
        </h1>
        <p class="text-slate-300 text-xs sm:text-sm mt-2 max-w-2xl leading-relaxed">
          Góc chia sẻ chuyên môn từ đội ngũ kiến trúc sư hạ tầng, chuyên gia bảo mật và kỹ sư viễn thông Toàn Cầu Technology.
        </p>
      </div>
    </div>

    <div class="py-12 bg-slate-50">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
          <article 
            v-for="post in posts.data" 
            :key="post.id"
            class="bg-white rounded-3xl border border-slate-200 overflow-hidden shadow-sm hover:shadow-lg transition flex flex-col justify-between"
          >
            <div>
              <div class="h-44 bg-slate-100 flex items-center justify-center text-slate-400">
                <svg class="w-12 h-12" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/>
                </svg>
              </div>

              <div class="p-6">
                <div class="text-[11px] font-bold text-[#0284C7] uppercase">{{ post.author_name }}</div>
                <h2 class="text-base font-extrabold text-slate-900 mt-2 line-clamp-2 leading-snug">
                  <Link :href="'/tin-tuc/' + post.slug" class="hover:text-[#0284C7] transition">
                    {{ post.title }}
                  </Link>
                </h2>
                <p class="text-xs text-slate-600 mt-2 line-clamp-3 leading-relaxed">
                  {{ post.summary }}
                </p>
              </div>
            </div>

            <div class="p-6 pt-0 border-t border-slate-100 flex items-center justify-between">
              <span class="text-[11px] text-slate-400">Chuyên sâu B2B</span>
              <Link :href="'/tin-tuc/' + post.slug" class="text-xs font-bold text-[#0284C7] hover:underline">
                Đọc bài viết →
              </Link>
            </div>
          </article>
        </div>

      </div>
    </div>
  </PublicLayout>
</template>
