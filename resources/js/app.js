import '../css/app.css';
import { createApp, h } from 'vue';
import { createInertiaApp } from '@inertiajs/vue3';
import { createPinia } from 'pinia';
import { createI18n } from 'vue-i18n';

const i18n = createI18n({
    legacy: false,
    locale: 'vi',
    fallbackLocale: 'en',
    messages: {
        vi: {
            brand: 'Toàn Cầu Technology',
            tagline: 'Hạ tầng số – Kết nối thông minh – Giải pháp công nghệ',
            quote_cta: 'Yêu cầu báo giá',
            contact_cta: 'Liên hệ tư vấn',
            partner_cta: 'Hợp tác kinh doanh',
            field_survey: 'Đăng ký khảo sát hiện trường',
        },
        en: {
            brand: 'Toan Cau Technology',
            tagline: 'Digital Infrastructure – Smart Connectivity – Tech Solutions',
            quote_cta: 'Request a Quote',
            contact_cta: 'Contact Consulting',
            partner_cta: 'Business Partnership',
            field_survey: 'Book Field Survey',
        },
    },
});

createInertiaApp({
    title: (title) => `${title ? title + ' - ' : ''}Toàn Cầu Technology`,
    resolve: (name) => {
        const pages = import.meta.glob('./Pages/**/*.vue', { eager: true });
        return pages[`./Pages/${name}.vue`];
    },
    setup({ el, App, props, plugin }) {
        const pinia = createPinia();
        createApp({ render: () => h(App, props) })
            .use(plugin)
            .use(pinia)
            .use(i18n)
            .mount(el);
    },
    progress: {
        color: '#0284C7',
    },
});
