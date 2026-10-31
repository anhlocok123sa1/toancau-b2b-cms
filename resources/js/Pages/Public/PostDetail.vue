<script setup>
import { Head, Link } from '@inertiajs/vue3';
import PublicLayout from '../../Layouts/PublicLayout.vue';

const props = defineProps({
  post: {
    type: Object,
    required: true,
  },
  relatedPosts: {
    type: Array,
    default: () => [],
  },
});
</script>

<template>
  <PublicLayout>
    <Head>
      <title>{{ post.title }} - Toàn Cầu Tech</title>
      <meta name="description" :content="post.summary" />
    </Head>

    <div class="py-12 bg-slate-50 min-h-screen">
      <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <nav class="flex items-center text-xs text-slate-500 mb-6 gap-2">
          <Link href="/" class="hover:text-[#0284C7]">Trang chủ</Link>
          <span>/</span>
          <Link href="/tin-tuc" class="hover:text-[#0284C7]">Tin tức</Link>
          <span>/</span>
          <span class="text-slate-800 font-semibold truncate">{{ post.title }}</span>
        </nav>

        <article class="bg-white rounded-3xl border border-slate-200 p-6 sm:p-10 shadow-sm space-y-6">
          <div>
            <div class="flex items-center gap-3 text-xs text-slate-500 mb-3">
              <span class="font-bold text-[#0284C7] bg-cyan-50 px-2.5 py-1 rounded">{{ post.author_name }}</span>
              <span>Chuyên mục Hạ tầng B2B</span>
            </div>
            <h1 class="text-2xl sm:text-4xl font-extrabold text-[#0F294A] tracking-tight leading-tight">
              {{ post.title }}
            </h1>
          </div>

          <div class="p-4 rounded-2xl bg-slate-50 border-l-4 border-[#0284C7] text-xs sm:text-sm text-slate-700 italic">
            {{ post.summary }}
          </div>

          <div class="prose prose-slate max-w-none text-xs sm:text-sm leading-relaxed text-slate-800 space-y-4" v-html="post.content">
          </div>
        </article>

        <!-- Related Posts -->
        <div v-if="relatedPosts.length > 0" class="mt-12">
          <h3 class="text-xl font-bold text-[#0F294A] mb-6">Bài Viết Liên Quan</h3>
          <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
            <div v-for="rel in relatedPosts" :key="rel.id" class="bg-white rounded-2xl border border-slate-200 p-4">
              <h4 class="font-bold text-xs text-slate-900 line-clamp-2">
                <Link :href="'/tin-tuc/' + rel.slug" class="hover:text-[#0284C7]">
                  {{ rel.title }}
                </Link>
              </h4>
            </div>
          </div>
        </div>

      </div>
    </div>
  </PublicLayout>
</template>
