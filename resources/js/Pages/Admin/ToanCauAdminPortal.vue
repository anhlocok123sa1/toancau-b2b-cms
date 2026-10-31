<script setup>
import { ref, computed } from 'vue';
import { Head, Link, useForm, usePage, router } from '@inertiajs/vue3';

const page = usePage();
const auth = computed(() => page.props.auth?.user || {});
const flash = computed(() => page.props.flash || {});

const props = defineProps({
  currentTab: {
    type: String,
    default: 'dashboard', // dashboard, leads, products, compliance
  },
  // Dashboard Props
  leadStats: {
    type: Object,
    default: null,
  },
  productStats: {
    type: Object,
    default: () => ({}),
  },
  complianceList: {
    type: Array,
    default: () => [],
  },
  complianceSummary: {
    type: Object,
    default: () => ({}),
  },
  recentActivities: {
    type: Array,
    default: () => [],
  },
  // Leads Portal Props
  leads: {
    type: Object,
    default: () => ({ data: [] }),
  },
  filters: {
    type: Object,
    default: () => ({}),
  },
  salesUsers: {
    type: Array,
    default: () => [],
  },
  statusLabels: {
    type: Object,
    default: () => ({}),
  },
  formTypes: {
    type: Object,
    default: () => ({}),
  },
  // Product & Content CMS Props
  products: {
    type: Object,
    default: () => ({ data: [] }),
  },
  categories: {
    type: Array,
    default: () => [],
  },
  projects: {
    type: Array,
    default: () => [],
  },
  vpsPlans: {
    type: Array,
    default: () => [],
  },
  pageSections: {
    type: Object,
    default: () => ({}),
  },
  posts: {
    type: Array,
    default: () => [],
  },
  // Settings Props
  systemSettings: {
    type: Object,
    default: () => ({}),
  },
});

const activeTab = ref(props.currentTab);

// Lead action modal state
const selectedLead = ref(null);
const isLeadDetailOpen = ref(false);
const leadStatusForm = useForm({
  status: '',
  assigned_to: '',
  closed_reason: '',
  note: '',
});

const leadNoteForm = useForm({
  note: '',
});

const openLeadDetail = (lead) => {
  selectedLead.value = lead;
  leadStatusForm.status = lead.status;
  leadStatusForm.assigned_to = lead.assigned_to || '';
  leadStatusForm.closed_reason = lead.closed_reason || '';
  leadStatusForm.note = '';
  isLeadDetailOpen.value = true;
};

const submitStatusChange = () => {
  leadStatusForm.post(`/admin/leads/${selectedLead.value.id}/status`, {
    preserveScroll: true,
    onSuccess: (res) => {
      isLeadDetailOpen.value = false;
    },
  });
};

const submitAddNote = () => {
  leadNoteForm.post(`/admin/leads/${selectedLead.value.id}/notes`, {
    preserveScroll: true,
    onSuccess: () => {
      leadNoteForm.reset();
    },
  });
};

// Filter forms
const leadFilterForm = useForm({
  status: props.filters?.status || '',
  form_type: props.filters?.form_type || '',
  search: props.filters?.search || '',
  sla_breach_only: props.filters?.sla_breach_only || false,
});

const applyLeadFilters = () => {
  router.get('/admin/leads', {
    status: leadFilterForm.status,
    form_type: leadFilterForm.form_type,
    search: leadFilterForm.search,
    sla_breach_only: leadFilterForm.sla_breach_only ? 1 : null,
  }, { preserveState: true });
};

// Product Quick Actions
const toggleProductPrice = (prod) => {
  router.post(`/admin/products/${prod.id}/toggle-price`, {}, { preserveScroll: true });
};
const toggleProductFeatured = (prod) => {
  router.post(`/admin/products/${prod.id}/toggle-featured`, {}, { preserveScroll: true });
};
const toggleProductActive = (prod) => {
  router.post(`/admin/products/${prod.id}/toggle-active`, {}, { preserveScroll: true });
};

// Compliance Quick Toggle (Admin only)
const toggleCompliance = (chk) => {
  router.post(`/admin/compliance/${chk.id}/toggle`, {}, { preserveScroll: true });
};

const complianceTargets = {
  legal_corporate_profile: {
    url: '/ve-toan-cau',
    name: 'Về Toàn Cầu & Liên Hệ',
    onEffect: 'Hiện huy hiệu xanh: "✓ ĐÃ XÁC THỰC PHÁP NHÂN (SỞ KH&ĐT TP.HCM)"',
    offEffect: 'Hiện nhãn vàng: "Đang Trong Tiến Trình Thẩm Định Hồ Sơ Pháp Nhân"',
  },
  ip_resource_4000: {
    url: '/cloud-infrastructure?tab=ip',
    name: 'Cloud & Cho Thuê IP',
    onEffect: 'Hiện rõ con số: "4.000+ IP sạch APNIC" trên trang chủ & trang Cloud',
    offEffect: 'Ẩn con số 4.000, chỉ hiển thị chung: "Tài nguyên mạng IP Doanh nghiệp"',
  },
  starlink_reseller_capability: {
    url: '/internet-connectivity',
    name: 'Internet & Starlink',
    onEffect: 'Hiện huy hiệu vàng: "✓ ĐẠI LÝ ỦY QUYỀN CHÍNH THỨC STARLINK TẠI VIỆT NAM"',
    offEffect: 'Chuyển về: "ĐƠN VỊ TƯ VẤN & KHẢO SÁT HIỆN TRƯỜNG STARLINK" (an toàn pháp lý)',
  },
  bluecom_distribution_status: {
    url: '/bluecom',
    name: 'Trang Bluecom 4G',
    onEffect: 'Hiện chứng nhận: "✓ CHỨNG NHẬN NHÀ PHÂN PHỐI CHÍNH HÃNG BLUECOM 4G (BC-DIST-VN-2026)"',
    offEffect: 'Chỉ hiện: "HỆ THỐNG TRUYỀN THANH SỐ 4G"',
  },
  product_datasheets_warranty: {
    url: '/san-pham',
    name: 'Chi Tiết Sản Phẩm',
    onEffect: 'Cho phép người dùng bấm "Tải Datasheet (PDF)" chính hãng',
    offEffect: 'Nút chuyển sang: "Datasheet PDF đang được cập nhật"',
  },
  lead_sla_privacy_nd13: {
    url: '/',
    name: 'Biểu Mẫu Tiếp Nhận Lead',
    onEffect: 'Bật huy hiệu cam kết SLA 15-30 phút & bảo vệ dữ liệu cá nhân theo Nghị định 13/2023/NĐ-CP',
    offEffect: 'Giao diện tiếp nhận liên hệ thông thường',
  },
};

// Settings form (Admin only)
const settingsForm = useForm({
  google_analytics_id: props.systemSettings?.google_analytics_id || '',
  google_search_console_token: props.systemSettings?.google_search_console_token || '',
  hotline: props.systemSettings?.hotline || '',
  zalo_oa_url: props.systemSettings?.zalo_oa_url || '',
  show_infra_stats_home: props.systemSettings?.show_infra_stats_home === '1',
});

const saveSettings = () => {
  settingsForm.post('/admin/settings', { preserveScroll: true });
};

// Create Product Modal
const isCreateProductOpen = ref(false);
const createProdForm = useForm({
  category_id: props.categories?.[0]?.id || '',
  sector_code: 'cloud_infra',
  name: '',
  model_code: '',
  overview: '',
  warranty_policy: '24 tháng 1 đổi 1',
  support_commitment: 'SLA kỹ thuật 15-30 phút',
  price: '',
  price_unit: 'tháng',
  is_price_visible: false,
  is_featured: false,
  thumbnail_file: null,
  datasheet_file: null,
  specs_json: JSON.stringify([
    { label: 'Vi xử lý', value: 'AMD EPYC' },
    { label: 'Cổng mạng', value: '1 Gbps' }
  ], null, 2),
});

const submitCreateProduct = () => {
  createProdForm.post('/admin/products', {
    preserveScroll: true,
    onSuccess: () => {
      isCreateProductOpen.value = false;
      createProdForm.reset();
    },
  });
};

// ========================================================
// VISUAL ZERO-CODE CONTENT MANAGEMENT SYSTEM (CMS)
// ========================================================
const contentSubTab = ref('pages'); // 'pages', 'sectors', 'projects', 'vps', 'products', 'posts'
const selectedPageKey = ref('home');

const pageOptions = [
  { key: 'home', label: '🏠 Trang Chủ', desc: 'Hero Banner, Hệ Sinh Thái 6 Trụ Cột' },
  { key: 'cloud', label: '☁️ Cloud & Hạ Tầng', desc: 'Private Cloud SME/SI, Dịch Vụ Cho Thuê IP' },
  { key: 'internet', label: '🌐 Internet Doanh Nghiệp', desc: 'Leased Line, SD-WAN, SIM IoT, Starlink' },
  { key: 'smart_iot', label: '📡 Smart IoT', desc: 'Giải pháp Solar Camera & Cảm biến' },
  { key: 'bluecom', label: '📢 Bluecom 4G', desc: 'Sơ đồ 4 bước & Truyền thanh AI' },
  { key: 'tech_services', label: '🛠️ Dịch Vụ Kỹ Thuật', desc: 'Thi công trọn gói & Bảo trì khẩn cấp' },
  { key: 'about', label: '🏢 Về Toàn Cầu', desc: 'Hồ sơ năng lực & 3 Trụ cột thế mạnh' },
];

// 1. Page Section Visual Modal
const isEditSectionOpen = ref(false);
const selectedSection = ref(null);
const sectionForm = useForm({
  title: '',
  subtitle: '',
  badge: '',
  content: '',
  metadata_json: '',
});

const openEditSection = (section) => {
  selectedSection.value = section;
  sectionForm.title = section.title || '';
  sectionForm.subtitle = section.subtitle || '';
  sectionForm.badge = section.badge || '';
  sectionForm.content = section.content || '';
  sectionForm.metadata_json = JSON.stringify(section.metadata || {}, null, 2);
  isEditSectionOpen.value = true;
};

const submitUpdateSection = () => {
  sectionForm.post(`/admin/page-sections/${selectedSection.value.id}`, {
    preserveScroll: true,
    onSuccess: () => {
      isEditSectionOpen.value = false;
    },
  });
};

// 2. Categories & Core Sectors
const isCreateCategoryOpen = ref(false);
const isEditCategoryOpen = ref(false);
const selectedCategory = ref(null);
const selectedSectorFilter = ref('all');

const systemRootSlugs = [
  'cloud-infrastructure',
  'internet-connectivity',
  'smart-iot',
  'bluecom',
  'technical-services',
  'doi-tac-dai-ly',
  've-toan-cau',
];

const isSystemRootCategory = (cat) => {
  return !cat.parent_id && systemRootSlugs.includes(cat.slug);
};

const getCategoryPublicUrl = (cat) => {
  if (!cat) return '#';
  if (!cat.parent_id) {
    if (cat.slug === 'cloud-infrastructure') return '/cloud-infrastructure';
    return `/${cat.slug}`;
  }
  // Sub-category
  if (cat.sector_code === 'cloud_infra') {
    if (cat.slug === 'dedicated-server') return '/cloud-infrastructure?tab=dedicated';
    if (cat.slug === 'cloud-vps') return '/cloud-infrastructure?tab=vps';
    if (cat.slug === 'ip-services') return '/cloud-infrastructure?tab=ip';
    if (cat.slug === 'private-cloud' || cat.slug === 'cloud-business') return '/cloud-infrastructure?tab=business';
    return '/cloud-infrastructure';
  }
  if (cat.sector_code === 'smart_iot') {
    return `/smart-iot?sub=${cat.slug}`;
  }
  return `/san-pham?category=${cat.slug}`;
};

const rootCategories = computed(() => {
  return (props.categories || []).filter(c => !c.parent_id);
});

const filteredCategories = computed(() => {
  if (!props.categories) return [];
  if (selectedSectorFilter.value === 'all') return props.categories;
  return props.categories.filter(c => c.sector_code === selectedSectorFilter.value);
});

const categoryForm = useForm({
  name: '',
  sector_code: 'smart_iot',
  parent_id: '',
  description: '',
  icon: 'CpuChipIcon',
  display_order: 1,
  is_active: true,
});

const openCreateCategory = (prefillSector = null, prefillParentId = null) => {
  categoryForm.reset();
  const sector = prefillSector || (selectedSectorFilter.value !== 'all' ? selectedSectorFilter.value : 'smart_iot');
  categoryForm.sector_code = sector;
  
  if (prefillParentId) {
    categoryForm.parent_id = prefillParentId;
  } else {
    const root = (props.categories || []).find(c => c.sector_code === sector && !c.parent_id);
    categoryForm.parent_id = root ? root.id : '';
  }
  
  categoryForm.icon = sector === 'smart_iot' ? 'CpuChipIcon' : 'ServerIcon';
  categoryForm.display_order = ((props.categories || []).filter(c => c.sector_code === sector).length) + 1;
  categoryForm.is_active = true;
  isCreateCategoryOpen.value = true;
};

const openEditCategory = (cat) => {
  selectedCategory.value = cat;
  categoryForm.name = cat.name;
  categoryForm.sector_code = cat.sector_code;
  categoryForm.parent_id = cat.parent_id || '';
  categoryForm.description = cat.description || '';
  categoryForm.icon = cat.icon || 'ServerIcon';
  categoryForm.display_order = cat.display_order || 1;
  categoryForm.is_active = !!cat.is_active;
  isEditCategoryOpen.value = true;
};

const submitCreateCategory = () => {
  categoryForm.post('/admin/categories', {
    preserveScroll: true,
    onSuccess: () => {
      isCreateCategoryOpen.value = false;
      categoryForm.reset();
    },
  });
};

const submitUpdateCategory = () => {
  categoryForm.post(`/admin/categories/${selectedCategory.value.id}`, {
    preserveScroll: true,
    onSuccess: () => {
      isEditCategoryOpen.value = false;
    },
  });
};

const deleteCategory = (cat) => {
  if (isSystemRootCategory(cat)) {
    alert(`[${cat.name}] là 1 trong 8 trụ cột hệ thống gốc của website. Bạn có thể bấm "Sửa" để đổi tên hoặc ẩn, nhưng không thể xóa hoàn toàn để bảo vệ cấu trúc trang!`);
    return;
  }
  if (cat.products_count && cat.products_count > 0) {
    alert(`Không thể xóa danh mục [${cat.name}] vì đang có ${cat.products_count} sản phẩm trực thuộc. Vui lòng chuyển hoặc xóa sản phẩm trước!`);
    return;
  }
  if (confirm(`Bạn có chắc chắn muốn xóa danh mục [${cat.name}]? Thao tác này sẽ đồng bộ ngay lập tức ra ngoài website.`)) {
    router.delete(`/admin/categories/${cat.id}`, { preserveScroll: true });
  }
};

// 3. Projects & Clients Showcase
const isCreateProjectOpen = ref(false);
const isEditProjectOpen = ref(false);
const selectedProject = ref(null);
const projectForm = useForm({
  title: '',
  client_name: '',
  sector_code: 'cloud_infra',
  description: '',
  is_featured: true,
  image_file: null,
});

const openCreateProject = () => {
  projectForm.reset();
  projectForm.sector_code = 'cloud_infra';
  projectForm.is_featured = true;
  isCreateProjectOpen.value = true;
};

const openEditProject = (proj) => {
  selectedProject.value = proj;
  projectForm.title = proj.title;
  projectForm.client_name = proj.client_name;
  projectForm.sector_code = proj.sector_code;
  projectForm.description = proj.description || '';
  projectForm.is_featured = !!proj.is_featured;
  projectForm.image_file = null;
  isEditProjectOpen.value = true;
};

const submitCreateProject = () => {
  projectForm.post('/admin/projects', {
    preserveScroll: true,
    onSuccess: () => {
      isCreateProjectOpen.value = false;
      projectForm.reset();
    },
  });
};

const submitUpdateProject = () => {
  projectForm.post(`/admin/projects/${selectedProject.value.id}`, {
    preserveScroll: true,
    onSuccess: () => {
      isEditProjectOpen.value = false;
    },
  });
};

const deleteProject = (proj) => {
  if (confirm(`Bạn có chắc muốn xóa dự án [${proj.title}]?`)) {
    router.delete(`/admin/projects/${proj.id}`, { preserveScroll: true });
  }
};

const toggleProjectFeatured = (proj) => {
  router.post(`/admin/projects/${proj.id}/toggle-featured`, {}, { preserveScroll: true });
};

// 4. VPS Plans & Comparison Matrix
const isCreateVpsOpen = ref(false);
const isEditVpsOpen = ref(false);
const selectedVps = ref(null);
const vpsForm = useForm({
  name: '',
  vcpu: 4,
  ram_gb: 8,
  storage_nvme_gb: 120,
  bandwidth: 'Không giới hạn (Port 10Gbps Uplink)',
  port_speed: '10 Gbps Uplink Dedicated',
  ipv4_count: 1,
  price_monthly: 450000,
  price_yearly: 4590000,
  discount_percent_yearly: 15,
  features_text: '',
  is_popular: false,
});

