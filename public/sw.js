const CACHE_NAME = 'patroli-security-v1';
const urlsToCache = [
    '/login',
    '/css/app.css',
    '/js/app.js',
    '/manifest.json',
];

self.addEventListener('install', event => {
    event.waitUntil(
        caches.open(CACHE_NAME).then(cache => cache.addAll(urlsToCache))
    );
    self.skipWaiting();
});

self.addEventListener('activate', event => {
    event.waitUntil(clients.claim());
});

self.addEventListener('fetch', event => {
    if (event.request.method !== 'GET') return;

    event.respondWith(
        caches.match(event.request).then(cached => {
            const fetchPromise = fetch(event.request).then(response => {
                if (response && response.status === 200) {
                    const clone = response.clone();
                    caches.open(CACHE_NAME).then(cache => cache.put(event.request, clone));
                }
                return response;
            }).catch(() => cached);
            return cached || fetchPromise;
        })
    );
});

self.addEventListener('sync', event => {
    if (event.tag === 'sync-patrol') {
        event.waitUntil(syncPatrolData());
    }
});

async function syncPatrolData() {
    const db = await openDB();
    const tx = db.transaction('pending-scans', 'readonly');
    const store = tx.objectStore('pending-scans');
    const scans = await store.getAll();

    for (const scan of scans) {
        try {
            await fetch('/api/scan', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content },
                body: JSON.stringify(scan),
            });
            const deleteTx = db.transaction('pending-scans', 'readwrite');
            await deleteTx.objectStore('pending-scans').delete(scan.id);
        } catch (e) {
            console.error('Sync failed:', e);
        }
    }
}

function openDB() {
    return new Promise((resolve, reject) => {
        const req = indexedDB.open('PatroliDB', 1);
        req.onupgradeneeded = () => {
            req.result.createObjectStore('pending-scans', { keyPath: 'id', autoIncrement: true });
        };
        req.onsuccess = () => resolve(req.result);
        req.onerror = () => reject(req.error);
    });
}
