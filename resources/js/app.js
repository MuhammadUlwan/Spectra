import './bootstrap';
import '../css/app.css';
import { createApp, h } from 'vue';
import { createInertiaApp } from '@inertiajs/vue3'; 
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { InertiaProgress } from '@inertiajs/progress';
import { Link } from '@inertiajs/vue3'; 

// Inertia progress bar
InertiaProgress.init({
  color: '#4ADE80',
  showSpinner: true,
});

// Buat Inertia app
createInertiaApp({
  resolve: (name) => {
    return resolvePageComponent(
      `./Pages/${name}.vue`,
      import.meta.glob('./Pages/**/*.vue')
    );
  },
  setup({ el, App, props, plugin }) {
    const app = createApp({ render: () => h(App, props) });
    app.use(plugin);
    app.component('Link', Link);
    app.mount(el);
  },
});