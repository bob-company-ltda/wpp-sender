const staticCacheName = 'pwa-v1';
const offlinePage = '/offline.html';
let deferredPrompt;

// Install service worker
self.addEventListener('install', event => {
  event.waitUntil(
    caches.open(staticCacheName).then(cache => {
      return cache.addAll([offlinePage]);
    })
  );
});

// Fetch event
self.addEventListener('fetch', event => {
  const { request } = event;
  event.respondWith(
    caches.match(request).then(response => {
      return response || fetch(request).catch(() => caches.match(offlinePage));
    })
  );
});

// Listen for "beforeinstallprompt" event
self.addEventListener('beforeinstallprompt', event => {
  event.preventDefault(); // Prevent the default prompt
  // Store the event for later use
  deferredPrompt = event;
  // Show your custom install prompt UI
  showInstallPrompt();
});

// Function to show your custom install prompt UI
function showInstallPrompt() {
  // Show your custom UI for the install prompt
  // For example, display a button to trigger the installation
  const installButton = document.getElementById('install-button');
  installButton.style.display = 'block';

  // Handle the install button click event
  installButton.addEventListener('click', () => {
    // Prompt the user to install the PWA
    deferredPrompt.prompt();
    deferredPrompt.userChoice.then(choiceResult => {
      if (choiceResult.outcome === 'accepted') {
        console.log('User installed the PWA');
      } else {
        console.log('User dismissed the PWA installation');
      }
      // Reset the deferredPrompt variable
      deferredPrompt = null;
    });
  });
}

// Activate service worker
self.addEventListener('activate', event => {
  event.waitUntil(self.clients.claim());
});
