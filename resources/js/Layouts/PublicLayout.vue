<script setup>
import { ref, computed } from "vue";
import { Link, usePage } from "@inertiajs/vue3";
import LeadFormModal from "../Components/LeadFormModal.vue";
import VpsComparisonModal from "../Components/VpsComparisonModal.vue";

const page = usePage();
const auth = computed(() => page.props.auth?.user);
const compliance = computed(() => page.props.compliance || {});
const settings = computed(() => page.props.settings || {});

const isMobileMenuOpen = ref(false);
const isLeadModalOpen = ref(false);
const modalInitialType = ref("consultation_quote");
const modalPrefilledProduct = ref(null);
const modalPrefilledService = ref("");

const isVpsModalOpen = ref(false);

const openLeadModal = (
    type = "consultation_quote",
    product = null,
    service = "",
) => {
    modalInitialType.value = type;
    modalPrefilledProduct.value = product;
    modalPrefilledService.value = service;
    isLeadModalOpen.value = true;
};

const handleSelectVpsPlan = (plan) => {
    isVpsModalOpen.value = false;
    openLeadModal(
        "consultation_quote",
        null,
        `Đăng ký gói ${plan.name} (vCPU: ${plan.vcpu}, RAM: ${plan.ram_gb}GB, NVMe: ${plan.storage_nvme_gb}GB)`,
    );
};

defineExpose({
    openLeadModal,
    openVpsModal: () => (isVpsModalOpen.value = true),
});

const navLinks = [
    { name: "Trang Chủ", href: "/", routeName: "public.home" },
    {
        name: "Cloud & Hạ Tầng",
        href: "/cloud-infrastructure",
        routeName: "public.cloud_infra",
    },
    {
        name: "Internet Doanh Nghiệp",
        href: "/internet-connectivity",
        routeName: "public.internet",
    },
    { name: "Smart IoT", href: "/smart-iot", routeName: "public.smart_iot" },
    { name: "Bluecom 4G", href: "/bluecom", routeName: "public.bluecom" },
    {
        name: "Dịch Vụ Kỹ Thuật",
        href: "/technical-services",
        routeName: "public.tech_services",
    },
    {
        name: "Đối Tác & Đại Lý",
        href: "/doi-tac-dai-ly",
        routeName: "public.partner",
    },
    { name: "Về Toàn Cầu", href: "/ve-toan-cau", routeName: "public.about" },
];

const currentPath = computed(() => page.url);
const isActive = (href) => {
    if (href === "/") return currentPath.value === "/";
    return currentPath.value.startsWith(href);
};
</script>

