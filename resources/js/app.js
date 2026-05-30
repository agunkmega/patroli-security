import './bootstrap';

import Alpine from 'alpinejs';
import axios from 'axios';

window.Alpine = Alpine;
window.axios = axios;
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

Alpine.store('sidebar', {
    isOpen: window.innerWidth >= 1024,
});

Alpine.data('dashboard', () => ({
    currentTime: new Date().toLocaleTimeString('id-ID'),
    init() {
        setInterval(() => {
            this.currentTime = new Date().toLocaleTimeString('id-ID');
        }, 1000);
    }
}));

Alpine.start();

if ('serviceWorker' in navigator) {
    window.addEventListener('load', () => {
        navigator.serviceWorker.register('/sw.js').catch(() => {});
    });
}

document.addEventListener('DOMContentLoaded', () => {
    const clock = document.getElementById('liveClock');
    if (clock) {
        setInterval(() => {
            clock.textContent = new Date().toLocaleString('id-ID', {
                weekday: 'short', year: 'numeric', month: 'short', day: 'numeric',
                hour: '2-digit', minute: '2-digit', second: '2-digit'
            });
        }, 1000);
    }
});
