import './bootstrap';
import '../css/app.css';
import { createApp, h } from 'vue'
import { createInertiaApp, Head, Link } from '@inertiajs/vue3'
import { ZiggyVue } from '../../vendor/tightenco/ziggy'

createInertiaApp({
  progress: true,
  title: (title) => `ArtzworkPC ${title}`,
  resolve: name => {
    const pages = import.meta.glob('./Pages/**/*.vue', { eager: true })
    return pages[`./Pages/${name}.vue`]
  },
  setup({ el, App, props, plugin }) {
   const app =  createApp({ render: () => h(App, props) })
      .use(plugin)
      .use(ZiggyVue)
      .component("Head", Head)
      .component("Link", Link)
      .mount(el)
  },
})
