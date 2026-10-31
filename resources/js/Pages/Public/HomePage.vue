<script setup>
import { ref } from "vue";
import { Head, Link } from "@inertiajs/vue3";
import PublicLayout from "../../Layouts/PublicLayout.vue";

const props = defineProps({
    sectors: {
        type: Array,
        default: () => [],
    },
    featuredProducts: {
        type: Array,
        default: () => [],
    },
    infraStats: {
        type: Object,
        default: () => ({}),
    },
    featuredProjects: {
        type: Array,
        default: () => [],
    },
    latestNews: {
        type: Array,
        default: () => [],
    },
    homeSections: {
        type: Object,
        default: () => ({}),
    },
});

const layoutRef = ref(null);

const triggerLead = (
    type = "consultation_quote",
    product = null,
    service = "",
) => {
    if (layoutRef.value) {
        layoutRef.value.openLeadModal(type, product, service);
    }
};

const formatPrice = (price) => {
    if (!price) return "Liên hệ";
    return new Intl.NumberFormat("vi-VN", {
        style: "currency",
        currency: "VND",
    }).format(price);
};
</script>

<template>
    <PublicLayout ref="layoutRef">
        <Head>
            <title>
                Toàn Cầu Technology - Hạ Tầng Số, Kết Nối Thông Minh, Giải Pháp
                Công Nghệ
            </title>
            <meta
                name="description"
                content="Toàn Cầu Technology cung cấp giải pháp Dedicated Server AMD EPYC, Cloud VPS NVMe, Cho thuê IPv4 sạch /24, Internet Leased Line SLA 99.9%, Smart IoT Solar Camera và Truyền thanh thông minh Bluecom 4G."
            />
        </Head>

        <!-- 1. HERO BANNER - HIGH TECH CYBER INFRASTRUCTURE BACKGROUND -->
        <section
            class="relative bg-[#061224] text-white pt-20 pb-28 overflow-hidden"
        >
            <!-- Layer 1: Ambient High-Tech Glow Orbs -->
            <div
                class="absolute -top-32 -left-32 w-96 h-96 bg-cyan-500/20 rounded-full blur-3xl pointer-events-none animate-pulse"
                style="animation-duration: 8s"
            ></div>
            <div
                class="absolute -top-32 -right-32 w-96 h-96 bg-blue-600/20 rounded-full blur-3xl pointer-events-none"
            ></div>
            <div
                class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[700px] h-[350px] bg-sky-500/10 rounded-full blur-[110px] pointer-events-none"
            ></div>
            <div
                class="absolute bottom-10 left-1/2 -translate-x-1/2 w-80 h-24 bg-orange-600/10 rounded-full blur-3xl pointer-events-none"
            ></div>

            <!-- Layer 2: Cybernetic Matrix Grid with Radial Mask -->
            <div
                class="absolute inset-0 bg-[linear-gradient(to_right,#0284c715_1px,transparent_1px),linear-gradient(to_bottom,#0284c715_1px,transparent_1px)] bg-[size:4rem_4rem] [mask-image:radial-gradient(ellipse_70%_60%_at_50%_35%,#000_65%,transparent_100%)] pointer-events-none"
            ></div>

            <!-- Layer 3: High-Tech Circuit Topology & Fiber Optic Vectors -->
            <svg
                class="absolute inset-0 w-full h-full pointer-events-none opacity-20"
                xmlns="http://www.w3.org/2000/svg"
            >
                <defs>
                    <linearGradient
                        id="cyberCircuitGrad"
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
                <!-- Circuit paths Left -->
                <path
                    d="M 0 100 L 200 100 L 260 160 L 400 160"
                    fill="none"
                    stroke="url(#cyberCircuitGrad)"
                    stroke-width="1.5"
                />
                <circle cx="200" cy="100" r="3" fill="#38BDF8" />
                <circle cx="260" cy="160" r="4" fill="#0284C7" />
                <circle cx="400" cy="160" r="3" fill="#38BDF8" />

                <path
                    d="M 0 280 L 150 280 L 210 220 L 350 220 L 420 290"
                    fill="none"
                    stroke="url(#cyberCircuitGrad)"
                    stroke-width="1.5"
                />
                <circle cx="150" cy="280" r="3" fill="#38BDF8" />
                <circle cx="210" cy="220" r="3" fill="#38BDF8" />
                <circle cx="420" cy="290" r="4" fill="#0284C7" />

                <!-- Circuit paths Right -->
                <path
                    d="M 1920 120 L 1700 120 L 1630 190 L 1480 190"
                    fill="none"
                    stroke="url(#cyberCircuitGrad)"
                    stroke-width="1.5"
                />
                <circle cx="1700" cy="120" r="3" fill="#38BDF8" />
                <circle cx="1630" cy="190" r="4" fill="#0284C7" />
                <circle cx="1480" cy="190" r="3" fill="#38BDF8" />

                <path
                    d="M 1920 310 L 1750 310 L 1680 240 L 1520 240 L 1450 310"
                    fill="none"
                    stroke="url(#cyberCircuitGrad)"
                    stroke-width="1.5"
                />
                <circle cx="1750" cy="310" r="3" fill="#38BDF8" />
                <circle cx="1680" cy="240" r="3" fill="#38BDF8" />
                <circle cx="1450" cy="310" r="4" fill="#0284C7" />
            </svg>

            <!-- Layer 4: Fiber Optic Laser Horizon Line -->
            <div
                class="absolute top-1/2 left-0 right-0 h-px bg-gradient-to-r from-transparent via-cyan-400/30 to-transparent pointer-events-none"
            ></div>

            <!-- Layer 5: Left Floating Desktop HUD Telemetry Card -->
            <div
                class="hidden xl:block absolute left-8 2xl:left-14 top-28 w-64 p-4 rounded-2xl bg-slate-900/60 backdrop-blur-xl border border-cyan-500/30 shadow-[0_8px_32px_rgba(2,132,199,0.18)] text-left pointer-events-none transition transform hover:scale-105 duration-300"
            >
                <div
                    class="flex items-center justify-between border-b border-cyan-500/20 pb-2 mb-2.5"
                >
                    <div class="flex items-center gap-2">
                        <span
                            class="w-2 h-2 rounded-full bg-emerald-400 animate-ping"
                        ></span>
                        <span
                            class="text-[10px] font-mono font-bold tracking-wider text-emerald-400 uppercase"
                            >SYS_CORE: ACTIVE</span
                        >
                    </div>
                    <span class="text-[10px] font-mono text-slate-400"
                        >TIER 3+ DC</span
                    >
                </div>
                <div class="space-y-1.5 text-[11px] font-mono text-slate-300">
                    <div class="flex justify-between">
                        <span class="text-slate-400">Compute:</span>
                        <span class="text-cyan-300 font-semibold"
                            >AMD EPYC™ 7763</span
                        >
                    </div>
                    <div class="flex justify-between">
                        <span class="text-slate-400">Storage:</span>
                        <span class="text-slate-200">NVMe Enterprise Ceph</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-slate-400">Uptime SLA:</span>
                        <span class="text-emerald-400 font-semibold"
                            >99.99%</span
                        >
                    </div>
                </div>
                <!-- <div class="mt-3 pt-2 border-t border-cyan-500/20 flex items-center justify-between text-[10px] text-cyan-300">
          <span>● Viettel IDC / VNPT</span>
          <span class="bg-cyan-500/20 px-1.5 py-0.5 rounded text-[9px]">SLA 15m</span>
        </div> -->
            </div>

            <!-- Layer 6: Right Floating Desktop HUD Telemetry Card -->
            <div
                class="hidden xl:block absolute right-8 2xl:right-14 top-28 w-64 p-4 rounded-2xl bg-slate-900/60 backdrop-blur-xl border border-sky-500/30 shadow-[0_8px_32px_rgba(56,189,248,0.18)] text-left pointer-events-none transition transform hover:scale-105 duration-300"
            >
                <div
                    class="flex items-center justify-between border-b border-sky-500/20 pb-2 mb-2.5"
                >
                    <div class="flex items-center gap-2">
                        <span
                            class="w-2 h-2 rounded-full bg-cyan-400 animate-pulse"
                        ></span>
                        <span
                            class="text-[10px] font-mono font-bold tracking-wider text-cyan-400 uppercase"
                            >BACKBONE: 10Gbps</span
                        >
                    </div>
                    <span class="text-[10px] font-mono text-slate-400"
                        >MULTI-WAN</span
                    >
                </div>
                <div class="space-y-1.5 text-[11px] font-mono text-slate-300">
                    <div class="flex justify-between">
                        <span class="text-slate-400">Tài nguyên IP:</span>
                        <span class="text-sky-300 font-semibold"
                            >Clean IPv4 /24</span
                        >
                    </div>
                    <div class="flex justify-between">
                        <span class="text-slate-400">Độ trễ trong nước:</span>
                        <span class="text-emerald-400 font-semibold"
                            >&lt; 5ms</span
                        >
                    </div>
                    <div class="flex justify-between">
                        <span class="text-slate-400">Smart IoT / 4G:</span>
                        <span class="text-slate-200">Solar + Bluecom</span>
                    </div>
                </div>
                <!-- <div class="mt-3 pt-2 border-t border-sky-500/20 flex items-center justify-between text-[10px] text-sky-300">
          <span>● APNIC Direct Pool</span>
          <span class="bg-sky-500/20 px-1.5 py-0.5 rounded text-[9px]">Anti-DDoS</span>
        </div> -->
            </div>

            <!-- Central Banner Content -->
            <div
                class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center z-10"
            >
                <!-- Tagline Pill -->
                <div
                    class="inline-flex items-center gap-2.5 px-4 py-1.5 rounded-full bg-slate-900/80 border border-cyan-400/40 text-cyan-300 text-xs font-semibold shadow-[0_0_20px_rgba(2,132,199,0.25)] mb-6 backdrop-blur-md"
                >
                    <span class="relative flex h-2 w-2">
                        <span
                            class="animate-ping absolute inline-flex h-full w-full rounded-full bg-cyan-400 opacity-75"
                        ></span>
                        <span
                            class="relative inline-flex rounded-full h-2 w-2 bg-[#0284C7]"
                        ></span>
                    </span>
                    <span class="font-mono tracking-wider">{{
                        (homeSections?.hero || homeSections?.hero_banner)
                            ?.badge ||
                        "HẠ TẦNG SỐ – KẾT NỐI THÔNG MINH – GIẢI PHÁP CÔNG NGHỆ"
                    }}</span>
                </div>

                <!-- H1 Hero Heading -->
                <h1
                    class="text-3xl sm:text-5xl lg:text-6xl font-black tracking-tight max-w-4xl mx-auto leading-tight"
                >
                    <span
                        v-if="
                            (homeSections?.hero || homeSections?.hero_banner)
                                ?.title
                        "
                    >
                        {{
                            (homeSections?.hero || homeSections?.hero_banner)
                                .title
                        }}
                    </span>
                    <span v-else>
                        KẾT NỐI HẠ TẦNG – <br class="hidden sm:inline" />
                        <span
                            class="bg-gradient-to-r from-cyan-300 via-sky-200 to-teal-200 bg-clip-text text-transparent drop-shadow-[0_0_30px_rgba(56,189,248,0.4)]"
                        >
                            KIẾN TẠO GIẢI PHÁP SỐ
                        </span>
                    </span>
                </h1>

                <!-- Subtitle -->
                <p
                    class="mt-6 text-base sm:text-lg text-slate-300 max-w-3xl mx-auto font-normal leading-relaxed"
                >
                    {{
                        (homeSections?.hero || homeSections?.hero_banner)
                            ?.content ||
                        "Cung cấp hệ sinh thái toàn diện từ Máy chủ chuyên dụng, Cloud VPS NVMe, Tài nguyên IP uy tín, Kênh truyền Internet Leased Line SLA 99.9% đến Giải pháp Smart IoT và Thiết bị truyền thanh 4G Bluecom cho Doanh nghiệp, Đơn vị hành chính và Đối tác công nghệ toàn quốc."
                    }}
                </p>

                <!-- 3 Contrast CTA Buttons -->
                <div
                    class="mt-10 flex flex-wrap items-center justify-center gap-4"
                >
                    <!-- Button 1: Khám phá giải pháp -->
                    <a
                        href="#core-sectors"
                        class="px-7 py-3.5 rounded-xl bg-white text-[#0F294A] hover:bg-cyan-50 font-extrabold text-sm transition-all shadow-[0_4px_20px_rgba(255,255,255,0.2)] hover:shadow-[0_6px_25px_rgba(255,255,255,0.3)] flex items-center gap-2 transform hover:-translate-y-0.5"
                    >
                        <span>Khám Phá Giải Pháp</span>
                        <svg
                            class="w-4 h-4 text-[#0284C7]"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke="currentColor"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2.5"
                                d="M19 9l-7 7-7-7"
                            />
                        </svg>
                    </a>

                    <!-- Button 2: Liên hệ tư vấn (CTA Color #EA580C) -->
                    <button
                        type="button"
                        @click="triggerLead('consultation_quote')"
                        class="px-7 py-3.5 rounded-xl bg-[#EA580C] hover:bg-[#C2410C] text-white font-extrabold text-sm transition-all shadow-[0_4px_25px_rgba(234,88,12,0.45)] hover:shadow-[0_6px_30px_rgba(234,88,12,0.6)] flex items-center gap-2 transform hover:-translate-y-0.5"
                    >
                        <span>Liên Hệ Tư Vấn</span>
                        <span
                            class="text-xs bg-black/25 px-2 py-0.5 rounded-full font-medium"
                            >SLA 15p</span
                        >
                    </button>

                    <!-- Button 3: Hợp tác kinh doanh -->
                    <Link
                        href="/doi-tac-dai-ly"
                        class="px-7 py-3.5 rounded-xl bg-slate-900/60 hover:bg-cyan-950/80 text-cyan-300 hover:text-white border border-cyan-400/40 hover:border-cyan-300 font-bold text-sm transition-all backdrop-blur-md shadow-[0_4px_20px_rgba(2,132,199,0.15)] flex items-center gap-2 transform hover:-translate-y-0.5"
                    >
                        <span>Hợp Tác Kinh Doanh</span>
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
                                d="M14 5l7 7m0 0l-7 7m7-7H3"
                            />
                        </svg>
                    </Link>
                </div>

                <!-- Trust Badges Glass Container -->
                <div class="mt-14 pt-8">
                    <div
                        class="inline-flex flex-wrap items-center justify-center gap-8 sm:gap-14 px-8 py-4 rounded-2xl bg-slate-900/40 backdrop-blur-md border border-slate-700/60 shadow-[0_4px_20px_rgba(0,0,0,0.3)] text-xs font-semibold text-slate-300"
                    >
                        <div class="flex items-center gap-2">
                            <svg
                                class="w-5 h-5 text-cyan-400"
                                fill="currentColor"
                                viewBox="0 0 20 20"
                            >
                                <path
                                    fill-rule="evenodd"
                                    d="M2.166 4.999A11.954 11.954 0 0010 1.944 11.954 11.954 0 0017.834 5c.11.65.166 1.32.166 2.001 0 5.225-3.34 9.67-8 11.317C5.34 16.67 2 12.225 2 7c0-.682.057-1.35.166-2.001zm11.541 3.708a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                    clip-rule="evenodd"
                                />
                            </svg>
                            <span>Cam Kết SLA 99.9% Uptime</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <svg
                                class="w-5 h-5 text-cyan-400"
                                fill="currentColor"
                                viewBox="0 0 20 20"
                            >
                                <path
                                    fill-rule="evenodd"
                                    d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z"
                                    clip-rule="evenodd"
                                />
                            </svg>
                            <span>Data Center Tier 3 Quốc Tế</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <svg
                                class="w-5 h-5 text-cyan-400"
                                fill="currentColor"
                                viewBox="0 0 20 20"
                            >
                                <path
                                    fill-rule="evenodd"
                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z"
                                    clip-rule="evenodd"
                                />
                            </svg>
                            <span>Phản Hồi Sự Cố Kỹ Thuật 15-30 Phút</span>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- 2. SIX CORE SECTOR CARDS -->
        <section id="core-sectors" class="py-20 bg-slate-50">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center max-w-3xl mx-auto mb-14">
                    <span
                        class="text-xs font-bold text-[#0284C7] uppercase tracking-wider bg-cyan-100/60 px-3 py-1 rounded-full"
                    >
                        {{
                            homeSections?.core_sectors?.badge ||
                            "Hệ Sinh Thái 6 Trụ Cột"
                        }}
                    </span>
                    <h2
                        class="text-3xl font-extrabold text-[#0F294A] mt-3 tracking-tight"
                    >
                        {{
                            homeSections?.core_sectors?.title ||
                            "Năng Lực Công Nghệ Cốt Lõi Của Toàn Cầu"
                        }}
                    </h2>
                    <p class="text-slate-600 text-sm mt-2 leading-relaxed">
                        {{
                            homeSections?.core_sectors?.subtitle ||
                            "Hạ tầng viễn thông và số hóa toàn diện được xây dựng theo tiêu chuẩn công nghiệp khắt khe."
                        }}
                    </p>
                </div>

                <div
                    class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8"
                >
                    <div
                        v-for="(sector, idx) in sectors"
                        :key="idx"
                        class="bg-white rounded-2xl p-7 border border-slate-200/80 shadow-sm hover:shadow-xl transition-all duration-300 flex flex-col justify-between group hover:-translate-y-1"
                    >
                        <div>
                            <div class="flex items-center justify-between mb-4">
                                <span
                                    class="text-[11px] font-bold text-[#0284C7] bg-cyan-50 px-2.5 py-1 rounded-lg border border-cyan-100"
                                >
                                    {{ sector.badge }}
                                </span>
                                <span
                                    class="text-slate-300 font-mono text-xs font-bold"
                                    >0{{ idx + 1 }}</span
                                >
                            </div>
                            <h3
                                class="text-xl font-bold text-slate-900 group-hover:text-[#0284C7] transition"
                            >
                                {{ sector.title }}
                            </h3>
                            <p
                                class="text-slate-600 text-xs mt-3 leading-relaxed"
                            >
                                {{ sector.desc }}
                            </p>
                        </div>

                        <div
                            class="mt-6 pt-4 border-t border-slate-100 flex items-center justify-between"
                        >
                            <Link
                                :href="sector.href"
                                class="text-xs font-bold text-[#0F294A] hover:text-[#0284C7] transition flex items-center gap-1.5"
                            >
                                <span>Xem chi tiết giải pháp</span>
                                <svg
                                    class="w-3.5 h-3.5 group-hover:translate-x-1 transition"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                    stroke="currentColor"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M9 5l7 7-7 7"
                                    />
                                </svg>
                            </Link>
                            <button
                                type="button"
                                @click="
                                    triggerLead(
                                        'consultation_quote',
                                        null,
                                        sector.title,
                                    )
                                "
                                class="text-xs font-semibold text-[#EA580C] hover:underline"
                            >
                                Nhận Báo Giá
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- 3. INFRASTRUCTURE & CAPABILITIES BLOCK (CMS Toggle Supported) -->
        <section
            v-if="infraStats.visible"
            class="py-16 bg-[#0F294A] text-white relative overflow-hidden"
        >
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div
                    class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-center"
                >
                    <div class="lg:col-span-5 space-y-4">
                        <span
                            class="text-xs font-bold uppercase tracking-wider text-cyan-400 bg-cyan-400/10 px-3 py-1 rounded-full"
                        >
                            Hạ Tầng & Năng Lực Triển Khai
                        </span>
                        <h2
                            class="text-2xl sm:text-3xl font-extrabold tracking-tight leading-snug"
                        >
                            Nền Tảng Vững Chắc Cho Mọi Quy Mô Chuyển Đổi Số
                        </h2>
                        <p
                            class="text-xs sm:text-sm text-slate-300 leading-relaxed"
                        >
                            Chúng tôi sở hữu tài nguyên độc quyền, mạng truyền
                            dẫn đa hướng và trung tâm dữ liệu chuẩn quốc tế sẵn
                            sàng đáp ứng yêu cầu khắt khe nhất của khối tài
                            chính, ngân hàng và cơ quan chính phủ.
                        </p>
                        <div class="pt-2">
                            <button
                                type="button"
                                @click="triggerLead('technical_service')"
                                class="px-5 py-2.5 rounded-xl bg-cyan-600 hover:bg-cyan-500 text-white text-xs font-bold transition shadow"
                            >
                                Yêu Cầu Khảo Sát Kỹ Thuật Hiện Trường
                            </button>
                        </div>
                    </div>

                    <div
                        class="lg:col-span-7 grid grid-cols-1 sm:grid-cols-2 gap-4"
                    >
                        <div
                            class="bg-white/5 border border-white/10 rounded-2xl p-5 backdrop-blur-sm"
                        >
                            <div class="text-2xl font-black text-cyan-400">
                                100+ Cụm Rack
                            </div>
                            <div class="font-bold text-sm text-white mt-1">
                                Hạ Tầng Server Tier 3
                            </div>
                            <p class="text-xs text-slate-300 mt-1">
                                Trang bị chip AMD EPYC & Intel Xeon Scalable tại
                                VNPT IDC & Viettel IDC.
                            </p>
                        </div>

                        <div
                            class="bg-white/5 border border-white/10 rounded-2xl p-5 backdrop-blur-sm"
                        >
                            <div class="text-2xl font-black text-amber-400">
                                {{ infraStats.ip_resource }}
                            </div>
                            <div class="font-bold text-sm text-white mt-1">
                                Tài Nguyên IP Độc Quyền
                            </div>
                            <p class="text-xs text-slate-300 mt-1">
                                Cấp phát chính ngạch APNIC, 100% IP sạch uy tín
                                hỗ trợ LOA & BGP.
                            </p>
                        </div>

                        <div
                            class="bg-white/5 border border-white/10 rounded-2xl p-5 backdrop-blur-sm"
                        >
                            <div class="text-2xl font-black text-emerald-400">
                                99.9% Uptime
                            </div>
                            <div class="font-bold text-sm text-white mt-1">
                                Cam Kết SLA Bằng Hợp Đồng
                            </div>
                            <p class="text-xs text-slate-300 mt-1">
                                Đường truyền kênh Leased Line đối xứng không
                                nghẽn giờ cao điểm.
                            </p>
                        </div>

                        <div
                            class="bg-white/5 border border-white/10 rounded-2xl p-5 backdrop-blur-sm"
                        >
                            <div class="text-2xl font-black text-orange-400">
                                15 - 30 Phút
                            </div>
                            <div class="font-bold text-sm text-white mt-1">
                                SLA Phản Hồi Khẩn Cấp
                            </div>
                            <p class="text-xs text-slate-300 mt-1">
                                Đội cơ động kỹ thuật túc trực tại Hà Nội, Đà
                                Nẵng, TP.HCM & Cần Thơ.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- 4. FEATURED PRODUCTS SHOWCASE (MAX 8 TAGGED FEATURED) -->
        <section class="py-20 bg-white">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div
                    class="flex flex-col sm:flex-row sm:items-end justify-between gap-4 mb-12"
                >
                    <div>
                        <span
                            class="text-xs font-bold text-[#EA580C] uppercase tracking-wider bg-orange-50 px-3 py-1 rounded-full border border-orange-100"
                        >
                            Showcase Thiết Bị & Dịch Vụ
                        </span>
                        <h2
                            class="text-3xl font-extrabold text-[#0F294A] mt-3 tracking-tight"
                        >
                            Sản Phẩm & Giải Pháp Tiêu Biểu
                        </h2>
                        <p class="text-slate-600 text-xs sm:text-sm mt-1">
                            Các thiết bị và gói hạ tầng được tin dùng trong các
                            dự án công nghệ trọng điểm.
                        </p>
                    </div>
                    <Link
                        href="/san-pham"
                        class="text-xs font-bold text-[#0284C7] hover:underline flex items-center gap-1"
                    >
                        <span>Xem toàn bộ danh mục sản phẩm</span>
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
                                d="M9 5l7 7-7 7"
                            />
                        </svg>
                    </Link>
                </div>

                <div
                    class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6"
                >
                    <div
                        v-for="prod in featuredProducts"
                        :key="prod.id"
                        class="bg-slate-50 rounded-2xl border border-slate-200 overflow-hidden flex flex-col justify-between hover:shadow-lg transition group"
                    >
                        <div>
                            <!-- Product Image Mock / WebP -->
                            <div
                                class="h-44 bg-slate-200/80 flex items-center justify-center relative overflow-hidden group-hover:scale-102 transition"
                            >
                                <div
                                    class="w-16 h-16 rounded-2xl bg-[#0F294A]/10 flex items-center justify-center text-[#0F294A]"
                                >
                                    <svg
                                        class="w-10 h-10"
                                        fill="none"
                                        viewBox="0 0 24 24"
                                        stroke="currentColor"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="1.5"
                                            d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z"
                                        />
                                    </svg>
                                </div>
                                <div
                                    class="absolute top-3 left-3 bg-[#0F294A] text-white text-[10px] font-mono px-2 py-0.5 rounded-md font-semibold"
                                >
                                    {{ prod.model_code }}
                                </div>
                                <div
                                    v-if="prod.warranty_policy"
                                    class="absolute top-3 right-3 bg-emerald-600 text-white text-[10px] px-2 py-0.5 rounded-md font-semibold"
                                >
                                    BH 24T
                                </div>
                            </div>

                            <!-- Product Info -->
                            <div class="p-4 space-y-2">
                                <div
                                    class="text-[11px] font-semibold text-[#0284C7] uppercase"
                                >
                                    {{ prod.category?.name || "Toàn Cầu Tech" }}
                                </div>
                                <h3
                                    class="font-bold text-slate-900 text-sm line-clamp-2 leading-snug group-hover:text-[#0284C7] transition"
                                >
                                    {{ prod.name }}
                                </h3>
                                <p
                                    class="text-slate-600 text-xs line-clamp-3 leading-relaxed"
                                >
                                    {{ prod.overview }}
                                </p>
                            </div>
                        </div>

                        <!-- Price & CTA [Component 11] -->
                        <div
                            class="p-4 pt-0 border-t border-slate-100 flex items-center justify-between mt-3"
                        >
                            <div>
                                <div class="text-[10px] text-slate-400">
                                    Giá B2B:
                                </div>
                                <div
                                    class="text-sm font-extrabold text-[#0F294A]"
                                >
                                    <template v-if="prod.is_price_visible">
                                        {{ formatPrice(prod.price) }}
                                        <span
                                            class="text-[10px] font-normal text-slate-500"
                                            >/ {{ prod.price_unit }}</span
                                        >
                                    </template>
                                    <template v-else>
                                        <span class="text-[#EA580C]"
                                            >Liên hệ</span
                                        >
                                    </template>
                                </div>
                            </div>

                            <div class="flex items-center gap-1.5">
                                <Link
                                    :href="'/san-pham/' + prod.slug"
                                    class="px-2.5 py-1.5 rounded-lg bg-slate-200 text-slate-700 hover:bg-slate-300 text-xs font-semibold transition"
                                >
                                    Chi tiết
                                </Link>
                                <button
                                    type="button"
                                    @click="
                                        triggerLead('consultation_quote', prod, prod.name)
                                    "
                                    class="px-3 py-1.5 rounded-lg bg-[#EA580C] hover:bg-[#C2410C] text-white text-xs font-bold transition shadow-sm"
                                >
                                    Báo Giá
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- 5. BANNER: RECRUITING DEALERS & PARTNERS -->
        <section
            class="py-16 bg-gradient-to-r from-[#0F294A] to-[#183F70] text-white"
        >
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div
                    class="flex flex-col lg:flex-row lg:items-center justify-between gap-8 rounded-3xl bg-white/5 border border-white/10 p-8 sm:p-12 backdrop-blur-md"
                >
                    <div class="space-y-3 max-w-2xl">
                        <span
                            class="inline-block text-xs font-bold uppercase tracking-wider text-amber-300 bg-amber-400/10 px-3 py-1 rounded-full"
                        >
                            Chính Sách Đối Tác Toàn Quốc
                        </span>
                        <h2
                            class="text-2xl sm:text-4xl font-extrabold tracking-tight"
                        >
                            Gia Nhập Mạng Lưới Phân Phối & Đại Lý Toàn Cầu
                            Technology
                        </h2>
                        <p
                            class="text-xs sm:text-sm text-slate-300 leading-relaxed"
                        >
                            Chiết khấu thương mại hấp dẫn lên đến 35%, bảo hộ dự
                            án, hỗ trợ kỹ sư trực tiếp khảo sát hiện trường và
                            chuyển giao toàn diện phần mềm quản lý phát thanh
                            đám mây Bluecom.
                        </p>
                    </div>

                    <div class="flex flex-wrap items-center gap-4 shrink-0">
                        <Link
                            href="/doi-tac-dai-ly"
                            class="px-6 py-3.5 rounded-xl bg-white text-[#0F294A] font-extrabold text-sm hover:bg-slate-100 transition shadow-lg"
                        >
                            Xem Chính Sách Đại Lý
                        </Link>
                        <button
                            type="button"
                            @click="triggerLead('partner_registration')"
                            class="px-6 py-3.5 rounded-xl bg-[#EA580C] hover:bg-[#C2410C] text-white font-extrabold text-sm transition shadow-lg shadow-[#EA580C]/25"
                        >
                            Đăng Ký Làm Đại Lý Ngay
                        </button>
                    </div>
                </div>
            </div>
        </section>

        <!-- 6. FEATURED PROJECTS GRID -->
        <section class="py-20 bg-slate-50">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center max-w-2xl mx-auto mb-12">
                    <span
                        class="text-xs font-bold text-[#0284C7] uppercase tracking-wider bg-cyan-100/60 px-3 py-1 rounded-full"
                    >
                        Hồ Sơ Năng Lực
                    </span>
                    <h2
                        class="text-3xl font-extrabold text-[#0F294A] mt-3 tracking-tight"
                    >
                        Dự Án & Khách Hàng Tiêu Biểu
                    </h2>
                    <p class="text-slate-600 text-xs sm:text-sm mt-1">
                        Minh chứng năng lực triển khai thực tế qua các hợp đồng
                        trọng điểm trên toàn quốc.
                    </p>
                </div>

                <div
                    class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6"
                >
                    <div
                        v-for="project in featuredProjects"
                        :key="project.id"
                        class="bg-white rounded-2xl border border-slate-200/80 p-5 shadow-sm hover:shadow-md transition flex flex-col justify-between"
                    >
                        <div>
                            <div
                                class="h-36 bg-gradient-to-tr from-slate-200 to-slate-100 rounded-xl mb-4 flex items-center justify-center text-slate-400"
                            >
                                <svg
                                    class="w-12 h-12"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                    stroke="currentColor"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="1.5"
                                        d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"
                                    />
                                </svg>
                            </div>
                            <div
                                class="text-[11px] font-bold text-[#0284C7] uppercase"
                            >
                                {{ project.client_name }}
                            </div>
                            <h3
                                class="font-bold text-slate-900 text-sm mt-1 leading-snug"
                            >
                                {{ project.title }}
                            </h3>
                            <p
                                class="text-slate-600 text-xs mt-2 leading-relaxed"
                            >
                                {{ project.description }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </PublicLayout>
</template>
