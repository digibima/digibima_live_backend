self.addEventListener('install', (event) => {
    event.waitUntil(
        caches.open('your-cache-name').then((cache) => {
            return cache.addAll([
                '/',
                '/css/app.css', // Add your CSS files
                '/js/app.js',   // Add your JavaScript files
                'https://insurance.digibima.com/public/images/192.jpg',
                'https://insurance.digibima.com/public/images/512.jpg',
            ]);
        })
    );
});

self.addEventListener('fetch', (event) => {
    event.respondWith(
        caches.match(event.request).then((response) => {
            return response || fetch(event.request);
        })
    );
});