const openCreateVps = () => {
  vpsForm.reset();
  vpsForm.features_text = "4 vCPU Dedicated AMD EPYC\n8 GB RAM ECC Server\n120 GB NVMe Enterprise RAID-10\n1 IPv4 tĩnh sạch\nAnti-DDoS Layer 7\nSLA 99.95% Uptime";
  isCreateVpsOpen.value = true;
};

const openEditVps = (vps) => {
  selectedVps.value = vps;
  vpsForm.name = vps.name;
  vpsForm.vcpu = vps.vcpu;
  vpsForm.ram_gb = vps.ram_gb;
  vpsForm.storage_nvme_gb = vps.storage_nvme_gb;
  vpsForm.bandwidth = vps.bandwidth;
  vpsForm.port_speed = vps.port_speed;
  vpsForm.ipv4_count = vps.ipv4_count;
  vpsForm.price_monthly = vps.price_monthly;
  vpsForm.price_yearly = vps.price_yearly;
  vpsForm.discount_percent_yearly = vps.discount_percent_yearly || 15;
  vpsForm.features_text = Array.isArray(vps.features) ? vps.features.join('\n') : '';
  vpsForm.is_popular = !!vps.is_popular;
  isEditVpsOpen.value = true;
};

const submitCreateVps = () => {
  vpsForm.post('/admin/vps-plans', {
    preserveScroll: true,
    onSuccess: () => {
      isCreateVpsOpen.value = false;
      vpsForm.reset();
    },
  });
};

const submitUpdateVps = () => {
  vpsForm.post(`/admin/vps-plans/${selectedVps.value.id}`, {
    preserveScroll: true,
    onSuccess: () => {
      isEditVpsOpen.value = false;
    },
  });
};

const deleteVps = (vps) => {
  if (confirm(`Bạn có chắc muốn xóa gói VPS [${vps.name}]?`)) {
    router.delete(`/admin/vps-plans/${vps.id}`, { preserveScroll: true });
  }
};

// 5. Products Edit & Delete
const isEditProductOpen = ref(false);
const selectedProduct = ref(null);
const editProdForm = useForm({
  name: '',
  model_code: '',
  category_id: '',
  sector_code: 'cloud_infra',
  overview: '',
  warranty_policy: '',
  support_commitment: '',
  price: '',
  price_unit: 'tháng',
  is_price_visible: false,
  is_featured: false,
  thumbnail_file: null,
  datasheet_file: null,
  specs_json: '',
  features_json: '',
  scenarios_json: '',
});

const openEditProduct = (prod) => {
  selectedProduct.value = prod;
  editProdForm.name = prod.name;
  editProdForm.model_code = prod.model_code;
  editProdForm.category_id = prod.category_id;
  editProdForm.sector_code = prod.sector_code;
  editProdForm.overview = prod.overview;
  editProdForm.warranty_policy = prod.warranty_policy || '';
  editProdForm.support_commitment = prod.support_commitment || '';
  editProdForm.price = prod.price || '';
  editProdForm.price_unit = prod.price_unit || 'tháng';
  editProdForm.is_price_visible = !!prod.is_price_visible;
  editProdForm.is_featured = !!prod.is_featured;
  editProdForm.thumbnail_file = null;
  editProdForm.datasheet_file = null;
  editProdForm.specs_json = JSON.stringify(prod.specs || [], null, 2);
  editProdForm.features_json = JSON.stringify(prod.features || [], null, 2);
  editProdForm.scenarios_json = JSON.stringify(prod.application_scenarios || [], null, 2);
  isEditProductOpen.value = true;
};

const submitUpdateProduct = () => {
  editProdForm.post(`/admin/products/${selectedProduct.value.id}`, {
    preserveScroll: true,
    onSuccess: () => {
      isEditProductOpen.value = false;
    },
  });
};

const deleteProduct = (prod) => {
  if (confirm(`Bạn có chắc chắn muốn xóa sản phẩm [${prod.name}]?`)) {
    router.delete(`/admin/products/${prod.id}`, { preserveScroll: true });
  }
};

// 6. News Posts
const isCreatePostOpen = ref(false);
const isEditPostOpen = ref(false);
const selectedPost = ref(null);
const postForm = useForm({
  title: '',
  summary: '',
  content: '',
  author_name: 'Ban Biên Tập Kỹ Thuật Toàn Cầu',
  is_published: true,
  thumbnail_file: null,
});

const openCreatePost = () => {
  postForm.reset();
  postForm.author_name = 'Ban Biên Tập Kỹ Thuật Toàn Cầu';
  postForm.is_published = true;
  isCreatePostOpen.value = true;
};

const openEditPost = (p) => {
  selectedPost.value = p;
  postForm.title = p.title;
  postForm.summary = p.summary || '';
  postForm.content = p.content || '';
  postForm.author_name = p.author_name || 'Ban Biên Tập Kỹ Thuật Toàn Cầu';
  postForm.is_published = !!p.is_published;
  postForm.thumbnail_file = null;
  isEditPostOpen.value = true;
};

const submitCreatePost = () => {
  postForm.post('/admin/posts', {
    preserveScroll: true,
    onSuccess: () => {
      isCreatePostOpen.value = false;
      postForm.reset();
    },
  });
};

const submitUpdatePost = () => {
  postForm.post(`/admin/posts/${selectedPost.value.id}`, {
    preserveScroll: true,
    onSuccess: () => {
      isEditPostOpen.value = false;
    },
  });
};

const deletePost = (p) => {
  if (confirm(`Bạn có chắc chắn muốn xóa bài viết [${p.title}]?`)) {
    router.delete(`/admin/posts/${p.id}`, { preserveScroll: true });
  }
};

const formatVnd = (amount) => {
  if (!amount) return 'Liên hệ';
  return new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND' }).format(amount);
};
</script>