<template>
    <div
        class="min-h-screen flex flex-col bg-slate-50 text-slate-800 font-sans"
    >
        <!-- Top Utility Bar -->
        <div
            class="bg-[#0A1C33] text-slate-300 text-xs py-2 border-b border-slate-800"
        >
            <div
                class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-wrap items-center justify-between gap-2"
            >
                <div class="flex items-center gap-6">
                    <div class="flex items-center gap-2">
                        <span
                            class="inline-block w-2 h-2 rounded-full bg-emerald-400 animate-pulse"
                        ></span>
                        <span class="font-medium text-slate-200"
                            >SLA Hỗ trợ Kỹ thuật 24/7/365:</span
                        >
                        <a
                            :href="
                                'tel:' + (settings.hotline_raw || '19006868')
                            "
                            class="text-[#0284C7] font-bold hover:underline"
                        >
                            {{ settings.hotline || "1900 6868" }}
                        </a>
                    </div>
                    <div
                        class="hidden md:flex items-center gap-1.5 text-slate-400"
                    >
                        <svg
                            class="w-3.5 h-3.5"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke="currentColor"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"
                            />
                        </svg>
                        <span>{{
                            settings.email_lead || "lead@toancau.vn"
                        }}</span>
                    </div>
                </div>

                <div class="flex items-center gap-4">
                    <Link
                        href="/chinh-sach-bao-mat"
                        class="hover:text-cyan-300 transition text-[11px]"
                    >
                        Bảo vệ dữ liệu (NĐ 13/2023)
                    </Link>
                    <span class="text-slate-600">|</span>
                    <Link
                        v-if="auth"
                        href="/admin"
                        class="text-cyan-400 hover:text-cyan-300 font-semibold flex items-center gap-1"
                    >
                        <svg
                            class="w-3.5 h-3.5"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke="currentColor"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0zm6 2a9 9 0 11-18 0 9 9 0 0118 0z"
                            />
                        </svg>
                        <span>CMS ({{ auth.name }})</span>
                    </Link>
                    <Link
                        v-else
                        href="/login"
                        class="text-slate-300 hover:text-white transition"
                    >
                        Đăng nhập Quản Trị
                    </Link>
                </div>
            </div>
        </div>

        <!-- Main Navigation Header -->
        <header
            class="sticky top-0 z-40 bg-[#0F294A] border-b border-slate-700/80 shadow-lg"
        >
            <div class="max-w mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex items-center justify-between h-20">
                    <!-- Logo & Brand Identity -->
                    <Link href="/" class="flex items-center gap-3 group">
                        <div
                            class="bg-white px-2.5 py-1 rounded-xl shadow-md border border-white/20 group-hover:scale-105 transition flex items-center h-12"
                        >
                            <img src="/images/logo.png" alt="GlobalCorp Logo" class="h-9 w-auto object-contain" />
                        </div>
                    </Link>

                    <!-- Desktop 8 Menu Links -->
                    <nav class="hidden xl:flex items-center gap-1 2xl:gap-2">
                        <Link
                            v-for="item in navLinks"
                            :key="item.href"
                            :href="item.href"
                            :class="
                                isActive(item.href)
                                    ? 'text-white bg-[#0284C7]/20 border-b-2 border-[#0284C7]'
                                    : 'text-slate-300 hover:text-white hover:bg-white/5'
                            "
                            class="px-2.5 py-2 rounded-lg text-xs 2xl:text-sm font-semibold transition"
                        >
                            {{ item.name }}
                        </Link>
                    </nav>

                    <!-- Action CTA -->
                    <div class="hidden sm:flex items-center gap-3">
                        <button
                            type="button"
                            @click="openLeadModal('consultation_quote')"
                            class="px-4 py-2.5 rounded-xl bg-[#EA580C] hover:bg-[#C2410C] text-white text-xs font-bold shadow-md shadow-[#EA580C]/25 transition flex items-center gap-2 group"
                        >
                            <span>Yêu Cầu Tư Vấn</span>
                            <svg
                                class="w-4 h-4 group-hover:translate-x-0.5 transition"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke="currentColor"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M14 5l7 7m0 0l-7 7m7-7H3"
                                />
                            </svg>
                        </button>
                    </div>

                    <!-- Mobile Hamburger Toggle -->
                    <div class="flex xl:hidden">
                        <button
                            @click="isMobileMenuOpen = !isMobileMenuOpen"
                            type="button"
                            class="text-slate-300 hover:text-white p-2 rounded-lg focus:outline-none"
                        >
                            <svg
                                v-if="!isMobileMenuOpen"
                                class="h-6 w-6"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke="currentColor"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M4 6h16M4 12h16M4 18h16"
                                />
                            </svg>
                            <svg
                                v-else
                                class="h-6 w-6"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke="currentColor"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12"
                                />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Mobile Dropdown Navigation Drawer -->
            <div
                v-show="isMobileMenuOpen"
                class="xl:hidden bg-[#0A1C33] border-b border-slate-700 px-4 pt-2 pb-6 space-y-1"
            >
                <Link
                    v-for="item in navLinks"
                    :key="item.href"
                    :href="item.href"
                    @click="isMobileMenuOpen = false"
                    :class="
                        isActive(item.href)
                            ? 'text-white bg-[#0284C7]'
                            : 'text-slate-300 hover:bg-slate-800 hover:text-white'
                    "
                    class="block px-3 py-2.5 rounded-xl text-sm font-semibold"
                >
                    {{ item.name }}
                </Link>
                <div class="pt-4 border-t border-slate-800">
                    <button
                        type="button"
                        @click="
                            isMobileMenuOpen = false;
                            openLeadModal('consultation_quote');
                        "
                        class="w-full py-3 rounded-xl bg-[#EA580C] text-white text-sm font-bold text-center shadow-lg"
                    >
                        Yêu Cầu Báo Giá & Tư Vấn (SLA 30p)
                    </button>
                </div>
            </div>
        </header>

        <!-- Main Content Slot -->
        <main class="flex-1">
            <slot
                :open-lead-modal="openLeadModal"
                :open-vps-modal="() => (isVpsModalOpen = true)"
            />
        </main>

        <!-- Footer - High-Tech Modern IT Cybernetic Background -->
        <footer
            class="relative bg-[#061224] text-slate-300 pt-16 pb-12 border-t border-slate-800/80 overflow-hidden"
        >
            <!-- Layer 1: Ambient High-Tech Glow Orbs -->
            <div
                class="absolute -top-32 -left-32 w-96 h-96 bg-cyan-500/15 rounded-full blur-3xl pointer-events-none animate-pulse"
                style="animation-duration: 9s;"
            ></div>
            <div
                class="absolute -bottom-32 -right-32 w-96 h-96 bg-blue-600/15 rounded-full blur-3xl pointer-events-none"
            ></div>
            <div
                class="absolute top-1/2 left-1/3 -translate-y-1/2 w-80 h-80 bg-sky-500/10 rounded-full blur-[100px] pointer-events-none"
            ></div>
            <div
                class="absolute top-1/3 right-1/4 w-72 h-36 bg-orange-600/10 rounded-full blur-3xl pointer-events-none"
            ></div>

            <!-- Layer 2: Cybernetic Matrix Grid with Radial Mask -->
            <div
                class="absolute inset-0 bg-[linear-gradient(to_right,#0284c715_1px,transparent_1px),linear-gradient(to_bottom,#0284c715_1px,transparent_1px)] bg-[size:4rem_4rem] [mask-image:radial-gradient(ellipse_80%_60%_at_50%_35%,#000_65%,transparent_100%)] pointer-events-none"
            ></div>

            <!-- Layer 3: High-Tech Top Laser Horizon Line -->
            <div
                class="absolute top-0 left-0 right-0 h-px bg-gradient-to-r from-transparent via-cyan-400/50 to-transparent pointer-events-none"
            ></div>

            <!-- Layer 4: SVG Circuit Board Topology Vectors -->
            <svg
                class="absolute inset-0 w-full h-full pointer-events-none opacity-20"
                xmlns="http://www.w3.org/2000/svg"
            >
                <defs>
                    <linearGradient
                        id="footerCircuitGrad"
                        x1="0%"
                        y1="0%"
                        x2="100%"
                        y2="100%"
                    >
                        <stop
                            offset="0%"
                            stop-color="#0284C7"
                            stop-opacity="0.2"
                        />
                        <stop
                            offset="50%"
                            stop-color="#38BDF8"
                            stop-opacity="0.9"
                        />
                        <stop
                            offset="100%"
                            stop-color="#0284C7"
                            stop-opacity="0.1"
                        />
                    </linearGradient>
                </defs>
                <!-- Circuit trace Left -->
                <path
                    d="M 0 50 L 160 50 L 220 110 L 380 110"
                    fill="none"
                    stroke="url(#footerCircuitGrad)"
                    stroke-width="1.5"
                />
                <circle cx="160" cy="50" r="3" fill="#38BDF8" />
                <circle cx="220" cy="110" r="3" fill="#0284C7" />
                <circle cx="380" cy="110" r="4" fill="#38BDF8" />

                <!-- Circuit trace Right -->
                <path
                    d="M 1920 60 L 1740 60 L 1670 130 L 1510 130"
                    fill="none"
                    stroke="url(#footerCircuitGrad)"
                    stroke-width="1.5"
                />
                <circle cx="1740" cy="60" r="3" fill="#38BDF8" />
                <circle cx="1670" cy="130" r="3" fill="#0284C7" />
                <circle cx="1510" cy="130" r="4" fill="#38BDF8" />
            </svg>

            <!-- Main Content Container -->
            <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <!-- Top Footer Grid -->
                <div
                    class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-10 pb-12 border-b border-slate-800/80"
                >
                    <!-- Column 1: Company Profile -->
                    <div class="lg:col-span-2 space-y-4">
                        <div class="flex items-center gap-3">
                            <div
                                class="bg-white px-3 py-1 rounded-xl border border-white/20 flex items-center h-12 shadow-[0_0_20px_rgba(2,132,199,0.25)]"
                            >
                                <img src="/images/logo.png" alt="GlobalCorp Logo" class="h-9 w-auto object-contain" />
                            </div>
                        </div>

                        <!-- System Status Telemetry Pill -->
                        <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-slate-900/80 border border-cyan-500/30 text-cyan-300 text-[11px] font-mono">
                            <span class="w-2 h-2 rounded-full bg-emerald-400 animate-ping"></span>
                            <span>BACKBONE & DC: ONLINE 99.99%</span>
                        </div>

                        <p
                            class="text-xs text-slate-300 leading-relaxed max-w-sm"
                        >
                            Đơn vị tiên phong cung cấp giải pháp Hạ tầng Điện
                            toán Đám mây, Kênh truyền Internet Doanh nghiệp
                            Leased Line, Hệ sinh thái Smart IoT, Truyền thanh
                            thông minh 4G Bluecom và Dịch vụ Kỹ thuật viễn thông
                            toàn diện tại Việt Nam.
                        </p>

                        <div class="space-y-2 text-xs text-slate-300 pt-2">
                            <div class="flex items-start gap-2.5">
                                <span
                                    class="font-semibold text-slate-100 min-w-[70px]"
                                    >Mã số thuế:</span
                                >
                                <span>0318999888 do Sở KH&ĐT cấp</span>
                            </div>
                            <div class="flex items-start gap-2.5">
                                <span
                                    class="font-semibold text-slate-100 min-w-[70px]"
                                    >Trụ sở chính:</span
                                >
                                <span
                                    >Tầng 8, Tòa nhà Công Nghệ Số, 123 Nguyễn
                                    Thị Minh Khai, P. Bến Thành, Quận 1, TP. Hồ
                                    Chí Minh</span
                                >
                            </div>
                            <div class="flex items-start gap-2.5">
                                <span
                                    class="font-semibold text-slate-100 min-w-[70px]"
                                    >Hotline 24/7:</span
                                >
                                <a
                                    :href="
                                        'tel:' +
                                        (settings.hotline_raw || '19006868')
                                    "
                                    class="text-cyan-300 font-bold hover:text-cyan-200 hover:underline flex items-center gap-1.5"
                                >
                                    <span class="w-1.5 h-1.5 rounded-full bg-cyan-400"></span>
                                    {{ settings.hotline || "1900 6868" }}
                                </a>
                            </div>
                            <div class="flex items-start gap-2.5">
                                <span
                                    class="font-semibold text-slate-100 min-w-[70px]"
                                    >Email Lead:</span
                                >
                                <span>{{
                                    settings.email_lead || "lead@toancau.vn"
                                }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Column 2: Solutions 1 -->
                    <div>
                        <h4
                            class="text-xs font-bold text-white uppercase tracking-wider mb-4 flex items-center gap-2"
                        >
                            <span class="w-2 h-2 rounded-full bg-[#0284C7] shadow-[0_0_8px_#0284C7]"></span>
                            <span>Hạ Tầng Điện Toán</span>
                        </h4>
                        <ul class="space-y-2.5 text-xs text-slate-400">
                            <li>
                                <Link
                                    href="/cloud-infrastructure"
                                    class="hover:text-cyan-300 transition duration-150 inline-block"
                                    >Máy Chủ Dedicated EPYC</Link
                                >
                            </li>
                            <li>
                                <Link
                                    href="/cloud-infrastructure?tab=vps"
                                    class="hover:text-cyan-300 transition duration-150 inline-block"
                                    >Cloud VPS NVMe Gen4</Link
                                >
                            </li>
                            <li>
                                <Link
                                    href="/cloud-infrastructure?tab=ip"
                                    class="hover:text-cyan-300 transition duration-150 inline-block"
                                    >Cho Thuê IPv4 /24 Sạch</Link
                                >
                            </li>
                            <li>
                                <Link
                                    href="/cloud-infrastructure?tab=business"
                                    class="hover:text-cyan-300 transition duration-150 inline-block"
                                    >Private Cloud SME & SI</Link
                                >
                            </li>
                            <li>
                                <Link
                                    href="/internet-connectivity"
                                    class="hover:text-cyan-300 transition duration-150 inline-block"
                                    >Internet Leased Line SLA 99.9%</Link
                                >
                            </li>
                            <li>
                                <Link
                                    href="/internet-connectivity"
                                    class="hover:text-cyan-300 transition duration-150 inline-block"
                                    >Giải Pháp Vệ Tinh Starlink</Link
                                >
                            </li>
                        </ul>
                    </div>

                    <!-- Column 3: Solutions 2 -->
                    <div>
                        <h4
                            class="text-xs font-bold text-white uppercase tracking-wider mb-4 flex items-center gap-2"
                        >
                            <span class="w-2 h-2 rounded-full bg-cyan-400 shadow-[0_0_8px_#38BDF8]"></span>
                            <span>IoT & Thiết Bị Số</span>
                        </h4>
                        <ul class="space-y-2.5 text-xs text-slate-400">
                            <li>
                                <Link
                                    href="/bluecom"
                                    class="hover:text-cyan-300 transition duration-150 inline-block"
                                    >Truyền Thanh Bluecom 4G</Link
                                >
                            </li>
                            <li>
                                <Link
                                    href="/bluecom"
                                    class="hover:text-cyan-300 transition duration-150 inline-block"
                                    >Router Công Nghiệp Dual-SIM</Link
                                >
                            </li>
                            <li>
                                <Link
                                    href="/smart-iot"
                                    class="hover:text-cyan-300 transition duration-150 inline-block"
                                    >Solar Camera Năng Lượng Mặt Trời</Link
                                >
                            </li>
                            <li>
                                <Link
                                    href="/smart-iot"
                                    class="hover:text-cyan-300 transition duration-150 inline-block"
                                    >IoT Gateway Modbus/MQTT</Link
                                >
                            </li>
                            <li>
                                <Link
                                    href="/technical-services"
                                    class="hover:text-cyan-300 transition duration-150 inline-block"
                                    >Dịch Vụ Khảo Sát & Thi Công</Link
                                >
                            </li>
                            <li>
                                <Link
                                    href="/doi-tac-dai-ly"
                                    class="hover:text-cyan-300 transition duration-150 inline-block"
                                    >Chính Sách Đại Lý & Đối Tác</Link
                                >
                            </li>
                        </ul>
                    </div>

                    <!-- Column 4: Quick Contact Widget (Glassmorphism Card) -->
                    <div class="p-5 rounded-2xl bg-slate-900/60 backdrop-blur-xl border border-cyan-500/30 shadow-[0_8px_32px_rgba(2,132,199,0.15)] space-y-3.5">
                        <div class="flex items-center gap-2">
                            <span class="w-2 h-2 rounded-full bg-[#EA580C] animate-ping"></span>
                            <h4
                                class="text-xs font-bold text-white uppercase tracking-wider"
                            >
                                Đăng Ký Tư Vấn Nhanh
                            </h4>
                        </div>
                        <p class="text-xs text-slate-300 leading-relaxed">
                            Để lại thông tin để nhận phản hồi từ kỹ sư giải pháp
                            trong vòng 30 phút.
                        </p>
                        <button
                            type="button"
                            @click="openLeadModal('consultation_quote')"
                            class="w-full py-2.5 px-4 rounded-xl bg-[#EA580C] hover:bg-[#C2410C] text-white text-xs font-extrabold transition-all shadow-[0_4px_20px_rgba(234,88,12,0.4)] hover:shadow-[0_6px_25px_rgba(234,88,12,0.55)] flex items-center justify-center gap-1.5 transform hover:-translate-y-0.5"
                        >
                            <svg
                                class="w-4 h-4"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke="currentColor"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"
                                />
                            </svg>
                            <span>Mở Form Tư Vấn Trực Tuyến</span>
                        </button>
                        <div class="pt-1">
                            <Link
                                href="/chinh-sach-bao-mat"
                                class="text-[11px] text-cyan-300 hover:text-cyan-200 hover:underline block"
                            >
                                Tuân thủ Nghị định 13/2023/NĐ-CP về bảo vệ dữ
                                liệu cá nhân.
                            </Link>
                        </div>
                    </div>
                </div>

                <!-- Bottom Footer Copyright & Badges -->
                <div
                    class="pt-8 flex flex-col md:flex-row items-center justify-between gap-4 text-xs text-slate-400"
                >
                    <div>
                        © 2026 Toàn Cầu Technology. Mọi quyền được bảo lưu.
                    </div>
                    <div class="flex flex-wrap items-center gap-3 sm:gap-4 text-slate-300 text-xs">
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-slate-900/60 border border-slate-700/60 text-slate-300 text-[11px]">
                            <span class="w-1.5 h-1.5 rounded-full bg-cyan-400"></span>
                            Tier 3 Data Center
                        </span>
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-slate-900/60 border border-slate-700/60 text-slate-300 text-[11px]">
                            <span class="w-1.5 h-1.5 rounded-full bg-cyan-400"></span>
                            IPv4 / IPv6 APNIC Member
                        </span>
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-slate-900/60 border border-slate-700/60 text-slate-300 text-[11px]">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-400"></span>
                            SLA 99.9% Network
                        </span>
                        <Link href="/sitemap.xml" class="text-cyan-400 hover:text-cyan-300 hover:underline ml-1"
                            >Sitemap XML</Link
                        >
                    </div>
                </div>
            </div>
        </footer>

        <!-- Fixed Floating Widget (Hotline & Chat Zalo OA) -->
        <div class="fixed bottom-6 right-6 z-40 flex flex-col gap-3 items-end">
            <!-- Zalo OA Chat Button -->
            <a
                :href="settings.zalo_oa_url || 'https://zalo.me/toancautech'"
                target="_blank"
                rel="noopener noreferrer"
                class="group flex items-center gap-2 bg-[#0284C7] hover:bg-[#0369A1] text-white p-3 sm:px-4 sm:py-2.5 rounded-full shadow-xl shadow-cyan-600/30 transition transform hover:-translate-y-0.5"
            >
                <div
                    class="w-6 h-6 rounded-full bg-white text-[#0284C7] font-black flex items-center justify-center text-xs"
                >
                    Z
                </div>
                <span class="hidden sm:inline text-xs font-bold tracking-tight"
                    >Chat Zalo OA</span
                >
            </a>

            <!-- Hotline Direct Dial -->
            <a
                :href="'tel:' + (settings.hotline_raw || '19006868')"
                class="group flex items-center gap-2 bg-[#EA580C] hover:bg-[#C2410C] text-white p-3 sm:px-4 sm:py-2.5 rounded-full shadow-xl shadow-orange-600/30 transition transform hover:-translate-y-0.5 animate-bounce"
            >
                <svg
                    class="w-5 h-5"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke="currentColor"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"
                    />
                </svg>
                <span
                    class="hidden sm:inline text-xs font-bold tracking-tight"
                    >{{ settings.hotline || "1900 6868" }}</span
                >
            </a>
        </div>

        <!-- Modals -->
        <LeadFormModal
            :show="isLeadModalOpen"
            :initial-type="modalInitialType"
            :prefilled-product="modalPrefilledProduct"
            :prefilled-service="modalPrefilledService"
            @close="isLeadModalOpen = false"
        />

        <VpsComparisonModal
            :show="isVpsModalOpen"
            :plans="page.props.vpsPlans || []"
            @close="isVpsModalOpen = false"
            @select-plan="handleSelectVpsPlan"
        />
    </div>
</template>
