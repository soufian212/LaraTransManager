import { createApp, h } from 'vue'
import { createInertiaApp } from '@inertiajs/vue3'
import PrimeVue from 'primevue/config';
import Aura from '@primevue/themes/aura';
import layout from '@/Layouts/App.vue'
import { definePreset } from '@primevue/themes';
import 'primeicons/primeicons.css'
import Toast from 'primevue/toast';
import { useToast } from 'primevue/usetoast';
import ToastService from 'primevue/toastservice';
import { Link } from '@inertiajs/vue3'
import ConfirmationService from 'primevue/confirmationservice';
import { ZiggyVue } from 'ziggy-js';
import '../css/app.css'
const MyPreset = definePreset(Aura, {
  semantic: {
      primary: {
          50: '{Indigo.50}',
          100: '{Indigo.100}',
          200: '{Indigo.200}',
          300: '{Indigo.300}',
          400: '{Indigo.400}',
          500: '{Indigo.500}',
          600: '{Indigo.600}',
          700: '{Indigo.700}',
          800: '{Indigo.800}',
          900: '{Indigo.900}',
          950: '{Indigo.950}'
      },
      
  }
});

createInertiaApp({
  resolve: name => {
    const pages = import.meta.glob("./Pages/**/*.vue", { eager: true });
    let page = pages[`./Pages/${name}.vue`];
    if (!page) {
        throw new Error(`Page not found: ./Pages/${name}.vue`);
    }
    page.default.layout = page.default.layout || layout;
    return page;
  },
  setup({ el, App, props, plugin }) {
    createApp({ render: () => h(App, props) })
      .use(plugin)
      .use(ZiggyVue)
      .use(ToastService)
      .component('Link', Link)
      .use(ConfirmationService)
      .use(PrimeVue, {
        theme: {
          preset: MyPreset,
          options: {
            darkModeSelector: false,
          }
      }
      })
      .mount(el)
  },
})