importScripts("https://js.pusher.com/beams/service-worker.js");

// Handle push notification events
self.addEventListener('push', function(event) {
    console.log('Push notification received in service worker');

    if (event.data) {
        const data = event.data.json();
        console.log('Push data:', data);

        // Send message to all clients (browser tabs)
        event.waitUntil(
            self.clients.matchAll().then(function(clients) {
                clients.forEach(function(client) {
                    client.postMessage({
                        type: 'PUSH_NOTIFICATION',
                        notification: data.notification || data
                    });
                });
            })
        );
    }
});

// Handle notification click events
self.addEventListener('notificationclick', function(event) {
    console.log('Notification clicked');
    event.notification.close();

    // Focus or open the app window
    event.waitUntil(
        self.clients.matchAll({ type: 'window' }).then(function(clientList) {
            for (let i = 0; i < clientList.length; i++) {
                const client = clientList[i];
                if (client.url === '/' && 'focus' in client) {
                    return client.focus();
                }
            }
            if (self.clients.openWindow) {
                return self.clients.openWindow('/');
            }
        })
    );
});