<template>
  <div class="min-h-screen bg-slate-100 font-sans text-slate-800 flex flex-col">
    <Head>
      <title>Toàn Cầu Admin Portal - CMS & Lead CRM</title>
    </Head>

    <!-- Admin Topbar -->
    <header class="bg-[#0F294A] text-white border-b border-slate-700 sticky top-0 z-40 shadow-md">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-16">
          
          <div class="flex items-center gap-6">
            <Link href="/admin/dashboard" class="flex items-center gap-2.5">
              <div class="bg-white px-2 py-0.5 rounded-lg shadow-sm flex items-center h-9">
                <img src="/images/logo.png" alt="GlobalCorp" class="h-6 w-auto object-contain" />
              </div>
              <span class="font-extrabold text-white text-xs tracking-tight hidden sm:inline border-l border-slate-700 pl-2.5 py-1">
                B2B <span class="text-cyan-400">ADMIN CMS</span>
              </span>
            </Link>

            <!-- Navigation Tabs -->
            <nav class="flex items-center space-x-1 sm:space-x-2 text-xs font-semibold">
              <Link 
                href="/admin/dashboard"
                :class="activeTab === 'dashboard' ? 'bg-[#0284C7] text-white' : 'text-slate-300 hover:bg-slate-800'"
                class="px-3 py-2 rounded-lg transition"
              >
                Tổng Quan
              </Link>

              <!-- STRICT RBAC: Content Admin is strictly blocked from Leads! -->
              <Link 
                v-if="!auth.is_content_admin"
                href="/admin/leads"
                :class="activeTab === 'leads' ? 'bg-[#0284C7] text-white' : 'text-slate-300 hover:bg-slate-800'"
                class="px-3 py-2 rounded-lg transition flex items-center gap-1.5"
              >
                <span>Lead Portal</span>
                <span v-if="leadStats && leadStats.new > 0" class="bg-[#EA580C] text-white text-[10px] font-bold px-1.5 py-0.2 rounded-full">
                  {{ leadStats.new }}
                </span>
              </Link>

              <!-- Product CMS (Admin, Content Admin, Viewer) -->
              <Link 
                v-if="!auth.is_sales && !auth.is_partner_mgr"
                href="/admin/products"
                :class="activeTab === 'products' ? 'bg-[#0284C7] text-white' : 'text-slate-300 hover:bg-slate-800'"
                class="px-3 py-2 rounded-lg transition"
              >
                Quản Trị Nội Dung & CMS
              </Link>

              <!-- Compliance & Settings (Admin Only) -->
              <Link 
                v-if="auth.is_admin"
                href="/admin/compliance"
                :class="activeTab === 'compliance' ? 'bg-[#0284C7] text-white' : 'text-slate-300 hover:bg-slate-800'"
                class="px-3 py-2 rounded-lg transition"
              >
                Pháp Lý & Cấu Hình
              </Link>
            </nav>
          </div>

          <!-- User Info & Logout -->
          <div class="flex items-center gap-3">
            <div class="text-right hidden sm:block">
              <div class="text-xs font-bold text-white">{{ auth.name }}</div>
              <div class="text-[10px] text-cyan-300 font-medium">
                {{ auth.roles?.[0] || 'Staff' }}
                <span v-if="auth.is_viewer" class="text-amber-300 ml-1">(Chỉ Xem)</span>
                <span v-if="auth.is_content_admin" class="text-purple-300 ml-1">(Chặn Lead)</span>
              </div>
            </div>

            <Link href="/" class="p-2 text-slate-300 hover:text-white rounded-lg hover:bg-slate-800 transition" title="Xem Website">
              <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
              </svg>
            </Link>

            <Link href="/logout" method="post" as="button" class="px-3 py-1.5 bg-rose-600/80 hover:bg-rose-600 text-white rounded-lg text-xs font-bold transition">
              Thoát
            </Link>
          </div>

        </div>
      </div>
    </header>

    <!-- Flash message -->
    <div v-if="flash.success" class="bg-emerald-600 text-white px-4 py-2 text-xs font-bold text-center shadow">
      {{ flash.success }}
    </div>
    <div v-if="flash.error" class="bg-rose-600 text-white px-4 py-2 text-xs font-bold text-center shadow">
      {{ flash.error }}
    </div>

    <!-- MAIN BODY -->
    <main class="flex-1 max-w-7xl w-full mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-8">
      
      <!-- ======================================================== -->
      <!-- TAB 1: DASHBOARD OVERVIEW -->
      <!-- ======================================================== -->
      <div v-if="activeTab === 'dashboard'" class="space-y-8">
        
        <!-- Welcome Banner -->
        <div class="bg-gradient-to-r from-[#0F294A] to-[#183F70] text-white rounded-3xl p-6 sm:p-8 shadow-sm flex flex-col sm:flex-row sm:items-center justify-between gap-4">
          <div>
            <div class="inline-flex items-center gap-1.5 px-3 py-0.5 rounded-full text-xs font-bold bg-[#0284C7] text-white mb-2">
              Vai trò hiện tại: {{ auth.roles?.[0] }}
            </div>
            <h1 class="text-2xl font-black tracking-tight">Xin chào, {{ auth.name }}!</h1>
            <p class="text-xs text-slate-300 mt-1 max-w-xl">
              Hệ thống đang giám sát thời gian xử lý Lead theo cam kết SLA 30 phút và tuân thủ 6 hạng mục kiểm soát pháp lý B2B.
            </p>
          </div>

          <div v-if="!auth.is_content_admin" class="flex items-center gap-3">
            <Link href="/admin/leads" class="px-5 py-2.5 rounded-xl bg-[#EA580C] hover:bg-[#C2410C] text-white text-xs font-bold shadow transition">
              Quản Lý Yêu Cầu Lead →
            </Link>
          </div>
        </div>

        <!-- 5-State Lead Metrics (Strictly hidden for Content Admin) -->
        <div v-if="leadStats" class="space-y-4">
          <div class="flex items-center justify-between">
            <h2 class="text-lg font-bold text-[#0F294A]">Vòng Đời Xử Lý Lead B2B (5 Trạng Thái Chuẩn Hóa)</h2>
            <span class="text-xs text-slate-500">Mục tiêu xử lý &lt; 30 phút</span>
          </div>

          <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-4">
            <div class="bg-white rounded-2xl border border-slate-200 p-4 shadow-sm border-l-4 border-rose-500">
              <div class="text-xs font-bold text-slate-500 uppercase">[1] MỚI</div>
              <div class="text-2xl font-black text-rose-600 mt-1">{{ leadStats.new }}</div>
              <div class="text-[10px] text-slate-400 mt-0.5">Chờ tiếp nhận</div>
            </div>

            <div class="bg-white rounded-2xl border border-slate-200 p-4 shadow-sm border-l-4 border-amber-500">
              <div class="text-xs font-bold text-slate-500 uppercase">[2] ĐÃ TIẾP NHẬN</div>
              <div class="text-2xl font-black text-amber-600 mt-1">{{ leadStats.acknowledged }}</div>
              <div class="text-[10px] text-slate-400 mt-0.5">Đã phân công</div>
            </div>

            <div class="bg-white rounded-2xl border border-slate-200 p-4 shadow-sm border-l-4 border-cyan-500">
              <div class="text-xs font-bold text-slate-500 uppercase">[3] ĐANG TƯ VẤN</div>
              <div class="text-2xl font-black text-[#0284C7] mt-1">{{ leadStats.in_progress }}</div>
              <div class="text-[10px] text-slate-400 mt-0.5">Khảo sát / Demo</div>
            </div>

            <div class="bg-white rounded-2xl border border-slate-200 p-4 shadow-sm border-l-4 border-indigo-500">
              <div class="text-xs font-bold text-slate-500 uppercase">[4] ĐÃ BÁO GIÁ</div>
              <div class="text-2xl font-black text-indigo-600 mt-1">{{ leadStats.quoted }}</div>
              <div class="text-[10px] text-slate-400 mt-0.5">Đã gửi bảng giá</div>
            </div>

            <div class="bg-white rounded-2xl border border-slate-200 p-4 shadow-sm border-l-4 border-emerald-500">
              <div class="text-xs font-bold text-slate-500 uppercase">[5] THÀNH CÔNG</div>
              <div class="text-2xl font-black text-emerald-600 mt-1">{{ leadStats.closed_won }}</div>
              <div class="text-[10px] text-slate-400 mt-0.5">Ký kết hợp đồng</div>
            </div>

            <div class="bg-white rounded-2xl border border-slate-200 p-4 shadow-sm border-l-4 border-slate-500">
              <div class="text-xs font-bold text-slate-500 uppercase">[5] THẤT BẠI</div>
              <div class="text-2xl font-black text-slate-600 mt-1">{{ leadStats.closed_lost }}</div>
              <div class="text-[10px] text-slate-400 mt-0.5">Có lý do cụ thể</div>
            </div>
          </div>

          <!-- SLA Alert if breaches exist -->
          <div v-if="leadStats.sla_breached > 0" class="p-4 rounded-2xl bg-rose-50 border border-rose-200 flex items-center justify-between text-xs text-rose-900">
            <div class="flex items-center gap-2">
              <svg class="w-5 h-5 text-rose-600" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
              </svg>
              <span><strong>Cảnh báo SLA:</strong> Hiện có <strong class="text-rose-700 underline">{{ leadStats.sla_breached }} yêu cầu</strong> đã quá hạn 30 phút mà chưa được chuyển sang trạng thái đã tiếp nhận hoặc tư vấn!</span>
            </div>
            <Link href="/admin/leads?sla_breach_only=1" class="font-bold text-rose-700 underline">
              Xem ngay →
            </Link>
          </div>
        </div>

        <!-- Compliance & Legal Checklist Status -->
        <div class="bg-white rounded-3xl border border-slate-200 p-6 shadow-sm space-y-4">
          <div class="flex items-center justify-between">
            <div>
              <h2 class="text-lg font-bold text-[#0F294A]">6 Hạng Mục Kiểm Soát Pháp Lý & Thông Tin Nhạy Cảm</h2>
              <p class="text-xs text-slate-500 mt-0.5">Mặc định ẩn (`is_verified = 0`), chỉ công bố Public khi Admin phê duyệt.</p>
            </div>
            <span class="text-xs font-bold bg-cyan-50 text-[#0284C7] px-3 py-1 rounded-full">
              Đã duyệt {{ complianceSummary.verified }} / {{ complianceSummary.total }}
            </span>
          </div>

          <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            <div 
              v-for="chk in complianceList" 
              :key="chk.id"
              :class="chk.is_verified ? 'bg-emerald-50/40 border-emerald-200' : 'bg-slate-50 border-slate-200'"
              class="rounded-2xl border p-4 flex flex-col justify-between"
            >
              <div>
                <div class="flex items-center justify-between mb-2">
                  <span :class="chk.is_verified ? 'bg-emerald-600 text-white' : 'bg-slate-400 text-white'" class="text-[10px] font-bold px-2 py-0.5 rounded-full uppercase">
                    {{ chk.is_verified ? 'Đã Duyệt (Công Khai)' : 'Chưa Duyệt (Tạm Ẩn)' }}
                  </span>
                  <span class="text-[10px] font-mono text-slate-400">{{ chk.item_key }}</span>
                </div>
                <h4 class="font-bold text-xs text-slate-900 leading-snug">{{ chk.title }}</h4>
                <p class="text-[11px] text-slate-600 mt-1 leading-relaxed">{{ chk.description }}</p>
              </div>

              <div class="mt-4 pt-3 border-t border-slate-200/60 flex items-center justify-between text-[11px]">
                <span class="text-slate-400">
                  {{ chk.verifier ? 'Bởi: ' + chk.verifier.name : 'Chưa có người duyệt' }}
                </span>
                <button 
                  v-if="auth.is_admin"
                  type="button" 
                  @click="toggleCompliance(chk)"
                  :class="chk.is_verified ? 'text-rose-600 hover:underline' : 'text-emerald-700 font-bold hover:underline'"
                >
                  {{ chk.is_verified ? 'Hủy duyệt' : 'Phê duyệt ngay' }}
                </button>
              </div>
            </div>
          </div>
        </div>

        <!-- Recent Activity Logs (Admin Only) -->
        <div v-if="auth.is_admin && recentActivities.length > 0" class="bg-white rounded-3xl border border-slate-200 p-6 shadow-sm space-y-4">
          <h2 class="text-lg font-bold text-[#0F294A]">Nhật Ký Hoạt Động Hệ Thống (Audit Logs)</h2>
          <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
              <thead class="bg-slate-50 text-slate-500 font-semibold uppercase text-[10px] border-b border-slate-200">
                <tr>
                  <th class="py-2.5 px-3">Thời Gian</th>
                  <th class="py-2.5 px-3">Người Thực Hiện</th>
                  <th class="py-2.5 px-3">Đối Tượng</th>
                  <th class="py-2.5 px-3">Hành Động</th>
                </tr>
              </thead>
              <tbody class="divide-y divide-slate-100">
                <tr v-for="act in recentActivities" :key="act.id" class="hover:bg-slate-50">
                  <td class="py-2.5 px-3 text-slate-500 font-mono">{{ act.created_at }}</td>
                  <td class="py-2.5 px-3 font-bold text-slate-900">{{ act.causer_name }}</td>
                  <td class="py-2.5 px-3 text-[#0284C7] font-semibold">{{ act.subject_type }}</td>
                  <td class="py-2.5 px-3 text-slate-700">{{ act.description }}</td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>

      </div>

      <!-- ======================================================== -->
      <!-- TAB 2: LEADS PORTAL (Blocked for Content Admin) -->
      <!-- ======================================================== -->
      <div v-else-if="activeTab === 'leads'" class="space-y-6">
        
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
          <div>
            <h1 class="text-2xl font-black text-[#0F294A]">Cổng Quản Lý Lead B2B & Yêu Cầu Kỹ Thuật</h1>
            <p class="text-xs text-slate-500 mt-1">Tiếp nhận yêu cầu từ 3 biểu mẫu với cam kết SLA xử lý 15-30 phút.</p>
          </div>

          <div class="flex items-center gap-3">
            <a 
              href="/admin/leads-export" 
              class="px-4 py-2.5 rounded-xl bg-slate-800 hover:bg-slate-900 text-white text-xs font-bold transition flex items-center gap-2 shadow"
            >
              <svg class="w-4 h-4 text-emerald-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
              </svg>
              <span>Xuất CSV (Excel)</span>
            </a>
          </div>
        </div>

        <!-- Filter Bar -->
        <div class="bg-white p-4 rounded-2xl border border-slate-200 shadow-sm flex flex-wrap items-center gap-4 text-xs">
          <div>
            <select v-model="leadFilterForm.status" @change="applyLeadFilters" class="rounded-xl border border-slate-300 py-2 px-3 text-xs bg-white outline-none">
              <option value="">-- Tất cả trạng thái --</option>
              <option v-for="(lbl, val) in statusLabels" :key="val" :value="val">{{ lbl }}</option>
            </select>
          </div>

          <div>
            <select v-model="leadFilterForm.form_type" @change="applyLeadFilters" class="rounded-xl border border-slate-300 py-2 px-3 text-xs bg-white outline-none">
              <option value="">-- Tất cả loại biểu mẫu --</option>
              <option v-for="(lbl, val) in formTypes" :key="val" :value="val">{{ lbl }}</option>
            </select>
          </div>

          <div class="flex-1 min-w-[200px]">
            <input 
              v-model="leadFilterForm.search" 
              @keyup.enter="applyLeadFilters" 
              type="text" 
              placeholder="Tìm theo tên, mã lead, SĐT, công ty..." 
              class="w-full rounded-xl border border-slate-300 py-2 px-3 text-xs outline-none focus:border-[#0284C7]"
            />
          </div>

          <label class="flex items-center gap-2 cursor-pointer font-semibold text-rose-600">
            <input v-model="leadFilterForm.sla_breach_only" @change="applyLeadFilters" type="checkbox" class="rounded text-rose-600" />
            <span>Chỉ xem Lead quá hạn SLA 30p</span>
          </label>

          <button @click="applyLeadFilters" type="button" class="px-4 py-2 bg-[#0284C7] hover:bg-cyan-700 text-white rounded-xl font-bold">
            Lọc
          </button>
        </div>

        <!-- Leads Table -->
        <div class="bg-white rounded-3xl border border-slate-200 overflow-hidden shadow-sm">
          <div class="overflow-x-auto">
            <table class="w-full text-left text-xs border-collapse">
              <thead class="bg-slate-50 text-slate-500 font-bold uppercase text-[10px] border-b border-slate-200">
                <tr>
                  <th class="py-3 px-4">Mã Lead</th>
                  <th class="py-3 px-4">Loại Biểu Mẫu</th>
                  <th class="py-3 px-4">Khách Hàng & Doanh Nghiệp</th>
                  <th class="py-3 px-4">Dịch Vụ Quan Tâm</th>
                  <th class="py-3 px-4">Trạng Thái (5 Bước)</th>
                  <th class="py-3 px-4">Hạn SLA 30p</th>
                  <th class="py-3 px-4">Phụ Trách</th>
                  <th class="py-3 px-4 text-right">Thao Tác</th>
                </tr>
              </thead>
              <tbody class="divide-y divide-slate-100">
                <tr v-for="lead in leads.data" :key="lead.id" class="hover:bg-slate-50 transition">
                  <td class="py-3 px-4 font-mono font-bold text-[#0F294A]">
                    {{ lead.code }}
                  </td>
                  <td class="py-3 px-4">
                    <span class="inline-block px-2 py-0.5 rounded text-[10px] font-semibold bg-slate-100 text-slate-700">
                      {{ formTypes[lead.form_type] || lead.form_type }}
                    </span>
                  </td>
                  <td class="py-3 px-4">
                    <div class="font-bold text-slate-900">{{ lead.full_name }}</div>
                    <div class="text-slate-500 text-[11px]">{{ lead.company_name || 'Cá nhân' }}</div>
                    <div class="text-[#0284C7] font-mono text-[11px]">{{ lead.phone }}</div>
                  </td>
                  <td class="py-3 px-4 max-w-xs truncate text-slate-700">
                    {{ lead.interested_service || 'N/A' }}
                  </td>
                  <td class="py-3 px-4">
                    <span 
                      :class="{
                        'bg-rose-100 text-rose-800 font-bold': lead.status === '1_new',
                        'bg-amber-100 text-amber-800 font-bold': lead.status === '2_acknowledged',
                        'bg-cyan-100 text-cyan-800 font-bold': lead.status === '3_in_progress',
                        'bg-indigo-100 text-indigo-800 font-bold': lead.status === '4_quoted',
                        'bg-emerald-100 text-emerald-800 font-bold': lead.status === '5_closed_won',
                        'bg-slate-200 text-slate-800 font-bold': lead.status === '5_closed_lost',
                      }"
                      class="px-2 py-1 rounded-md text-[10px] whitespace-nowrap"
                    >
                      {{ statusLabels[lead.status] || lead.status }}
                    </span>
                  </td>
                  <td class="py-3 px-4 text-slate-500 font-mono text-[11px]">
                    {{ lead.sla_due_at ? lead.sla_due_at.substring(11, 16) : 'N/A' }}
                  </td>
                  <td class="py-3 px-4 text-slate-700">
                    {{ lead.assignee?.name || 'Chưa nhận' }}
                  </td>
                  <td class="py-3 px-4 text-right">
                    <Link 
                      :href="'/admin/leads/' + lead.id"
                      class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-[#0F294A] text-white font-bold hover:bg-[#0284C7] transition text-[11px] shadow-sm"
                    >
                      <span>Chi Tiết & Xử Lý</span>
                      <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                      </svg>
                    </Link>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>

      </div>

      <!-- ======================================================== -->
      <!-- TAB 3: VISUAL CONTENT & PRODUCT CMS -->
      <!-- ======================================================== -->
      <div v-else-if="activeTab === 'products'" class="space-y-6">
        
        <!-- Header & Top Overview -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
          <div>
            <h1 class="text-2xl font-black text-[#0F294A]">Hệ Thống Quản Trị Nội Dung Trực Quan (Zero-Code CMS)</h1>
            <p class="text-xs text-slate-500 mt-1">
              Quản trị toàn diện nội dung từng trang (Trang chủ, Cloud, Internet, IoT, Bluecom, Kỹ thuật, Về Toàn Cầu), 6 Trụ cột, Dự án tiêu biểu, Gói VPS và Sản phẩm mà không cần chạm vào code.
            </p>
          </div>
        </div>

        <!-- Sub-Navigation Bar (6 Sub-Tabs) -->
        <div class="flex items-center gap-2 overflow-x-auto pb-2 border-b border-slate-200">
          <button 
            type="button" 
            @click="contentSubTab = 'pages'"
            :class="contentSubTab === 'pages' ? 'bg-[#0F294A] text-white shadow' : 'bg-white text-slate-700 hover:bg-slate-100 border border-slate-200'"
            class="px-4 py-2.5 rounded-xl text-xs font-bold transition flex items-center gap-2 shrink-0"
          >
            <span>📝 Quản Lý Từng Trang (Zero-Code CMS)</span>
          </button>

          <button 
            type="button" 
            @click="contentSubTab = 'sectors'"
            :class="contentSubTab === 'sectors' ? 'bg-[#0F294A] text-white shadow' : 'bg-white text-slate-700 hover:bg-slate-100 border border-slate-200'"
            class="px-4 py-2.5 rounded-xl text-xs font-bold transition flex items-center gap-2 shrink-0"
          >
            <span>🏛️ 6 Trụ Cột & Danh Mục</span>
            <span class="bg-cyan-100 text-[#0284C7] px-1.5 py-0.2 rounded-full text-[10px]">{{ categories.length }}</span>
          </button>

          <button 
            type="button" 
            @click="contentSubTab = 'projects'"
            :class="contentSubTab === 'projects' ? 'bg-[#0F294A] text-white shadow' : 'bg-white text-slate-700 hover:bg-slate-100 border border-slate-200'"
            class="px-4 py-2.5 rounded-xl text-xs font-bold transition flex items-center gap-2 shrink-0"
          >
            <span>🏢 Dự Án Tiêu Biểu</span>
            <span class="bg-cyan-100 text-[#0284C7] px-1.5 py-0.2 rounded-full text-[10px]">{{ projects.length }}</span>
          </button>

          <button 
            type="button" 
            @click="contentSubTab = 'vps'"
            :class="contentSubTab === 'vps' ? 'bg-[#0F294A] text-white shadow' : 'bg-white text-slate-700 hover:bg-slate-100 border border-slate-200'"
            class="px-4 py-2.5 rounded-xl text-xs font-bold transition flex items-center gap-2 shrink-0"
          >
            <span>⚡ Gói Cloud VPS & So Sánh</span>
            <span class="bg-cyan-100 text-[#0284C7] px-1.5 py-0.2 rounded-full text-[10px]">{{ vpsPlans.length }}</span>
          </button>

          <button 
            type="button" 
            @click="contentSubTab = 'products'"
            :class="contentSubTab === 'products' ? 'bg-[#0F294A] text-white shadow' : 'bg-white text-slate-700 hover:bg-slate-100 border border-slate-200'"
            class="px-4 py-2.5 rounded-xl text-xs font-bold transition flex items-center gap-2 shrink-0"
          >
            <span>📦 Sản Phẩm Chuẩn 11 Mục</span>
            <span class="bg-cyan-100 text-[#0284C7] px-1.5 py-0.2 rounded-full text-[10px]">{{ products.total || products.data?.length || 0 }}</span>
          </button>

          <button 
            type="button" 
            @click="contentSubTab = 'posts'"
            :class="contentSubTab === 'posts' ? 'bg-[#0F294A] text-white shadow' : 'bg-white text-slate-700 hover:bg-slate-100 border border-slate-200'"
            class="px-4 py-2.5 rounded-xl text-xs font-bold transition flex items-center gap-2 shrink-0"
          >
            <span>📰 Tin Tức & Kiến Thức</span>
            <span class="bg-cyan-100 text-[#0284C7] px-1.5 py-0.2 rounded-full text-[10px]">{{ posts.length }}</span>
          </button>
        </div>

        <!-- -------------------------------------------------------- -->
        <!-- SUB-TAB 1: PAGES ZERO-CODE CMS (Quản Lý Từng Trang)       -->
        <!-- -------------------------------------------------------- -->
        <div v-if="contentSubTab === 'pages'" class="space-y-6">
          <div class="bg-white rounded-3xl border border-slate-200 p-6 shadow-sm space-y-4">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
              <div>
                <h2 class="text-lg font-bold text-[#0F294A]">Chọn Trang Cần Quản Trị Nội Dung Trực Quan:</h2>
                <p class="text-xs text-slate-500 mt-0.5">Click vào từng trang bên dưới để xem và chỉnh sửa tiêu đề, mô tả, danh sách thẻ và bước quy trình.</p>
              </div>
            </div>

            <!-- Page Selection Pills -->
            <div class="flex items-center gap-2 overflow-x-auto pb-2">
              <button 
                v-for="p in pageOptions" 
                :key="p.key"
                @click="selectedPageKey = p.key"
                :class="selectedPageKey === p.key ? 'bg-[#0284C7] text-white font-bold shadow' : 'bg-slate-100 text-slate-700 hover:bg-slate-200 font-medium'"
                class="px-4 py-2.5 rounded-xl text-xs transition whitespace-nowrap flex flex-col text-left"
              >
                <span>{{ p.label }}</span>
                <span :class="selectedPageKey === p.key ? 'text-cyan-100' : 'text-slate-400'" class="text-[10px] font-normal">{{ p.desc }}</span>
              </button>
            </div>
          </div>

          <!-- Section Cards Grid for Selected Page -->
          <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div 
              v-for="sec in (pageSections[selectedPageKey] || [])" 
              :key="sec.id"
              class="bg-white rounded-3xl border border-slate-200 p-6 shadow-sm flex flex-col justify-between space-y-4 hover:border-cyan-300 transition"
            >
              <div>
                <div class="flex items-center justify-between gap-2 mb-2">
                  <span class="text-[10px] font-bold uppercase tracking-wider text-[#0284C7] bg-cyan-50 px-2.5 py-0.5 rounded-full border border-cyan-100">
                    {{ sec.badge || sec.section_key }}
                  </span>
                  <span class="text-[10px] font-mono font-bold text-slate-400">
                    Mã mục: {{ sec.section_key }}
                  </span>
                </div>

                <h3 class="text-base font-bold text-slate-900 leading-snug">
                  {{ sec.title }}
                </h3>
                <p v-if="sec.subtitle" class="text-xs font-semibold text-slate-500 mt-1">
                  {{ sec.subtitle }}
                </p>

                <p class="text-xs text-slate-600 mt-3 leading-relaxed bg-slate-50 p-3 rounded-xl border border-slate-100">
                  {{ sec.content || 'Chưa có đoạn văn mô tả.' }}
                </p>

                <!-- Metadata preview tags -->
                <div v-if="sec.metadata" class="mt-3 text-[11px] text-slate-600 bg-slate-100/70 p-3 rounded-xl border border-slate-200 space-y-1">
                  <div class="font-bold text-slate-800 text-[11px]">Dữ liệu cấu trúc (Tự động hiển thị thẻ/bước):</div>
                  <div v-if="sec.metadata.bullets" class="text-slate-600 truncate">• {{ sec.metadata.bullets.length }} gạch đầu dòng tính năng</div>
                  <div v-if="sec.metadata.cards" class="text-slate-600 truncate">• {{ sec.metadata.cards.length }} thẻ giải pháp (Cards)</div>
                  <div v-if="sec.metadata.steps" class="text-slate-600 truncate">• {{ sec.metadata.steps.length }} bước quy trình vận hành</div>
                  <div v-if="sec.metadata.items" class="text-slate-600 truncate">• {{ sec.metadata.items.length }} hạng mục công trình</div>
                  <div v-if="sec.metadata.pillars" class="text-slate-600 truncate">• {{ sec.metadata.pillars.length }} trụ cột thế mạnh năng lực</div>
                  <div v-if="sec.metadata.warning_note" class="text-amber-700 truncate">⚠️ Lưu ý an ninh: {{ sec.metadata.warning_note }}</div>
                  <div v-if="sec.metadata.compliance_note" class="text-cyan-700 truncate">ℹ️ Tuân thủ: {{ sec.metadata.compliance_note }}</div>
                </div>
              </div>

              <div class="pt-3 border-t border-slate-100 flex items-center justify-between gap-3">
                <button 
                  type="button" 
                  :disabled="auth.is_viewer"
                  @click="openEditSection(sec)"
                  class="px-4 py-2 bg-[#0284C7] hover:bg-cyan-700 text-white rounded-xl text-xs font-bold transition shadow disabled:opacity-50 flex items-center gap-1.5"
                >
                  <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/>
                  </svg>
                  <span>Sửa Nội Dung Trực Quan</span>
                </button>

                <a 
                  :href="selectedPageKey === 'home' ? '/' : selectedPageKey === 'cloud' ? '/cloud-infrastructure' : selectedPageKey === 'internet' ? '/internet-connectivity' : selectedPageKey === 'smart_iot' ? '/smart-iot' : selectedPageKey === 'bluecom' ? '/bluecom' : selectedPageKey === 'tech_services' ? '/technical-services' : '/ve-toan-cau'"
                  target="_blank"
                  class="text-xs font-bold text-slate-500 hover:text-[#0284C7] transition flex items-center gap-1"
                >
                  <span>Xem ngoài Web ↗</span>
                </a>
              </div>
            </div>
          </div>
        </div>

        <!-- -------------------------------------------------------- -->
        <!-- SUB-TAB 2: SECTORS & CATEGORIES (6 Trụ Cột & Danh Mục)   -->
        <!-- -------------------------------------------------------- -->
        <div v-else-if="contentSubTab === 'sectors'" class="space-y-6">
          <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
              <h2 class="text-xl font-bold text-[#0F294A]">Hệ Sinh Thái 6 Trụ Cột & Danh Mục Sản Phẩm</h2>
              <p class="text-xs text-slate-500 mt-1">Quản lý thêm, sửa, xóa các danh mục Smart IoT, Cloud, Internet, Bluecom và Dịch vụ kỹ thuật hiển thị ngoài website.</p>
            </div>

            <div v-if="!auth.is_viewer" class="flex flex-wrap items-center gap-2">
              <button 
                type="button" 
                @click="openCreateCategory('smart_iot')"
                class="px-4 py-2.5 rounded-xl bg-cyan-600 hover:bg-cyan-700 text-white text-xs font-bold transition flex items-center gap-1.5 shadow"
              >
                <span>📡 + Thêm Danh Mục Smart IoT</span>
              </button>
              <button 
                type="button" 
                @click="openCreateCategory()"
                class="px-4 py-2.5 rounded-xl bg-[#0F294A] hover:bg-slate-800 text-white text-xs font-bold transition flex items-center gap-1.5 shadow"
              >
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                <span>+ Thêm Danh Mục / Trụ Cột Khác</span>
              </button>
            </div>
          </div>

          <!-- Sector Filter Bar -->
          <div class="flex space-x-2 overflow-x-auto pb-2 text-xs font-bold">
            <button 
              type="button" 
              @click="selectedSectorFilter = 'all'"
              :class="selectedSectorFilter === 'all' ? 'bg-[#0F294A] text-white shadow' : 'bg-white text-slate-700 hover:bg-slate-100 border border-slate-200'"
              class="px-3.5 py-2 rounded-xl transition whitespace-nowrap"
            >
              Tất Cả ({{ categories.length }})
            </button>
            <button 
              type="button" 
              @click="selectedSectorFilter = 'smart_iot'"
              :class="selectedSectorFilter === 'smart_iot' ? 'bg-cyan-600 text-white shadow' : 'bg-white text-slate-700 hover:bg-slate-100 border border-slate-200'"
              class="px-3.5 py-2 rounded-xl transition whitespace-nowrap flex items-center gap-1"
            >
              <span>📡 Smart IoT</span>
              <span class="text-[10px] px-1.5 py-0.5 rounded-full bg-black/10">
                {{ categories.filter(c => c.sector_code === 'smart_iot').length }}
              </span>
            </button>
            <button 
              type="button" 
              @click="selectedSectorFilter = 'cloud_infra'"
              :class="selectedSectorFilter === 'cloud_infra' ? 'bg-[#0F294A] text-white shadow' : 'bg-white text-slate-700 hover:bg-slate-100 border border-slate-200'"
              class="px-3.5 py-2 rounded-xl transition whitespace-nowrap"
            >
              ☁️ Cloud & Dedicated ({{ categories.filter(c => c.sector_code === 'cloud_infra').length }})
            </button>
            <button 
              type="button" 
              @click="selectedSectorFilter = 'internet'"
              :class="selectedSectorFilter === 'internet' ? 'bg-[#0F294A] text-white shadow' : 'bg-white text-slate-700 hover:bg-slate-100 border border-slate-200'"
              class="px-3.5 py-2 rounded-xl transition whitespace-nowrap"
            >
              🌐 Internet & Leased Line ({{ categories.filter(c => c.sector_code === 'internet').length }})
            </button>
            <button 
              type="button" 
              @click="selectedSectorFilter = 'bluecom'"
              :class="selectedSectorFilter === 'bluecom' ? 'bg-[#0F294A] text-white shadow' : 'bg-white text-slate-700 hover:bg-slate-100 border border-slate-200'"
              class="px-3.5 py-2 rounded-xl transition whitespace-nowrap"
            >
              📢 Bluecom 4G ({{ categories.filter(c => c.sector_code === 'bluecom').length }})
            </button>
            <button 
              type="button" 
              @click="selectedSectorFilter = 'tech_services'"
              :class="selectedSectorFilter === 'tech_services' ? 'bg-[#0F294A] text-white shadow' : 'bg-white text-slate-700 hover:bg-slate-100 border border-slate-200'"
              class="px-3.5 py-2 rounded-xl transition whitespace-nowrap"
            >
              🛠️ Dịch Vụ Kỹ Thuật ({{ categories.filter(c => c.sector_code === 'tech_services').length }})
            </button>
          </div>

          <div class="bg-white rounded-3xl border border-slate-200 overflow-hidden shadow-sm">
            <div class="overflow-x-auto">
              <table class="w-full text-left text-xs border-collapse">
                <thead class="bg-slate-50 text-slate-500 font-bold uppercase text-[10px] border-b border-slate-200">
                  <tr>
                    <th class="py-3 px-4">Thứ Tự</th>
                    <th class="py-3 px-4">Tên Danh Mục / Phân Mục</th>
                    <th class="py-3 px-4">Cấp Bậc & Danh Mục Cha</th>
                    <th class="py-3 px-4">Lĩnh Vực</th>
                    <th class="py-3 px-4 text-center">Sản Phẩm</th>
                    <th class="py-3 px-4 text-center">Trạng Thái</th>
                    <th class="py-3 px-4 text-right">Thao Tác</th>
                  </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                  <tr v-for="cat in filteredCategories" :key="cat.id" class="hover:bg-slate-50 transition">
                    <td class="py-3 px-4 font-mono font-bold text-slate-400">
                      {{ cat.display_order }}
                    </td>
                    <td class="py-3 px-4">
                      <div class="font-bold text-slate-900 flex items-center gap-1.5">
                        <span v-if="cat.parent_id" class="text-slate-400 text-xs font-mono">↳</span>
                        <span>{{ cat.name }}</span>
                      </div>
                      <a 
                        :href="getCategoryPublicUrl(cat)" 
                        target="_blank" 
                        class="text-slate-400 hover:text-[#0284C7] text-[10px] font-mono transition inline-flex items-center gap-1 group"
                        title="Xem trang thực tế"
                      >
                        <span>/{{ cat.slug }}</span>
                        <span class="opacity-0 group-hover:opacity-100 transition-opacity">↗</span>
                      </a>
                    </td>
                    <td class="py-3 px-4">
                      <span v-if="!cat.parent_id" class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-indigo-50 text-indigo-700 border border-indigo-200">
                        🏛️ Trụ Cột Gốc
                      </span>
                      <span v-else class="px-2 py-0.5 rounded-full text-[10px] font-semibold bg-slate-100 text-slate-700">
                        ↳ Thuộc: {{ cat.parent?.name || 'Gốc' }}
                      </span>
                    </td>
                    <td class="py-3 px-4 font-mono text-[#0284C7] font-semibold">
                      {{ cat.sector_code }}
                    </td>
                    <td class="py-3 px-4 text-center">
                      <span class="px-2 py-0.5 rounded-full text-[10px] font-bold" :class="cat.products_count > 0 ? 'bg-cyan-100 text-cyan-800' : 'bg-slate-100 text-slate-500'">
                        {{ cat.products_count || 0 }} SP
                      </span>
                    </td>
                    <td class="py-3 px-4 text-center">
                      <span :class="cat.is_active ? 'bg-emerald-50 text-emerald-700' : 'bg-slate-100 text-slate-400'" class="px-2 py-0.5 rounded text-[10px] font-bold">
                        {{ cat.is_active ? 'Kích hoạt' : 'Ẩn' }}
                      </span>
                    </td>
                    <td class="py-3 px-4 text-right whitespace-nowrap">
                      <a 
                        :href="getCategoryPublicUrl(cat)" 
                        target="_blank" 
                        class="px-2 py-1 text-slate-500 hover:text-[#0284C7] font-bold inline-block text-xs"
                      >
                        Xem Web ↗
                      </a>
                      <button 
                        :disabled="auth.is_viewer"
                        @click="openEditCategory(cat)"
                        class="px-2.5 py-1 text-[#0284C7] hover:underline font-bold disabled:opacity-50"
                      >
                        Sửa
                      </button>
                      <button 
                        v-if="!isSystemRootCategory(cat)"
                        :disabled="auth.is_viewer"
                        @click="deleteCategory(cat)"
                        class="px-2.5 py-1 text-rose-600 hover:underline font-bold disabled:opacity-50"
                      >
                        Xóa
                      </button>
                      <span v-else class="px-2.5 py-1 text-slate-400 text-[10px] italic" title="Trụ cột gốc hệ thống">
                        (Trụ cột gốc)
                      </span>
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>

        <!-- -------------------------------------------------------- -->
        <!-- SUB-TAB 3: PROJECTS & CLIENTS (Dự Án Tiêu Biểu)           -->
        <!-- -------------------------------------------------------- -->
        <div v-else-if="contentSubTab === 'projects'" class="space-y-6">
          <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
              <h2 class="text-xl font-bold text-[#0F294A]">Quản Trị Dự Án & Khách Hàng Tiêu Biểu</h2>
              <p class="text-xs text-slate-500 mt-1">Thêm hồ sơ dự án thực tế, upload ảnh công trình WebP và chọn hiển thị nổi bật trên Trang Chủ.</p>
            </div>

            <button 
              v-if="!auth.is_viewer"
              type="button" 
              @click="openCreateProject"
              class="px-4 py-2.5 rounded-xl bg-[#0284C7] hover:bg-cyan-700 text-white text-xs font-bold transition flex items-center gap-2 shadow"
            >
              <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
              </svg>
              <span>+ Thêm Dự Án Mới</span>
            </button>
          </div>

          <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <div 
              v-for="proj in projects" 
              :key="proj.id"
              class="bg-white rounded-3xl border border-slate-200 overflow-hidden shadow-sm flex flex-col justify-between hover:border-cyan-300 transition"
            >
              <div>
                <div class="h-40 bg-slate-100 relative overflow-hidden flex items-center justify-center">
                  <img :src="proj.image_url" :alt="proj.title" class="w-full h-full object-cover" />
                  <span 
                    :class="proj.is_featured ? 'bg-[#EA580C] text-white' : 'bg-slate-800/80 text-slate-300'"
                    class="absolute top-3 right-3 px-2.5 py-0.5 rounded-full text-[10px] font-bold"
                  >
                    {{ proj.is_featured ? '★ Nổi Bật Trang Chủ' : 'Bình Thường' }}
                  </span>
                </div>

                <div class="p-5 space-y-2">
                  <div class="text-[10px] font-bold uppercase tracking-wider text-[#0284C7] font-mono">
                    {{ proj.sector_code }}
                  </div>
                  <h3 class="font-bold text-sm text-slate-900 leading-snug">
                    {{ proj.title }}
                  </h3>
                  <div class="text-xs font-semibold text-slate-700 flex items-center gap-1.5">
                    <span class="text-slate-400">Khách hàng:</span>
                    <span>{{ proj.client_name }}</span>
                  </div>
                  <p class="text-xs text-slate-500 leading-relaxed line-clamp-3">
                    {{ proj.description }}
                  </p>
                </div>
              </div>

              <div class="p-4 bg-slate-50 border-t border-slate-100 flex items-center justify-between gap-2">
                <button 
                  :disabled="auth.is_viewer"
                  @click="toggleProjectFeatured(proj)"
                  :class="proj.is_featured ? 'bg-amber-100 text-amber-800' : 'bg-slate-200 text-slate-700'"
                  class="px-2.5 py-1 rounded-lg text-[10px] font-bold transition disabled:opacity-50"
                >
                  {{ proj.is_featured ? 'Bỏ Nổi Bật' : 'Gắn Nổi Bật' }}
                </button>

                <div class="flex items-center gap-2">
                  <button 
                    :disabled="auth.is_viewer"
                    @click="openEditProject(proj)"
                    class="px-3 py-1 bg-white border border-slate-300 rounded-lg text-xs font-bold text-slate-700 hover:bg-slate-100 disabled:opacity-50"
                  >
                    Sửa
                  </button>
                  <button 
                    :disabled="auth.is_viewer"
                    @click="deleteProject(proj)"
                    class="px-3 py-1 bg-rose-50 border border-rose-200 rounded-lg text-xs font-bold text-rose-600 hover:bg-rose-100 disabled:opacity-50"
                  >
                    Xóa
                  </button>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- -------------------------------------------------------- -->
        <!-- SUB-TAB 4: CLOUD VPS PLANS (Gói VPS & Ma Trận So Sánh)     -->
        <!-- -------------------------------------------------------- -->
        <div v-else-if="contentSubTab === 'vps'" class="space-y-6">
          <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
              <h2 class="text-xl font-bold text-[#0F294A]">Quản Trị Gói Cấu Hình Cloud VPS & Bảng So Sánh B2B</h2>
              <p class="text-xs text-slate-500 mt-1">Tùy biến vCPU, RAM, ổ cứng NVMe, giá tháng/năm, chiết khấu và danh sách tính năng phục vụ Modal so sánh.</p>
            </div>

            <button 
              v-if="!auth.is_viewer"
              type="button" 
              @click="openCreateVps"
              class="px-4 py-2.5 rounded-xl bg-[#0284C7] hover:bg-cyan-700 text-white text-xs font-bold transition flex items-center gap-2 shadow"
            >
              <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
              </svg>
              <span>+ Thêm Gói Cloud VPS</span>
            </button>
          </div>

          <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <div 
              v-for="plan in vpsPlans" 
              :key="plan.id"
              class="bg-white rounded-3xl border border-slate-200 p-6 shadow-sm flex flex-col justify-between space-y-4 hover:border-cyan-300 transition relative"
            >
              <div>
                <span 
                  v-if="plan.is_popular" 
                  class="absolute top-4 right-4 bg-[#EA580C] text-white text-[10px] font-bold px-2.5 py-0.5 rounded-full shadow"
                >
                  Phổ Biến Nhất
                </span>

                <div class="font-bold text-base text-slate-900 pr-16">{{ plan.name }}</div>
                <div class="font-mono text-xs text-slate-400 mt-0.5">{{ plan.slug }}</div>

                <div class="mt-4 p-3 rounded-2xl bg-slate-50 border border-slate-100 space-y-1.5 text-xs">
                  <div class="flex justify-between">
                    <span class="text-slate-500">vCPU:</span>
                    <strong class="font-bold text-slate-800">{{ plan.vcpu }} Cores</strong>
                  </div>
                  <div class="flex justify-between">
                    <span class="text-slate-500">RAM ECC:</span>
                    <strong class="font-bold text-slate-800">{{ plan.ram_gb }} GB</strong>
                  </div>
                  <div class="flex justify-between">
                    <span class="text-slate-500">NVMe U.2:</span>
                    <strong class="font-bold text-slate-800">{{ plan.storage_nvme_gb }} GB</strong>
                  </div>
                  <div class="flex justify-between">
                    <span class="text-slate-500">IPv4 tĩnh:</span>
                    <strong class="font-bold text-slate-800">{{ plan.ipv4_count }} IP sạch</strong>
                  </div>
                </div>

                <div class="mt-4 space-y-1">
                  <div class="text-xs text-slate-400">Giá theo tháng:</div>
                  <div class="text-xl font-black text-[#0F294A]">{{ formatVnd(plan.price_monthly) }} <span class="text-xs font-normal text-slate-500">/tháng</span></div>
                  <div class="text-[11px] text-emerald-600 font-semibold">
                    Thuê năm: {{ formatVnd(plan.price_yearly) }} (-{{ plan.discount_percent_yearly }}%)
                  </div>
                </div>

                <!-- Features list preview -->
                <div class="mt-4 pt-3 border-t border-slate-100 text-[11px] text-slate-600 space-y-1">
                  <div v-for="(feat, fIdx) in (plan.features || []).slice(0, 4)" :key="fIdx" class="truncate">
                    ✓ {{ feat }}
                  </div>
                  <div v-if="(plan.features || []).length > 4" class="text-slate-400 text-[10px]">
                    +{{ plan.features.length - 4 }} tính năng khác...
                  </div>
                </div>
              </div>

              <div class="pt-3 border-t border-slate-100 flex items-center justify-between gap-2">
                <button 
                  :disabled="auth.is_viewer"
                  @click="openEditVps(plan)"
                  class="w-full py-2 bg-slate-100 hover:bg-[#0284C7] hover:text-white rounded-xl text-xs font-bold text-slate-700 transition disabled:opacity-50 text-center"
                >
                  Sửa Cấu Hình
                </button>
                <button 
                  :disabled="auth.is_viewer"
                  @click="deleteVps(plan)"
                  class="py-2 px-3 bg-rose-50 hover:bg-rose-100 text-rose-600 rounded-xl text-xs font-bold transition disabled:opacity-50"
                >
                  Xóa
                </button>
              </div>
            </div>
          </div>
        </div>

        <!-- -------------------------------------------------------- -->
        <!-- SUB-TAB 5: PRODUCTS (Chuẩn 11 Thành Phần)                 -->
        <!-- -------------------------------------------------------- -->
        <div v-else-if="contentSubTab === 'products'" class="space-y-6">
          <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
              <h2 class="text-xl font-bold text-[#0F294A]">Danh Mục Sản Phẩm & Thiết Bị (Chuẩn 11 Thành Phần)</h2>
              <p class="text-xs text-slate-500 mt-1">Cấu hình chi tiết 11 thành phần: Model, Datasheet PDF, Specs JSON, Chính sách bảo hành & Toggle giá B2B.</p>
            </div>

            <Link 
              v-if="!auth.is_viewer"
              href="/admin/products/create"
              class="px-4 py-2.5 rounded-xl bg-[#0284C7] hover:bg-cyan-700 text-white text-xs font-bold transition flex items-center gap-2 shadow"
            >
              <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
              </svg>
              <span>+ Thêm Sản Phẩm 11 Mục</span>
            </Link>
          </div>

          <!-- Products Table -->
          <div class="bg-white rounded-3xl border border-slate-200 overflow-hidden shadow-sm">
            <div class="overflow-x-auto">
              <table class="w-full text-left text-xs border-collapse">
                <thead class="bg-slate-50 text-slate-500 font-bold uppercase text-[10px] border-b border-slate-200">
                  <tr>
                    <th class="py-3 px-4">Mã Model [02]</th>
                    <th class="py-3 px-4">Tên Sản Phẩm / Dịch Vụ</th>
                    <th class="py-3 px-4">Lĩnh Vực</th>
                    <th class="py-3 px-4">Giá Niêm Yết [11]</th>
                    <th class="py-3 px-4 text-center">Toggle Giá B2B [11]</th>
                    <th class="py-3 px-4 text-center">Nổi Bật Trang Chủ</th>
                    <th class="py-3 px-4 text-center">Trạng Thái</th>
                    <th class="py-3 px-4 text-right">Thao Tác</th>
                  </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                  <tr v-for="prod in products.data" :key="prod.id" class="hover:bg-slate-50 transition">
                    <td class="py-3 px-4 font-mono font-bold text-[#0F294A]">
                      {{ prod.model_code }}
                    </td>
                    <td class="py-3 px-4">
                      <div class="font-bold text-slate-900">{{ prod.name }}</div>
                      <div class="text-slate-400 text-[10px]">{{ prod.slug }}</div>
                    </td>
                    <td class="py-3 px-4 text-slate-600">
                      {{ prod.sector_code }}
                    </td>
                    <td class="py-3 px-4 font-bold text-slate-900">
                      {{ formatVnd(prod.price) }}
                    </td>
                    <td class="py-3 px-4 text-center">
                      <button 
                        :disabled="auth.is_viewer"
                        @click="toggleProductPrice(prod)" 
                        :class="prod.is_price_visible ? 'bg-emerald-100 text-emerald-800' : 'bg-orange-100 text-[#EA580C]'"
                        class="px-2.5 py-1 rounded-full text-[10px] font-bold transition disabled:opacity-50"
                      >
                        {{ prod.is_price_visible ? 'Hiện Giá' : 'Nút "Liên hệ"' }}
                      </button>
                    </td>
                    <td class="py-3 px-4 text-center">
                      <button 
                        :disabled="auth.is_viewer"
                        @click="toggleProductFeatured(prod)" 
                        :class="prod.is_featured ? 'bg-cyan-100 text-[#0284C7]' : 'bg-slate-100 text-slate-400'"
                        class="px-2.5 py-1 rounded-full text-[10px] font-bold transition disabled:opacity-50"
                      >
                        {{ prod.is_featured ? '★ Nổi bật' : 'Bình thường' }}
                      </button>
                    </td>
                    <td class="py-3 px-4 text-center">
                      <button 
                        :disabled="auth.is_viewer"
                        @click="toggleProductActive(prod)" 
                        :class="prod.is_active ? 'bg-emerald-50 text-emerald-600' : 'bg-rose-50 text-rose-600'"
                        class="px-2 py-0.5 rounded text-[10px] font-bold transition disabled:opacity-50"
                      >
                        {{ prod.is_active ? 'Kích hoạt' : 'Tạm ẩn' }}
                      </button>
                    </td>
                    <td class="py-3 px-4 text-right whitespace-nowrap space-x-1">
                      <Link 
                        v-if="!auth.is_viewer"
                        :href="'/admin/products/' + prod.id + '/edit'"
                        class="px-2.5 py-1 rounded-lg bg-cyan-50 hover:bg-cyan-100 text-[#0284C7] font-bold inline-block"
                      >
                        Sửa 11 Mục ↗
                      </Link>
                      <button 
                        :disabled="auth.is_viewer"
                        @click="deleteProduct(prod)"
                        class="px-2 py-1 text-rose-600 hover:underline font-bold disabled:opacity-50"
                      >
                        Xóa
                      </button>
                      <a :href="'/san-pham/' + prod.slug" target="_blank" class="px-2 py-1 text-slate-500 hover:text-[#0284C7] font-bold inline-block">
                        Xem Web ↗
                      </a>
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>

        <!-- -------------------------------------------------------- -->
        <!-- SUB-TAB 6: NEWS POSTS (Tin Tức & Kiến Thức Kỹ Thuật)      -->
        <!-- -------------------------------------------------------- -->
        <div v-else-if="contentSubTab === 'posts'" class="space-y-6">
          <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
              <h2 class="text-xl font-bold text-[#0F294A]">Quản Trị Tin Tức & Kiến Thức Công Nghệ</h2>
              <p class="text-xs text-slate-500 mt-1">Đăng tải bài viết chuyên môn, kiến thức viễn thông, hướng dẫn triển khai và thông cáo B2B.</p>
            </div>

            <button 
              v-if="!auth.is_viewer"
              type="button" 
              @click="openCreatePost"
              class="px-4 py-2.5 rounded-xl bg-[#0284C7] hover:bg-cyan-700 text-white text-xs font-bold transition flex items-center gap-2 shadow"
            >
              <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
              </svg>
              <span>+ Thêm Bài Viết Mới</span>
            </button>
          </div>

          <div class="bg-white rounded-3xl border border-slate-200 overflow-hidden shadow-sm">
            <div class="overflow-x-auto">
              <table class="w-full text-left text-xs border-collapse">
                <thead class="bg-slate-50 text-slate-500 font-bold uppercase text-[10px] border-b border-slate-200">
                  <tr>
                    <th class="py-3 px-4">Ảnh</th>
                    <th class="py-3 px-4">Tiêu Đề Bài Viết</th>
                    <th class="py-3 px-4">Tác Giả</th>
                    <th class="py-3 px-4">Ngày Xuất Bản</th>
                    <th class="py-3 px-4 text-center">Trạng Thái</th>
                    <th class="py-3 px-4 text-right">Thao Tác</th>
                  </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                  <tr v-for="post in posts" :key="post.id" class="hover:bg-slate-50 transition">
                    <td class="py-3 px-4">
                      <div class="w-12 h-10 rounded-lg bg-slate-100 overflow-hidden">
                        <img :src="post.thumbnail || '/images/news/datacenter.webp'" :alt="post.title" class="w-full h-full object-cover" />
                      </div>
                    </td>
                    <td class="py-3 px-4">
                      <div class="font-bold text-slate-900 max-w-md">{{ post.title }}</div>
                      <div class="text-slate-400 text-[10px] truncate max-w-sm">{{ post.summary }}</div>
                    </td>
                    <td class="py-3 px-4 text-slate-700">
                      {{ post.author_name || 'Toàn Cầu Tech' }}
                    </td>
                    <td class="py-3 px-4 font-mono text-slate-500">
                      {{ post.published_at ? post.published_at.substring(0, 10) : 'N/A' }}
                    </td>
                    <td class="py-3 px-4 text-center">
                      <span :class="post.is_published ? 'bg-emerald-50 text-emerald-700' : 'bg-slate-100 text-slate-400'" class="px-2 py-0.5 rounded text-[10px] font-bold">
                        {{ post.is_published ? 'Đã Xuất Bản' : 'Bản Nháp' }}
                      </span>
                    </td>
                    <td class="py-3 px-4 text-right whitespace-nowrap">
                      <button 
                        :disabled="auth.is_viewer"
                        @click="openEditPost(post)"
                        class="px-2.5 py-1 text-[#0284C7] hover:underline font-bold disabled:opacity-50"
                      >
                        Sửa
                      </button>
                      <button 
                        :disabled="auth.is_viewer"
                        @click="deletePost(post)"
                        class="px-2.5 py-1 text-rose-600 hover:underline font-bold disabled:opacity-50"
                      >
                        Xóa
                      </button>
                      <a :href="'/tin-tuc/' + post.slug" target="_blank" class="px-2.5 py-1 text-slate-500 hover:text-[#0284C7] font-bold">
                        Xem ↗
                      </a>
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>

      </div>

      <!-- ======================================================== -->
      <!-- TAB 4: COMPLIANCE & SETTINGS (Admin Only) -->
      <!-- ======================================================== -->
      <div v-else-if="activeTab === 'compliance' && auth.is_admin" class="space-y-8">
        
        <div class="bg-white rounded-3xl border border-slate-200 p-8 shadow-sm space-y-6">
          <div>
            <h2 class="text-xl font-bold text-[#0F294A]">6 Hạng Mục Phê Duyệt Pháp Lý Bắt Buộc</h2>
            <p class="text-xs text-slate-500 mt-1 leading-relaxed">
              Toàn Cầu Technology áp dụng cơ chế cờ duyệt: Thông tin chỉ được xuất hiện trên Public sau khi có phê duyệt chính thức từ Quản trị viên (Admin).
            </p>
          </div>

          <div class="space-y-4">
            <div 
              v-for="chk in complianceList" 
              :key="chk.id"
              class="p-5 rounded-2xl border transition-all flex flex-col lg:flex-row lg:items-center justify-between gap-5"
              :class="chk.is_verified ? 'bg-emerald-50/40 border-emerald-300 shadow-sm' : 'bg-slate-50 border-slate-200'"
            >
              <div class="space-y-2 flex-1">
                <div class="flex items-center gap-2.5">
                  <span :class="chk.is_verified ? 'bg-emerald-600 animate-pulse' : 'bg-slate-400'" class="w-2.5 h-2.5 rounded-full shrink-0"></span>
                  <h4 class="font-extrabold text-sm text-slate-900">{{ chk.title }}</h4>
                  <span 
                    :class="chk.is_verified ? 'bg-emerald-100 text-emerald-800' : 'bg-slate-200 text-slate-600'" 
                    class="px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wider"
                  >
                    {{ chk.is_verified ? '✓ ĐÃ PHÊ DUYỆT CÔNG BỐ' : 'ĐANG ẨN KHỎI PUBLIC' }}
                  </span>
                </div>

                <p class="text-xs text-slate-600 pl-5 leading-relaxed">{{ chk.description }}</p>

                <!-- Impact summary & Preview link -->
                <div class="pl-5 pt-1 text-xs space-y-1">
                  <div class="flex flex-wrap items-center gap-2">
                    <span class="font-bold text-slate-700">Trang áp dụng:</span>
                    <a 
                      v-if="complianceTargets[chk.item_key]" 
                      :href="complianceTargets[chk.item_key].url" 
                      target="_blank" 
                      class="text-[#0284C7] hover:underline font-semibold flex items-center gap-1"
                    >
                      <span>{{ complianceTargets[chk.item_key].name }} ({{ complianceTargets[chk.item_key].url }})</span>
                      <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                      </svg>
                    </a>
                  </div>

                  <div v-if="complianceTargets[chk.item_key]" class="text-[11px] p-2 rounded-lg bg-white/80 border border-slate-200/80">
                    <span class="text-slate-500 font-medium">Hiệu lực hiện tại: </span>
                    <strong :class="chk.is_verified ? 'text-emerald-700' : 'text-amber-700'">
                      {{ chk.is_verified ? complianceTargets[chk.item_key].onEffect : complianceTargets[chk.item_key].offEffect }}
                    </strong>
                  </div>
                </div>

                <div v-if="chk.verified_at" class="text-[10px] text-slate-400 pl-5 font-mono">
                  Duyệt gần nhất: {{ chk.verified_at }} bởi {{ chk.verifier?.name || 'Admin' }}
                </div>
              </div>

              <div class="flex flex-col sm:flex-row lg:flex-col items-end gap-2 shrink-0">
                <button 
                  type="button" 
                  @click="toggleCompliance(chk)"
                  :class="chk.is_verified ? 'bg-rose-50 text-rose-600 border-rose-300 hover:bg-rose-100' : 'bg-emerald-600 text-white hover:bg-emerald-700 shadow-sm'"
                  class="w-full sm:w-auto px-5 py-2.5 rounded-xl text-xs font-bold transition border"
                >
                  {{ chk.is_verified ? 'Hủy Duyệt (Chuyển Ẩn)' : 'Phê Duyệt Công Khai (Bật = 1)' }}
                </button>
                <a 
                  v-if="complianceTargets[chk.item_key]"
                  :href="complianceTargets[chk.item_key].url"
                  target="_blank"
                  class="text-[11px] text-slate-500 hover:text-[#0284C7] underline text-right"
                >
                  Mở trang xem kết quả ↗
                </a>
              </div>
            </div>
          </div>
        </div>

        <!-- GA4, GSC & System Configuration Form -->
        <div class="bg-white rounded-3xl border border-slate-200 p-8 shadow-sm space-y-6">
          <div>
            <h2 class="text-xl font-bold text-[#0F294A]">Cấu Hình Hệ Thống, GA4 & Search Console</h2>
            <p class="text-xs text-slate-500 mt-1">Cấu hình các thông số tiếp thị số và kênh hotline tiếp nhận lead.</p>
          </div>

          <form @submit.prevent="saveSettings" class="space-y-4 text-xs">
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
              <div>
                <label class="block font-bold text-slate-700 uppercase mb-1">Google Analytics 4 ID (GA4)</label>
                <input v-model="settingsForm.google_analytics_id" type="text" placeholder="G-XXXXXXXXXX" class="w-full rounded-xl border border-slate-300 py-2.5 px-3 text-xs outline-none" />
              </div>
              <div>
                <label class="block font-bold text-slate-700 uppercase mb-1">Google Search Console Verification Token</label>
                <input v-model="settingsForm.google_search_console_token" type="text" placeholder="google-site-verification token" class="w-full rounded-xl border border-slate-300 py-2.5 px-3 text-xs outline-none" />
              </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
              <div>
                <label class="block font-bold text-slate-700 uppercase mb-1">Số Điện Thoại Hotline 24/7</label>
                <input v-model="settingsForm.hotline" type="text" placeholder="1900 6868 - 028 7300 8888" class="w-full rounded-xl border border-slate-300 py-2.5 px-3 text-xs outline-none" />
              </div>
              <div>
                <label class="block font-bold text-slate-700 uppercase mb-1">Link Chat Zalo OA Doanh Nghiệp</label>
                <input v-model="settingsForm.zalo_oa_url" type="url" placeholder="https://zalo.me/toancautech" class="w-full rounded-xl border border-slate-300 py-2.5 px-3 text-xs outline-none" />
              </div>
            </div>

            <div>
              <label class="flex items-center gap-2 cursor-pointer font-bold text-slate-800">
                <input v-model="settingsForm.show_infra_stats_home" type="checkbox" class="rounded text-[#0284C7]" />
                <span>Bật / Tắt khối Năng Lực Công Nghệ & Hạ Tầng trên Trang Chủ</span>
              </label>
            </div>

            <div class="pt-2">
              <button type="submit" class="px-6 py-2.5 bg-[#0F294A] hover:bg-[#183F70] text-white font-bold rounded-xl shadow">
                Lưu Thay Đổi Cấu Hình
              </button>
            </div>
          </form>
        </div>

      </div>

    </main>

    <!-- LEAD DETAIL & STATUS UPDATE MODAL (Strictly 5 lifecycle states) -->
    <div v-if="isLeadDetailOpen && selectedLead" class="fixed inset-0 z-50 overflow-y-auto" role="dialog">
      <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm" @click="isLeadDetailOpen = false"></div>
      
      <div class="flex min-h-screen items-center justify-center p-4">
        <div class="relative bg-white rounded-3xl max-w-2xl w-full p-6 sm:p-8 shadow-2xl border border-slate-200 text-left space-y-6">
          
          <div class="flex items-center justify-between border-b border-slate-100 pb-4">
            <div>
              <span class="text-xs font-mono font-bold text-[#0284C7] bg-cyan-50 px-2.5 py-0.5 rounded">
                {{ selectedLead.code }}
              </span>
              <h3 class="text-lg font-bold text-slate-900 mt-1">
                Chi Tiết Lead: {{ selectedLead.full_name }}
              </h3>
            </div>
            <button @click="isLeadDetailOpen = false" class="text-slate-400 hover:text-slate-700">
              <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
              </svg>
            </button>
          </div>

          <!-- Customer info -->
          <div class="grid grid-cols-2 gap-4 text-xs bg-slate-50 p-4 rounded-2xl border border-slate-200">
            <div>
              <span class="text-slate-400">Doanh nghiệp:</span>
              <div class="font-bold text-slate-800">{{ selectedLead.company_name || 'N/A' }}</div>
            </div>
            <div>
              <span class="text-slate-400">Số điện thoại:</span>
              <div class="font-bold text-[#0284C7] font-mono">{{ selectedLead.phone }}</div>
            </div>
            <div>
              <span class="text-slate-400">Email:</span>
              <div class="font-bold text-slate-800">{{ selectedLead.email }}</div>
            </div>
            <div>
              <span class="text-slate-400">Địa bàn / Tỉnh thành:</span>
              <div class="font-bold text-slate-800">{{ selectedLead.city_province || 'N/A' }}</div>
            </div>
            <div class="col-span-2 pt-2 border-t border-slate-200">
              <span class="text-slate-400">Dịch vụ quan tâm:</span>
              <div class="font-bold text-slate-900">{{ selectedLead.interested_service || 'N/A' }}</div>
            </div>
            <div v-if="selectedLead.message" class="col-span-2">
              <span class="text-slate-400">Nội dung yêu cầu / Mô tả sự cố:</span>
              <p class="text-slate-700 mt-0.5">{{ selectedLead.message }}</p>
            </div>
            <!-- Secure Attachment Download Link -->
            <div v-if="selectedLead.attachment_path" class="col-span-2 pt-2 border-t border-slate-200">
              <span class="text-slate-400">Tệp đính kèm an toàn (Lưu trữ Private):</span>
              <div class="mt-1">
                <a 
                  :href="'/admin/leads/' + selectedLead.id + '/download'" 
                  class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-[#0F294A] text-white font-bold text-xs"
                >
                  <svg class="w-4 h-4 text-cyan-400" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm3.293-7.707a1 1 0 011.414 0L9 10.586V3a1 1 0 112 0v7.586l1.293-1.293a1 1 0 111.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z" clip-rule="evenodd"/>
                  </svg>
                  <span>Tải tệp đính kèm ({{ selectedLead.attachment_name || 'Tài liệu' }})</span>
                </a>
              </div>
            </div>
          </div>

          <!-- Status Update Form (5 States) -->
          <form v-if="!auth.is_viewer" @submit.prevent="submitStatusChange" class="space-y-4 text-xs">
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
              <div>
                <label class="block font-bold text-slate-700 uppercase mb-1">
                  Chuyển Trạng Thái Vòng Đời <span class="text-rose-500">*</span>
                </label>
                <select v-model="leadStatusForm.status" required class="w-full rounded-xl border border-slate-300 py-2.5 px-3 text-xs bg-white outline-none">
                  <option v-for="(lbl, val) in statusLabels" :key="val" :value="val">{{ lbl }}</option>
                </select>
              </div>

              <div>
                <label class="block font-bold text-slate-700 uppercase mb-1">Nhân Sự Phụ Trách</label>
                <select v-model="leadStatusForm.assigned_to" class="w-full rounded-xl border border-slate-300 py-2.5 px-3 text-xs bg-white outline-none">
                  <option value="">-- Chưa chỉ định --</option>
                  <option v-for="user in salesUsers" :key="user.id" :value="user.id">{{ user.name }} ({{ user.email }})</option>
                </select>
              </div>
            </div>

            <!-- Mandatory Reason if 5_closed_lost -->
            <div v-if="leadStatusForm.status === '5_closed_lost'">
              <label class="block font-bold text-rose-700 uppercase mb-1">
                Lý Do Thất Bại (Bắt buộc theo quy chuẩn CMS) <span class="text-rose-500">*</span>
              </label>
              <textarea 
                v-model="leadStatusForm.closed_reason" 
                required 
                rows="2" 
                placeholder="VD: Khách hàng không phản hồi, mục đích dùng IP không hợp pháp, vượt ngân sách..." 
                class="w-full rounded-xl border border-rose-300 py-2 px-3 text-xs outline-none bg-rose-50/30"
              ></textarea>
            </div>

            <div>
              <label class="block font-bold text-slate-700 uppercase mb-1">Ghi Chú Tiến Độ Tư Vấn</label>
              <input v-model="leadStatusForm.note" type="text" placeholder="Đã gọi điện lần 1, hẹn báo giá ngày..." class="w-full rounded-xl border border-slate-300 py-2 px-3 text-xs outline-none" />
            </div>

            <div class="flex justify-end gap-3 pt-2">
              <button type="button" @click="isLeadDetailOpen = false" class="px-4 py-2 border rounded-xl text-slate-700">Đóng</button>
              <button type="submit" class="px-5 py-2 bg-[#EA580C] hover:bg-[#C2410C] text-white font-bold rounded-xl shadow">
                Cập Nhật Trạng Thái
              </button>
            </div>
          </form>

          <!-- Timeline Notes History -->
          <div class="pt-4 border-t border-slate-100">
            <h4 class="text-xs font-bold uppercase text-slate-500 tracking-wider mb-3">Lịch Sử Tương Tác & Tư Vấn</h4>
            <div class="space-y-2 max-h-40 overflow-y-auto pr-2">
              <div v-for="(note, nidx) in (selectedLead.notes || [])" :key="nidx" class="p-2.5 rounded-xl bg-slate-50 border border-slate-100 text-xs">
                <div class="flex items-center justify-between text-[10px] text-slate-400">
                  <span class="font-bold text-slate-700">{{ note.actor }}</span>
                  <span>{{ note.time ? note.time.substring(0, 19).replace('T', ' ') : '' }}</span>
                </div>
                <div class="text-slate-800 mt-1 leading-relaxed">{{ note.text }}</div>
              </div>
            </div>
          </div>

        </div>
      </div>
    </div>

    <!-- CREATE PRODUCT MODAL (11 Components) -->
    <div v-if="isCreateProductOpen" class="fixed inset-0 z-50 overflow-y-auto" role="dialog">
      <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm" @click="isCreateProductOpen = false"></div>
      
      <div class="flex min-h-screen items-center justify-center p-4">
        <div class="relative bg-white rounded-3xl max-w-3xl w-full p-6 sm:p-8 shadow-2xl border border-slate-200 text-left space-y-4">
          <div class="flex items-center justify-between border-b border-slate-100 pb-3">
            <h3 class="text-lg font-bold text-[#0F294A]">Thêm Mới Sản Phẩm B2B Chuẩn 11 Mục</h3>
            <button @click="isCreateProductOpen = false" class="text-slate-400 hover:text-slate-700">✕</button>
          </div>

          <form @submit.prevent="submitCreateProduct" class="space-y-4 text-xs">
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
              <div>
                <label class="block font-bold text-slate-700 uppercase mb-1">Tên Sản Phẩm / Dịch Vụ *</label>
                <input v-model="createProdForm.name" type="text" required placeholder="Máy chủ Dedicated Server..." class="w-full rounded-xl border border-slate-300 py-2 px-3 text-xs outline-none" />
              </div>
              <div>
                <label class="block font-bold text-slate-700 uppercase mb-1">Mã Model [02] *</label>
                <input v-model="createProdForm.model_code" type="text" required placeholder="TC-DED-EPYC7763" class="w-full rounded-xl border border-slate-300 py-2 px-3 text-xs outline-none font-mono" />
              </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
              <div>
                <label class="block font-bold text-slate-700 uppercase mb-1">Danh Mục Cấp *</label>
                <select v-model="createProdForm.category_id" required class="w-full rounded-xl border border-slate-300 py-2 px-3 text-xs bg-white outline-none">
                  <option v-for="cat in categories" :key="cat.id" :value="cat.id">{{ cat.parent_id ? '  ↳ ' + cat.name : '★ ' + cat.name }} ({{ cat.sector_code }})</option>
                </select>
              </div>
              <div>
                <label class="block font-bold text-slate-700 uppercase mb-1">Mã Lĩnh Vực</label>
                <select v-model="createProdForm.sector_code" class="w-full rounded-xl border border-slate-300 py-2 px-3 text-xs bg-white outline-none">
                  <option value="cloud_infra">cloud_infra</option>
                  <option value="internet">internet</option>
                  <option value="smart_iot">smart_iot</option>
                  <option value="bluecom">bluecom</option>
                  <option value="tech_services">tech_services</option>
                </select>
              </div>
            </div>

            <div>
              <label class="block font-bold text-slate-700 uppercase mb-1">Mô Tả Tổng Quan [04] (2-3 câu định vị B2B) *</label>
              <textarea v-model="createProdForm.overview" required rows="2" placeholder="Máy chủ chuyên dụng AMD EPYC đặt tại Data Center Tier 3..." class="w-full rounded-xl border border-slate-300 py-2 px-3 text-xs outline-none"></textarea>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
              <div>
                <label class="block font-bold text-slate-700 uppercase mb-1">Đơn Giá Niêm Yết [11]</label>
                <input v-model="createProdForm.price" type="number" placeholder="5800000" class="w-full rounded-xl border border-slate-300 py-2 px-3 text-xs outline-none" />
              </div>
              <div class="flex items-center gap-4 pt-6">
                <label class="flex items-center gap-2 cursor-pointer font-bold text-slate-800">
                  <input v-model="createProdForm.is_price_visible" type="checkbox" class="rounded text-[#0284C7]" />
                  <span>Hiển Thị Giá Niêm Yết</span>
                </label>
                <label class="flex items-center gap-2 cursor-pointer font-bold text-slate-800">
                  <input v-model="createProdForm.is_featured" type="checkbox" class="rounded text-[#0284C7]" />
                  <span>Nổi Bật Trang Chủ</span>
                </label>
              </div>
            </div>

            <div class="flex justify-end gap-3 pt-3 border-t">
              <button type="button" @click="isCreateProductOpen = false" class="px-4 py-2 border rounded-xl">Hủy</button>
              <button type="submit" class="px-5 py-2 bg-[#0284C7] text-white font-bold rounded-xl shadow">Lưu Sản Phẩm</button>
            </div>
          </form>
        </div>
      </div>
    </div>

    <!-- 1. PAGE SECTION ZERO-CODE MODAL -->
    <div v-if="isEditSectionOpen && selectedSection" class="fixed inset-0 z-50 overflow-y-auto" role="dialog">
      <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm" @click="isEditSectionOpen = false"></div>
      <div class="flex min-h-screen items-center justify-center p-4">
        <div class="relative bg-white rounded-3xl max-w-2xl w-full p-6 sm:p-8 shadow-2xl border border-slate-200 text-left space-y-4">
          <div class="flex items-center justify-between border-b border-slate-100 pb-3">
            <div>
              <span class="text-[10px] font-mono uppercase bg-cyan-50 text-[#0284C7] px-2 py-0.5 rounded font-bold">
                Trang: {{ selectedSection.page_key }} / Mục: {{ selectedSection.section_key }}
              </span>
              <h3 class="text-lg font-bold text-[#0F294A] mt-1">
                Chỉnh Sửa Nội Dung: {{ selectedSection.title }}
              </h3>
            </div>
            <button @click="isEditSectionOpen = false" class="text-slate-400 hover:text-slate-700">✕</button>
          </div>

          <form @submit.prevent="submitUpdateSection" class="space-y-4 text-xs">
            <div>
              <label class="block font-bold text-slate-700 uppercase mb-1">Tiêu Đề Chính *</label>
              <input v-model="sectionForm.title" type="text" required class="w-full rounded-xl border border-slate-300 py-2.5 px-3 text-xs outline-none focus:border-[#0284C7]" />
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
              <div>
                <label class="block font-bold text-slate-700 uppercase mb-1">Tiêu Đề Phụ</label>
                <input v-model="sectionForm.subtitle" type="text" class="w-full rounded-xl border border-slate-300 py-2 px-3 text-xs outline-none focus:border-[#0284C7]" />
              </div>
              <div>
                <label class="block font-bold text-slate-700 uppercase mb-1">Nhãn Huy Hiệu (Badge Pill)</label>
                <input v-model="sectionForm.badge" type="text" class="w-full rounded-xl border border-slate-300 py-2 px-3 text-xs outline-none focus:border-[#0284C7]" />
              </div>
            </div>

            <div>
              <label class="block font-bold text-slate-700 uppercase mb-1">Nội Dung Mô Tả Chi Tiết</label>
              <textarea v-model="sectionForm.content" rows="4" class="w-full rounded-xl border border-slate-300 py-2 px-3 text-xs outline-none focus:border-[#0284C7] leading-relaxed"></textarea>
            </div>

            <div>
              <div class="flex items-center justify-between mb-1">
                <label class="block font-bold text-slate-700 uppercase">Dữ Liệu Cấu Trúc (Thẻ, Bước, Ghi Chú, Danh Sách)</label>
                <span class="text-[10px] text-slate-400">Tự động parse JSON</span>
              </div>
              <textarea v-model="sectionForm.metadata_json" rows="6" class="w-full rounded-xl border border-slate-300 py-2 px-3 text-xs outline-none font-mono bg-slate-50 focus:border-[#0284C7]"></textarea>
              <p class="text-[10px] text-slate-400 mt-1">
                Lưu ý: Bạn có thể sửa trực tiếp các câu chữ tiếng Việt bên trong dấu ngoặc kép "" một cách an toàn mà không cần biết code.
              </p>
            </div>

            <div class="flex justify-end gap-3 pt-3 border-t">
              <button type="button" @click="isEditSectionOpen = false" class="px-4 py-2 border rounded-xl text-slate-700">Hủy</button>
              <button type="submit" class="px-5 py-2 bg-[#0284C7] hover:bg-cyan-700 text-white font-bold rounded-xl shadow">Lưu Thay Đổi (Cập Nhật Ngay)</button>
            </div>
          </form>
        </div>
      </div>
    </div>

    <!-- 2. CREATE CATEGORY MODAL -->
    <div v-if="isCreateCategoryOpen" class="fixed inset-0 z-50 overflow-y-auto" role="dialog">
      <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm" @click="isCreateCategoryOpen = false"></div>
      <div class="flex min-h-screen items-center justify-center p-4">
        <div class="relative bg-white rounded-3xl max-w-lg w-full p-6 sm:p-8 shadow-2xl border border-slate-200 text-left space-y-4">
          <div class="flex items-center justify-between border-b border-slate-100 pb-3">
            <h3 class="text-lg font-bold text-[#0F294A]">Thêm Mới Danh Mục / Phân Mục</h3>
            <button @click="isCreateCategoryOpen = false" class="text-slate-400 hover:text-slate-700">✕</button>
          </div>

          <form @submit.prevent="submitCreateCategory" class="space-y-4 text-xs">
            <div>
              <label class="block font-bold text-slate-700 uppercase mb-1">Tên Danh Mục / Phân Mục *</label>
              <input v-model="categoryForm.name" type="text" required placeholder="VD: Solar Camera Ngoài Trời, Cảm Biến Đất..." class="w-full rounded-xl border border-slate-300 py-2 px-3 text-xs outline-none focus:border-[#0284C7]" />
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
              <div>
                <label class="block font-bold text-slate-700 uppercase mb-1">Mã Lĩnh Vực Cốt Lõi *</label>
                <select v-model="categoryForm.sector_code" required class="w-full rounded-xl border border-slate-300 py-2 px-3 text-xs bg-white outline-none focus:border-[#0284C7]">
                  <option value="smart_iot">smart_iot (Smart IoT)</option>
                  <option value="cloud_infra">cloud_infra (Cloud & Hạ Tầng)</option>
                  <option value="internet">internet (Internet & Kết Nối)</option>
                  <option value="bluecom">bluecom (Bluecom 4G)</option>
                  <option value="tech_services">tech_services (Dịch Vụ Kỹ Thuật)</option>
                </select>
              </div>
              <div>
                <label class="block font-bold text-slate-700 uppercase mb-1">Danh Mục Cha (Tùy Chọn)</label>
                <select v-model="categoryForm.parent_id" class="w-full rounded-xl border border-slate-300 py-2 px-3 text-xs bg-white outline-none focus:border-[#0284C7]">
                  <option value="">-- Là Trụ Cột Gốc Độc Lập --</option>
                  <option v-for="root in rootCategories" :key="root.id" :value="root.id">
                    {{ root.name }} ({{ root.sector_code }})
                  </option>
                </select>
              </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
              <div>
                <label class="block font-bold text-slate-700 uppercase mb-1">Thứ Tự Hiển Thị</label>
                <input v-model="categoryForm.display_order" type="number" min="1" class="w-full rounded-xl border border-slate-300 py-2 px-3 text-xs outline-none focus:border-[#0284C7]" />
              </div>
              <div>
                <label class="block font-bold text-slate-700 uppercase mb-1">Icon Đại Diện</label>
                <input v-model="categoryForm.icon" type="text" placeholder="CpuChipIcon, ServerIcon, SunIcon..." class="w-full rounded-xl border border-slate-300 py-2 px-3 text-xs outline-none focus:border-[#0284C7]" />
              </div>
            </div>

            <div>
              <label class="block font-bold text-slate-700 uppercase mb-1">Mô Tả Tóm Tắt</label>
              <textarea v-model="categoryForm.description" rows="2" placeholder="Đoạn văn giới thiệu ngắn..." class="w-full rounded-xl border border-slate-300 py-2 px-3 text-xs outline-none focus:border-[#0284C7]"></textarea>
            </div>

            <div class="flex justify-end gap-3 pt-3 border-t">
              <button type="button" @click="isCreateCategoryOpen = false" class="px-4 py-2 border rounded-xl">Hủy</button>
              <button type="submit" class="px-5 py-2 bg-cyan-600 hover:bg-cyan-700 text-white font-bold rounded-xl shadow">Tạo Danh Mục</button>
            </div>
          </form>
        </div>
      </div>
    </div>

    <!-- 2B. EDIT CATEGORY MODAL -->
    <div v-if="isEditCategoryOpen && selectedCategory" class="fixed inset-0 z-50 overflow-y-auto" role="dialog">
      <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm" @click="isEditCategoryOpen = false"></div>
      <div class="flex min-h-screen items-center justify-center p-4">
        <div class="relative bg-white rounded-3xl max-w-lg w-full p-6 sm:p-8 shadow-2xl border border-slate-200 text-left space-y-4">
          <div class="flex items-center justify-between border-b border-slate-100 pb-3">
            <h3 class="text-lg font-bold text-[#0F294A]">Sửa Danh Mục: {{ selectedCategory.name }}</h3>
            <button @click="isEditCategoryOpen = false" class="text-slate-400 hover:text-slate-700">✕</button>
          </div>

          <form @submit.prevent="submitUpdateCategory" class="space-y-4 text-xs">
            <div>
              <label class="block font-bold text-slate-700 uppercase mb-1">Tên Danh Mục / Phân Mục *</label>
              <input v-model="categoryForm.name" type="text" required class="w-full rounded-xl border border-slate-300 py-2 px-3 text-xs outline-none focus:border-[#0284C7]" />
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
              <div>
                <label class="block font-bold text-slate-700 uppercase mb-1">Mã Lĩnh Vực Cốt Lõi *</label>
                <select v-model="categoryForm.sector_code" required class="w-full rounded-xl border border-slate-300 py-2 px-3 text-xs bg-white outline-none focus:border-[#0284C7]">
                  <option value="smart_iot">smart_iot (Smart IoT)</option>
                  <option value="cloud_infra">cloud_infra</option>
                  <option value="internet">internet</option>
                  <option value="bluecom">bluecom</option>
                  <option value="tech_services">tech_services</option>
                </select>
              </div>
              <div>
                <label class="block font-bold text-slate-700 uppercase mb-1">Danh Mục Cha (Tùy Chọn)</label>
                <select v-model="categoryForm.parent_id" class="w-full rounded-xl border border-slate-300 py-2 px-3 text-xs bg-white outline-none focus:border-[#0284C7]">
                  <option value="">-- Là Trụ Cột Gốc Độc Lập --</option>
                  <option v-for="root in rootCategories.filter(r => r.id !== selectedCategory.id)" :key="root.id" :value="root.id">
                    {{ root.name }} ({{ root.sector_code }})
                  </option>
                </select>
              </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
              <div>
                <label class="block font-bold text-slate-700 uppercase mb-1">Thứ Tự Hiển Thị</label>
                <input v-model="categoryForm.display_order" type="number" min="1" class="w-full rounded-xl border border-slate-300 py-2 px-3 text-xs outline-none focus:border-[#0284C7]" />
              </div>
              <div>
                <label class="block font-bold text-slate-700 uppercase mb-1">Icon Đại Diện</label>
                <input v-model="categoryForm.icon" type="text" class="w-full rounded-xl border border-slate-300 py-2 px-3 text-xs outline-none focus:border-[#0284C7]" />
              </div>
            </div>

            <div>
              <label class="block font-bold text-slate-700 uppercase mb-1">Mô Tả Tóm Tắt</label>
              <textarea v-model="categoryForm.description" rows="2" class="w-full rounded-xl border border-slate-300 py-2 px-3 text-xs outline-none focus:border-[#0284C7]"></textarea>
            </div>

            <div class="flex items-center gap-4">
              <label class="flex items-center gap-2 cursor-pointer font-bold text-slate-800">
                <input v-model="categoryForm.is_active" type="checkbox" class="rounded text-[#0284C7]" />
                <span>Kích hoạt hiển thị ngoài website</span>
              </label>
            </div>

            <div class="flex justify-end gap-3 pt-3 border-t">
              <button type="button" @click="isEditCategoryOpen = false" class="px-4 py-2 border rounded-xl">Hủy</button>
              <button type="submit" class="px-5 py-2 bg-[#0284C7] text-white font-bold rounded-xl shadow">Lưu Thay Đổi</button>
            </div>
          </form>
        </div>
      </div>
    </div>

    <!-- 3. CREATE PROJECT MODAL -->
    <div v-if="isCreateProjectOpen" class="fixed inset-0 z-50 overflow-y-auto" role="dialog">
      <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm" @click="isCreateProjectOpen = false"></div>
      <div class="flex min-h-screen items-center justify-center p-4">
        <div class="relative bg-white rounded-3xl max-w-xl w-full p-6 sm:p-8 shadow-2xl border border-slate-200 text-left space-y-4">
          <div class="flex items-center justify-between border-b border-slate-100 pb-3">
            <h3 class="text-lg font-bold text-[#0F294A]">Thêm Mới Dự Án & Khách Hàng Tiêu Biểu</h3>
            <button @click="isCreateProjectOpen = false" class="text-slate-400 hover:text-slate-700">✕</button>
          </div>

          <form @submit.prevent="submitCreateProject" class="space-y-4 text-xs">
            <div>
              <label class="block font-bold text-slate-700 uppercase mb-1">Tên Dự Án / Công Trình *</label>
              <input v-model="projectForm.title" type="text" required placeholder="VD: Cung cấp hạ tầng Cloud Private cho Tập đoàn Vận tải..." class="w-full rounded-xl border border-slate-300 py-2 px-3 text-xs outline-none focus:border-[#0284C7]" />
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
              <div>
                <label class="block font-bold text-slate-700 uppercase mb-1">Tên Khách Hàng / Đơn Vị *</label>
                <input v-model="projectForm.client_name" type="text" required placeholder="VD: Tập đoàn Cảng Biển SG" class="w-full rounded-xl border border-slate-300 py-2 px-3 text-xs outline-none focus:border-[#0284C7]" />
              </div>
              <div>
                <label class="block font-bold text-slate-700 uppercase mb-1">Lĩnh Vực Cốt Lõi *</label>
                <select v-model="projectForm.sector_code" required class="w-full rounded-xl border border-slate-300 py-2 px-3 text-xs bg-white outline-none focus:border-[#0284C7]">
                  <option value="cloud_infra">cloud_infra (Cloud & Dedicated)</option>
                  <option value="internet">internet (Internet Leased Line & SD-WAN)</option>
                  <option value="smart_iot">smart_iot (Smart IoT Solar)</option>
                  <option value="bluecom">bluecom (Bluecom 4G)</option>
                  <option value="tech_services">tech_services (Thi Công Kỹ Thuật)</option>
                </select>
              </div>
            </div>

            <div>
              <label class="block font-bold text-slate-700 uppercase mb-1">Mô Tả Kết Quả Triển Khai *</label>
              <textarea v-model="projectForm.description" required rows="3" placeholder="Quy mô triển khai, thời gian hoàn thành, cam kết SLA..." class="w-full rounded-xl border border-slate-300 py-2 px-3 text-xs outline-none focus:border-[#0284C7] leading-relaxed"></textarea>
            </div>

            <div>
              <label class="block font-bold text-slate-700 uppercase mb-1">Tải Ảnh Thực Tế Công Trình (Tự động nén WebP)</label>
              <input type="file" accept="image/*" @change="e => projectForm.image_file = e.target.files[0]" class="w-full text-xs text-slate-500 file:mr-3 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-cyan-50 file:text-[#0284C7] hover:file:bg-cyan-100" />
            </div>

            <div class="flex items-center gap-2 pt-2">
              <label class="flex items-center gap-2 cursor-pointer font-bold text-slate-800">
                <input v-model="projectForm.is_featured" type="checkbox" class="rounded text-[#0284C7]" />
                <span>Hiển thị nổi bật tại Lưới Dự Án trên Trang Chủ</span>
              </label>
            </div>

            <div class="flex justify-end gap-3 pt-3 border-t">
              <button type="button" @click="isCreateProjectOpen = false" class="px-4 py-2 border rounded-xl">Hủy</button>
              <button type="submit" class="px-5 py-2 bg-[#0284C7] text-white font-bold rounded-xl shadow">Lưu Dự Án</button>
            </div>
          </form>
        </div>
      </div>
    </div>

    <!-- 3B. EDIT PROJECT MODAL -->
    <div v-if="isEditProjectOpen && selectedProject" class="fixed inset-0 z-50 overflow-y-auto" role="dialog">
      <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm" @click="isEditProjectOpen = false"></div>
      <div class="flex min-h-screen items-center justify-center p-4">
        <div class="relative bg-white rounded-3xl max-w-xl w-full p-6 sm:p-8 shadow-2xl border border-slate-200 text-left space-y-4">
          <div class="flex items-center justify-between border-b border-slate-100 pb-3">
            <h3 class="text-lg font-bold text-[#0F294A]">Sửa Dự Án: {{ selectedProject.title }}</h3>
            <button @click="isEditProjectOpen = false" class="text-slate-400 hover:text-slate-700">✕</button>
          </div>

          <form @submit.prevent="submitUpdateProject" class="space-y-4 text-xs">
            <div>
              <label class="block font-bold text-slate-700 uppercase mb-1">Tên Dự Án / Công Trình *</label>
              <input v-model="projectForm.title" type="text" required class="w-full rounded-xl border border-slate-300 py-2 px-3 text-xs outline-none focus:border-[#0284C7]" />
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
              <div>
                <label class="block font-bold text-slate-700 uppercase mb-1">Tên Khách Hàng / Đơn Vị *</label>
                <input v-model="projectForm.client_name" type="text" required class="w-full rounded-xl border border-slate-300 py-2 px-3 text-xs outline-none focus:border-[#0284C7]" />
              </div>
              <div>
                <label class="block font-bold text-slate-700 uppercase mb-1">Lĩnh Vực Cốt Lõi *</label>
                <select v-model="projectForm.sector_code" required class="w-full rounded-xl border border-slate-300 py-2 px-3 text-xs bg-white outline-none focus:border-[#0284C7]">
                  <option value="cloud_infra">cloud_infra</option>
                  <option value="internet">internet</option>
                  <option value="smart_iot">smart_iot</option>
                  <option value="bluecom">bluecom</option>
                  <option value="tech_services">tech_services</option>
                </select>
              </div>
            </div>

            <div>
              <label class="block font-bold text-slate-700 uppercase mb-1">Mô Tả Kết Quả Triển Khai *</label>
              <textarea v-model="projectForm.description" required rows="3" class="w-full rounded-xl border border-slate-300 py-2 px-3 text-xs outline-none focus:border-[#0284C7] leading-relaxed"></textarea>
            </div>

            <div>
              <label class="block font-bold text-slate-700 uppercase mb-1">Cập Nhật Ảnh Mới (Bỏ trống nếu giữ ảnh cũ)</label>
              <input type="file" accept="image/*" @change="e => projectForm.image_file = e.target.files[0]" class="w-full text-xs text-slate-500 file:mr-3 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-cyan-50 file:text-[#0284C7] hover:file:bg-cyan-100" />
            </div>

            <div class="flex items-center gap-2 pt-2">
              <label class="flex items-center gap-2 cursor-pointer font-bold text-slate-800">
                <input v-model="projectForm.is_featured" type="checkbox" class="rounded text-[#0284C7]" />
                <span>Hiển thị nổi bật tại Lưới Dự Án trên Trang Chủ</span>
              </label>
            </div>

            <div class="flex justify-end gap-3 pt-3 border-t">
              <button type="button" @click="isEditProjectOpen = false" class="px-4 py-2 border rounded-xl">Hủy</button>
              <button type="submit" class="px-5 py-2 bg-[#0284C7] text-white font-bold rounded-xl shadow">Lưu Thay Đổi</button>
            </div>
          </form>
        </div>
      </div>
    </div>

    <!-- 4. CREATE VPS PLAN MODAL -->
    <div v-if="isCreateVpsOpen" class="fixed inset-0 z-50 overflow-y-auto" role="dialog">
      <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm" @click="isCreateVpsOpen = false"></div>
      <div class="flex min-h-screen items-center justify-center p-4">
        <div class="relative bg-white rounded-3xl max-w-xl w-full p-6 sm:p-8 shadow-2xl border border-slate-200 text-left space-y-4">
          <div class="flex items-center justify-between border-b border-slate-100 pb-3">
            <h3 class="text-lg font-bold text-[#0F294A]">Thêm Mới Gói Cloud VPS</h3>
            <button @click="isCreateVpsOpen = false" class="text-slate-400 hover:text-slate-700">✕</button>
          </div>

          <form @submit.prevent="submitCreateVps" class="space-y-4 text-xs">
            <div>
              <label class="block font-bold text-slate-700 uppercase mb-1">Tên Gói VPS *</label>
              <input v-model="vpsForm.name" type="text" required placeholder="VD: Cloud VPS Pro (Doanh Nghiệp)" class="w-full rounded-xl border border-slate-300 py-2 px-3 text-xs outline-none focus:border-[#0284C7]" />
            </div>

            <div class="grid grid-cols-3 gap-3">
              <div>
                <label class="block font-bold text-slate-700 uppercase mb-1">vCPU Cores *</label>
                <input v-model="vpsForm.vcpu" type="number" required min="1" class="w-full rounded-xl border border-slate-300 py-2 px-3 text-xs outline-none" />
              </div>
              <div>
                <label class="block font-bold text-slate-700 uppercase mb-1">RAM (GB) *</label>
                <input v-model="vpsForm.ram_gb" type="number" required min="1" class="w-full rounded-xl border border-slate-300 py-2 px-3 text-xs outline-none" />
              </div>
              <div>
                <label class="block font-bold text-slate-700 uppercase mb-1">NVMe (GB) *</label>
                <input v-model="vpsForm.storage_nvme_gb" type="number" required min="10" class="w-full rounded-xl border border-slate-300 py-2 px-3 text-xs outline-none" />
              </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
              <div>
                <label class="block font-bold text-slate-700 uppercase mb-1">Băng Thông Mạng *</label>
                <input v-model="vpsForm.bandwidth" type="text" required class="w-full rounded-xl border border-slate-300 py-2 px-3 text-xs outline-none" />
              </div>
              <div>
                <label class="block font-bold text-slate-700 uppercase mb-1">Cổng Mạng (Port Uplink) *</label>
                <input v-model="vpsForm.port_speed" type="text" required class="w-full rounded-xl border border-slate-300 py-2 px-3 text-xs outline-none" />
              </div>
            </div>

            <div class="grid grid-cols-3 gap-3">
              <div>
                <label class="block font-bold text-slate-700 uppercase mb-1">Giá Thuê Tháng (VNĐ) *</label>
                <input v-model="vpsForm.price_monthly" type="number" required min="0" class="w-full rounded-xl border border-slate-300 py-2 px-3 text-xs outline-none" />
              </div>
              <div>
                <label class="block font-bold text-slate-700 uppercase mb-1">Giá Thuê Năm (VNĐ) *</label>
                <input v-model="vpsForm.price_yearly" type="number" required min="0" class="w-full rounded-xl border border-slate-300 py-2 px-3 text-xs outline-none" />
              </div>
              <div>
                <label class="block font-bold text-slate-700 uppercase mb-1">% Giảm Khi Thuê Năm</label>
                <input v-model="vpsForm.discount_percent_yearly" type="number" min="0" max="100" class="w-full rounded-xl border border-slate-300 py-2 px-3 text-xs outline-none" />
              </div>
            </div>

            <div>
              <label class="block font-bold text-slate-700 uppercase mb-1">Danh Sách Tính Năng (Mỗi dòng 1 tính năng)</label>
              <textarea v-model="vpsForm.features_text" rows="4" placeholder="4 vCPU Dedicated AMD EPYC&#10;8 GB RAM ECC Server&#10;120 GB NVMe RAID-10..." class="w-full rounded-xl border border-slate-300 py-2 px-3 text-xs outline-none font-mono"></textarea>
            </div>

            <div class="flex items-center gap-2">
              <label class="flex items-center gap-2 cursor-pointer font-bold text-slate-800">
                <input v-model="vpsForm.is_popular" type="checkbox" class="rounded text-[#0284C7]" />
                <span>Gắn nhãn Gói Phổ Biến Nhất (Most Popular)</span>
              </label>
            </div>

            <div class="flex justify-end gap-3 pt-3 border-t">
              <button type="button" @click="isCreateVpsOpen = false" class="px-4 py-2 border rounded-xl">Hủy</button>
              <button type="submit" class="px-5 py-2 bg-[#0284C7] text-white font-bold rounded-xl shadow">Lưu Gói VPS</button>
            </div>
          </form>
        </div>
      </div>
    </div>

    <!-- 4B. EDIT VPS PLAN MODAL -->
    <div v-if="isEditVpsOpen && selectedVps" class="fixed inset-0 z-50 overflow-y-auto" role="dialog">
      <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm" @click="isEditVpsOpen = false"></div>
      <div class="flex min-h-screen items-center justify-center p-4">
        <div class="relative bg-white rounded-3xl max-w-xl w-full p-6 sm:p-8 shadow-2xl border border-slate-200 text-left space-y-4">
          <div class="flex items-center justify-between border-b border-slate-100 pb-3">
            <h3 class="text-lg font-bold text-[#0F294A]">Sửa Gói VPS: {{ selectedVps.name }}</h3>
            <button @click="isEditVpsOpen = false" class="text-slate-400 hover:text-slate-700">✕</button>
          </div>

          <form @submit.prevent="submitUpdateVps" class="space-y-4 text-xs">
            <div>
              <label class="block font-bold text-slate-700 uppercase mb-1">Tên Gói VPS *</label>
              <input v-model="vpsForm.name" type="text" required class="w-full rounded-xl border border-slate-300 py-2 px-3 text-xs outline-none focus:border-[#0284C7]" />
            </div>

            <div class="grid grid-cols-3 gap-3">
              <div>
                <label class="block font-bold text-slate-700 uppercase mb-1">vCPU Cores *</label>
                <input v-model="vpsForm.vcpu" type="number" required min="1" class="w-full rounded-xl border border-slate-300 py-2 px-3 text-xs outline-none" />
              </div>
              <div>
                <label class="block font-bold text-slate-700 uppercase mb-1">RAM (GB) *</label>
                <input v-model="vpsForm.ram_gb" type="number" required min="1" class="w-full rounded-xl border border-slate-300 py-2 px-3 text-xs outline-none" />
              </div>
              <div>
                <label class="block font-bold text-slate-700 uppercase mb-1">NVMe (GB) *</label>
                <input v-model="vpsForm.storage_nvme_gb" type="number" required min="10" class="w-full rounded-xl border border-slate-300 py-2 px-3 text-xs outline-none" />
              </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
              <div>
                <label class="block font-bold text-slate-700 uppercase mb-1">Băng Thông Mạng *</label>
                <input v-model="vpsForm.bandwidth" type="text" required class="w-full rounded-xl border border-slate-300 py-2 px-3 text-xs outline-none" />
              </div>
              <div>
                <label class="block font-bold text-slate-700 uppercase mb-1">Cổng Mạng (Port Uplink) *</label>
                <input v-model="vpsForm.port_speed" type="text" required class="w-full rounded-xl border border-slate-300 py-2 px-3 text-xs outline-none" />
              </div>
            </div>

            <div class="grid grid-cols-3 gap-3">
              <div>
                <label class="block font-bold text-slate-700 uppercase mb-1">Giá Thuê Tháng (VNĐ) *</label>
                <input v-model="vpsForm.price_monthly" type="number" required min="0" class="w-full rounded-xl border border-slate-300 py-2 px-3 text-xs outline-none" />
              </div>
              <div>
                <label class="block font-bold text-slate-700 uppercase mb-1">Giá Thuê Năm (VNĐ) *</label>
                <input v-model="vpsForm.price_yearly" type="number" required min="0" class="w-full rounded-xl border border-slate-300 py-2 px-3 text-xs outline-none" />
              </div>
              <div>
                <label class="block font-bold text-slate-700 uppercase mb-1">% Giảm Năm</label>
                <input v-model="vpsForm.discount_percent_yearly" type="number" min="0" max="100" class="w-full rounded-xl border border-slate-300 py-2 px-3 text-xs outline-none" />
              </div>
            </div>

            <div>
              <label class="block font-bold text-slate-700 uppercase mb-1">Danh Sách Tính Năng (Mỗi dòng 1 tính năng)</label>
              <textarea v-model="vpsForm.features_text" rows="4" class="w-full rounded-xl border border-slate-300 py-2 px-3 text-xs outline-none font-mono"></textarea>
            </div>

            <div class="flex items-center gap-2">
              <label class="flex items-center gap-2 cursor-pointer font-bold text-slate-800">
                <input v-model="vpsForm.is_popular" type="checkbox" class="rounded text-[#0284C7]" />
                <span>Gắn nhãn Gói Phổ Biến Nhất (Most Popular)</span>
              </label>
            </div>

            <div class="flex justify-end gap-3 pt-3 border-t">
              <button type="button" @click="isEditVpsOpen = false" class="px-4 py-2 border rounded-xl">Hủy</button>
              <button type="submit" class="px-5 py-2 bg-[#0284C7] text-white font-bold rounded-xl shadow">Lưu Thay Đổi</button>
            </div>
          </form>
        </div>
      </div>
    </div>

    <!-- 5. EDIT PRODUCT MODAL (11 Components) -->
    <div v-if="isEditProductOpen && selectedProduct" class="fixed inset-0 z-50 overflow-y-auto" role="dialog">
      <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm" @click="isEditProductOpen = false"></div>
      <div class="flex min-h-screen items-center justify-center p-4">
        <div class="relative bg-white rounded-3xl max-w-3xl w-full p-6 sm:p-8 shadow-2xl border border-slate-200 text-left space-y-4">
          <div class="flex items-center justify-between border-b border-slate-100 pb-3">
            <h3 class="text-lg font-bold text-[#0F294A]">Sửa Sản Phẩm: {{ selectedProduct.name }}</h3>
            <button @click="isEditProductOpen = false" class="text-slate-400 hover:text-slate-700">✕</button>
          </div>

          <form @submit.prevent="submitUpdateProduct" class="space-y-4 text-xs">
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
              <div>
                <label class="block font-bold text-slate-700 uppercase mb-1">Tên Sản Phẩm / Dịch Vụ *</label>
                <input v-model="editProdForm.name" type="text" required class="w-full rounded-xl border border-slate-300 py-2 px-3 text-xs outline-none" />
              </div>
              <div>
                <label class="block font-bold text-slate-700 uppercase mb-1">Mã Model [02] *</label>
                <input v-model="editProdForm.model_code" type="text" required class="w-full rounded-xl border border-slate-300 py-2 px-3 text-xs outline-none font-mono" />
              </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
              <div>
                <label class="block font-bold text-slate-700 uppercase mb-1">Danh Mục Cấp *</label>
                <select v-model="editProdForm.category_id" required class="w-full rounded-xl border border-slate-300 py-2 px-3 text-xs bg-white outline-none">
                  <option v-for="cat in categories" :key="cat.id" :value="cat.id">{{ cat.parent_id ? '  ↳ ' + cat.name : '★ ' + cat.name }} ({{ cat.sector_code }})</option>
                </select>
              </div>
              <div>
                <label class="block font-bold text-slate-700 uppercase mb-1">Mã Lĩnh Vực</label>
                <select v-model="editProdForm.sector_code" class="w-full rounded-xl border border-slate-300 py-2 px-3 text-xs bg-white outline-none">
                  <option value="cloud_infra">cloud_infra</option>
                  <option value="internet">internet</option>
                  <option value="smart_iot">smart_iot</option>
                  <option value="bluecom">bluecom</option>
                  <option value="tech_services">tech_services</option>
                </select>
              </div>
            </div>

            <div>
              <label class="block font-bold text-slate-700 uppercase mb-1">Mô Tả Tổng Quan [04] (2-3 câu định vị B2B) *</label>
              <textarea v-model="editProdForm.overview" required rows="2" class="w-full rounded-xl border border-slate-300 py-2 px-3 text-xs outline-none"></textarea>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
              <div>
                <label class="block font-bold text-slate-700 uppercase mb-1">Chính Sách Bảo Hành [09]</label>
                <input v-model="editProdForm.warranty_policy" type="text" class="w-full rounded-xl border border-slate-300 py-2 px-3 text-xs outline-none" />
              </div>
              <div>
                <label class="block font-bold text-slate-700 uppercase mb-1">Cam Kết Hỗ Trợ Kỹ Thuật SLA [09]</label>
                <input v-model="editProdForm.support_commitment" type="text" class="w-full rounded-xl border border-slate-300 py-2 px-3 text-xs outline-none" />
              </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
              <div>
                <label class="block font-bold text-slate-700 uppercase mb-1">Đơn Giá Niêm Yết [11]</label>
                <input v-model="editProdForm.price" type="number" class="w-full rounded-xl border border-slate-300 py-2 px-3 text-xs outline-none" />
              </div>
              <div class="flex items-center gap-4 pt-6">
                <label class="flex items-center gap-2 cursor-pointer font-bold text-slate-800">
                  <input v-model="editProdForm.is_price_visible" type="checkbox" class="rounded text-[#0284C7]" />
                  <span>Hiển Thị Giá Niêm Yết</span>
                </label>
                <label class="flex items-center gap-2 cursor-pointer font-bold text-slate-800">
                  <input v-model="editProdForm.is_featured" type="checkbox" class="rounded text-[#0284C7]" />
                  <span>Nổi Bật Trang Chủ</span>
                </label>
              </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
              <div>
                <label class="block font-bold text-slate-700 uppercase mb-1">Cập Nhật Ảnh WebP Mới</label>
                <input type="file" accept="image/*" @change="e => editProdForm.thumbnail_file = e.target.files[0]" class="w-full text-xs text-slate-500 file:mr-3 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-cyan-50 file:text-[#0284C7]" />
              </div>
              <div>
                <label class="block font-bold text-slate-700 uppercase mb-1">Tải Lên Datasheet PDF [08]</label>
                <input type="file" accept="application/pdf" @change="e => editProdForm.datasheet_file = e.target.files[0]" class="w-full text-xs text-slate-500 file:mr-3 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-cyan-50 file:text-[#0284C7]" />
              </div>
            </div>

            <div class="flex justify-end gap-3 pt-3 border-t">
              <button type="button" @click="isEditProductOpen = false" class="px-4 py-2 border rounded-xl">Hủy</button>
              <button type="submit" class="px-5 py-2 bg-[#0284C7] text-white font-bold rounded-xl shadow">Lưu Thay Đổi</button>
            </div>
          </form>
        </div>
      </div>
    </div>

    <!-- 6. CREATE POST MODAL -->
    <div v-if="isCreatePostOpen" class="fixed inset-0 z-50 overflow-y-auto" role="dialog">
      <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm" @click="isCreatePostOpen = false"></div>
      <div class="flex min-h-screen items-center justify-center p-4">
        <div class="relative bg-white rounded-3xl max-w-xl w-full p-6 sm:p-8 shadow-2xl border border-slate-200 text-left space-y-4">
          <div class="flex items-center justify-between border-b border-slate-100 pb-3">
            <h3 class="text-lg font-bold text-[#0F294A]">Thêm Mới Bài Viết / Tin Tức</h3>
            <button @click="isCreatePostOpen = false" class="text-slate-400 hover:text-slate-700">✕</button>
          </div>

          <form @submit.prevent="submitCreatePost" class="space-y-4 text-xs">
            <div>
              <label class="block font-bold text-slate-700 uppercase mb-1">Tiêu Đề Bài Viết *</label>
              <input v-model="postForm.title" type="text" required placeholder="VD: Khảo sát triển khai Starlink cho tàu ngoài khơi..." class="w-full rounded-xl border border-slate-300 py-2 px-3 text-xs outline-none focus:border-[#0284C7]" />
            </div>

            <div>
              <label class="block font-bold text-slate-700 uppercase mb-1">Tóm Tắt Ngắn (Summary) *</label>
              <textarea v-model="postForm.summary" required rows="2" placeholder="Đoạn mở đầu bài viết..." class="w-full rounded-xl border border-slate-300 py-2 px-3 text-xs outline-none focus:border-[#0284C7]"></textarea>
            </div>

            <div>
              <label class="block font-bold text-slate-700 uppercase mb-1">Nội Dung Chi Tiết *</label>
              <textarea v-model="postForm.content" required rows="6" placeholder="Nội dung bài viết chi tiết..." class="w-full rounded-xl border border-slate-300 py-2 px-3 text-xs outline-none focus:border-[#0284C7] leading-relaxed"></textarea>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
              <div>
                <label class="block font-bold text-slate-700 uppercase mb-1">Tác Giả</label>
                <input v-model="postForm.author_name" type="text" class="w-full rounded-xl border border-slate-300 py-2 px-3 text-xs outline-none" />
              </div>
              <div>
                <label class="block font-bold text-slate-700 uppercase mb-1">Ảnh Đại Diện Bài Viết</label>
                <input type="file" accept="image/*" @change="e => postForm.thumbnail_file = e.target.files[0]" class="w-full text-xs text-slate-500 file:mr-3 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-cyan-50 file:text-[#0284C7]" />
              </div>
            </div>

            <div class="flex items-center gap-2 pt-2">
              <label class="flex items-center gap-2 cursor-pointer font-bold text-slate-800">
                <input v-model="postForm.is_published" type="checkbox" class="rounded text-[#0284C7]" />
                <span>Xuất Bản Ngay (Công khai ngoài trang Tin Tức)</span>
              </label>
            </div>

            <div class="flex justify-end gap-3 pt-3 border-t">
              <button type="button" @click="isCreatePostOpen = false" class="px-4 py-2 border rounded-xl">Hủy</button>
              <button type="submit" class="px-5 py-2 bg-[#0284C7] text-white font-bold rounded-xl shadow">Đăng Bài Viết</button>
            </div>
          </form>
        </div>
      </div>
    </div>

    <!-- 6B. EDIT POST MODAL -->
    <div v-if="isEditPostOpen && selectedPost" class="fixed inset-0 z-50 overflow-y-auto" role="dialog">
      <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm" @click="isEditPostOpen = false"></div>
      <div class="flex min-h-screen items-center justify-center p-4">
        <div class="relative bg-white rounded-3xl max-w-xl w-full p-6 sm:p-8 shadow-2xl border border-slate-200 text-left space-y-4">
          <div class="flex items-center justify-between border-b border-slate-100 pb-3">
            <h3 class="text-lg font-bold text-[#0F294A]">Sửa Bài Viết: {{ selectedPost.title }}</h3>
            <button @click="isEditPostOpen = false" class="text-slate-400 hover:text-slate-700">✕</button>
          </div>

          <form @submit.prevent="submitUpdatePost" class="space-y-4 text-xs">
            <div>
              <label class="block font-bold text-slate-700 uppercase mb-1">Tiêu Đề Bài Viết *</label>
              <input v-model="postForm.title" type="text" required class="w-full rounded-xl border border-slate-300 py-2 px-3 text-xs outline-none focus:border-[#0284C7]" />
            </div>

            <div>
              <label class="block font-bold text-slate-700 uppercase mb-1">Tóm Tắt Ngắn (Summary) *</label>
              <textarea v-model="postForm.summary" required rows="2" class="w-full rounded-xl border border-slate-300 py-2 px-3 text-xs outline-none focus:border-[#0284C7]"></textarea>
            </div>

            <div>
              <label class="block font-bold text-slate-700 uppercase mb-1">Nội Dung Chi Tiết *</label>
              <textarea v-model="postForm.content" required rows="6" class="w-full rounded-xl border border-slate-300 py-2 px-3 text-xs outline-none focus:border-[#0284C7] leading-relaxed"></textarea>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
              <div>
                <label class="block font-bold text-slate-700 uppercase mb-1">Tác Giả</label>
                <input v-model="postForm.author_name" type="text" class="w-full rounded-xl border border-slate-300 py-2 px-3 text-xs outline-none" />
              </div>
              <div>
                <label class="block font-bold text-slate-700 uppercase mb-1">Cập Nhật Ảnh Đại Diện Mới</label>
                <input type="file" accept="image/*" @change="e => postForm.thumbnail_file = e.target.files[0]" class="w-full text-xs text-slate-500 file:mr-3 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-cyan-50 file:text-[#0284C7]" />
              </div>
            </div>

            <div class="flex items-center gap-2 pt-2">
              <label class="flex items-center gap-2 cursor-pointer font-bold text-slate-800">
                <input v-model="postForm.is_published" type="checkbox" class="rounded text-[#0284C7]" />
                <span>Xuất Bản (Công khai ngoài trang Tin Tức)</span>
              </label>
            </div>

            <div class="flex justify-end gap-3 pt-3 border-t">
              <button type="button" @click="isEditPostOpen = false" class="px-4 py-2 border rounded-xl">Hủy</button>
              <button type="submit" class="px-5 py-2 bg-[#0284C7] text-white font-bold rounded-xl shadow">Lưu Thay Đổi</button>
            </div>
          </form>
        </div>
      </div>
    </div>

  </div>
</template>
