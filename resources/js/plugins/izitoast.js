import izitoast from 'izitoast';
import 'izitoast/dist/css/iziToast.min.css';

const plugin = {
    install: (app, options) => {
        const toasts = {};
        const types = ['info', 'success', 'warning', 'error', 'show'];

        types.forEach(type => {
            toasts[type] = (message, settings = { position: 'topRight' }) => {
                const config = { ...settings, ...options }
                izitoast[type]({ message, ...config });
            }
        });

        app.config.globalProperties.$toast = toasts;
    },
};

export default plugin;
