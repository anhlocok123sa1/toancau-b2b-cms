<script setup>
import { Head, useForm, Link } from '@inertiajs/vue3';

const form = useForm({
  email: 'admin@toancau.vn',
  password: 'Password@123',
  remember: true,
});

const quickLoginAs = (email) => {
  form.email = email;
  form.password = 'Password@123';
  form.post('/login');
};

const submit = () => {
  form.post('/login');
};
</script>

<template>
  <div class="min-h-screen flex items-center justify-center bg-slate-900 py-12 px-4 sm:px-6 lg:px-8 font-sans">
    <Head>
      <title>Đăng Nhập Quản Trị Hệ Thống - Toàn Cầu Technology</title>
    </Head>

    <div class="max-w-md w-full space-y-8 bg-white p-8 sm:p-10 rounded-3xl shadow-2xl border border-slate-700/50">
      
      <!-- Logo & Title -->
      <div class="text-center">
        <Link href="/" class="inline-flex items-center justify-center gap-2 mb-3">
          <div class="bg-white p-2 rounded-2xl shadow-md border border-slate-200">
            <img src="/images/logo.png" alt="GlobalCorp Logo" class="h-10 w-auto object-contain mx-auto" />
          </div>
        </Link>
        <h2 class="text-2xl font-black text-[#0F294A] tracking-tight">
          Toàn Cầu Admin Portal
        </h2>
        <p class="text-xs text-slate-500 mt-1">
          Hệ thống Quản trị Nội dung CMS & Cổng Tiếp Nhận Lead B2B
        </p>
      </div>

      <!-- Quick Role Selection for Pair-Programming & Testing -->
      <div class="bg-slate-50 border border-slate-200 rounded-2xl p-4">
        <div class="text-[11px] font-bold uppercase tracking-wider text-slate-500 mb-2">
          Đăng nhập nhanh theo 5 vai trò (RBAC):
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 text-xs">
          <button 
            type="button" 
            @click="quickLoginAs('admin@toancau.vn')"
            class="py-2 px-2.5 rounded-xl bg-[#0F294A] hover:bg-[#183F70] text-white font-bold text-left transition flex items-center justify-between"
          >
            <span>[1] Admin</span>
            <span class="text-[10px] text-cyan-300 font-normal">Toàn quyền</span>
          </button>
          <button 
            type="button" 
            @click="quickLoginAs('content@toancau.vn')"
            class="py-2 px-2.5 rounded-xl bg-purple-700 hover:bg-purple-800 text-white font-bold text-left transition flex items-center justify-between"
          >
            <span>[2] Content Admin</span>
            <span class="text-[10px] text-purple-200 font-normal">Chặn Lead</span>
          </button>
          <button 
            type="button" 
            @click="quickLoginAs('sales@toancau.vn')"
            class="py-2 px-2.5 rounded-xl bg-[#EA580C] hover:bg-[#C2410C] text-white font-bold text-left transition flex items-center justify-between"
          >
            <span>[3] Sales</span>
            <span class="text-[10px] text-orange-200 font-normal">Xử lý Lead</span>
          </button>
          <button 
            type="button" 
            @click="quickLoginAs('partner@toancau.vn')"
            class="py-2 px-2.5 rounded-xl bg-cyan-700 hover:bg-cyan-800 text-white font-bold text-left transition flex items-center justify-between"
          >
            <span>[4] Partner Mgr</span>
            <span class="text-[10px] text-cyan-200 font-normal">Đại lý</span>
          </button>
          <button 
            type="button" 
            @click="quickLoginAs('viewer@toancau.vn')"
            class="sm:col-span-2 py-2 px-2.5 rounded-xl bg-slate-700 hover:bg-slate-800 text-white font-bold text-left transition flex items-center justify-between"
          >
            <span>[5] Viewer</span>
            <span class="text-[10px] text-slate-300 font-normal">Chỉ xem (Read-Only)</span>
          </button>
        </div>
      </div>

      <!-- Login Form -->
      <form class="space-y-4" @submit.prevent="submit">
        <div>
          <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wide mb-1">
            Email tài khoản
          </label>
          <input 
            v-model="form.email" 
            type="email" 
            required 
            class="w-full rounded-xl border border-slate-300 px-3.5 py-2.5 text-sm text-slate-900 focus:border-[#0284C7] focus:ring-2 focus:ring-[#0284C7]/20 outline-none"
          />
          <div v-if="form.errors.email" class="text-xs text-rose-500 mt-1">{{ form.errors.email }}</div>
        </div>

        <div>
          <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wide mb-1">
            Mật khẩu
          </label>
          <input 
            v-model="form.password" 
            type="password" 
            required 
            class="w-full rounded-xl border border-slate-300 px-3.5 py-2.5 text-sm text-slate-900 focus:border-[#0284C7] focus:ring-2 focus:ring-[#0284C7]/20 outline-none"
          />
        </div>

        <div class="flex items-center justify-between text-xs">
          <label class="flex items-center gap-2 cursor-pointer">
            <input v-model="form.remember" type="checkbox" class="rounded text-[#0284C7] focus:ring-[#0284C7]" />
            <span class="text-slate-600">Ghi nhớ đăng nhập</span>
          </label>
          <Link href="/" class="text-[#0284C7] font-semibold hover:underline">
            ← Về Website
          </Link>
        </div>

        <button 
          type="submit" 
          :disabled="form.processing"
          class="w-full py-3 px-4 rounded-xl bg-[#0F294A] hover:bg-[#183F70] text-white text-sm font-extrabold shadow-lg transition disabled:opacity-50"
        >
          {{ form.processing ? 'Đang xác thực...' : 'Đăng Nhập Quản Trị' }}
        </button>
      </form>

    </div>
  </div>
</template>
