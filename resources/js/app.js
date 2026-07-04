document.addEventListener('DOMContentLoaded', syncNotificationToggle);
document.addEventListener('DOMContentLoaded', initNotificationDrawer);

function initNotificationDrawer() {
    const drawer = document.getElementById('my-drawer');
    if (!drawer) return;

    drawer.addEventListener('change', async () => {
        if (!drawer.checked) return;

        document.getElementById('unread-dot')?.remove();

        await fetch(drawer.dataset.markReadUrl, {
            method: 'PATCH',
            headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content },
        });
    });
}

async function syncNotificationToggle() {
    const toggle = document.getElementById('notification-subscriber');
    if (!toggle) return;

    if (!('serviceWorker' in navigator) || !('PushManager' in window)) {
        toggle.disabled = true;
        return;
    }

    const registration = await navigator.serviceWorker.getRegistration('/sw.js');
    const subscription = registration ? await registration.pushManager.getSubscription() : null;
    toggle.checked = !!subscription;
}

document.getElementById('notification-subscriber')?.addEventListener('change', async (event) => {
    const toggle = event.target;
    try {
        if (toggle.checked) {
            await subscribe();
        } else {
            await unsubscribe();
        }
    } catch (err) {
        console.error('Push notification error:', err);
        toggle.checked = !toggle.checked;
    }
});


// VAPID keys are Base64URL-encoded; atob() requires standard Base64, so we convert first
function urlBase64ToUint8Array(base64String) {
    const padding = '='.repeat((4 - (base64String.length % 4)) % 4);
    const base64 = (base64String + padding).replace(/-/g, '+').replace(/_/g, '/');
    const rawData = atob(base64);
    return Uint8Array.from([...rawData].map((c) => c.charCodeAt(0)));
}

async function subscribe() {
    if (!('serviceWorker' in navigator) || !('PushManager' in window)) {
        return;
    }

    const vapidKey = document.querySelector('meta[name="vapid-public-key"]')?.content;
    if (!vapidKey) {
        throw new Error('VAPID public key is missing from the page.');
    }

    const permission = await Notification.requestPermission();
    if (permission !== 'granted') {
        return;
    }

    await navigator.serviceWorker.register('/sw.js');
    const registration = await navigator.serviceWorker.ready;

    const contentEncoding = (PushManager.supportedContentEncodings || ['aesgcm'])[0];

    const subscription = await registration.pushManager.subscribe({
        userVisibleOnly: true,
        applicationServerKey: urlBase64ToUint8Array(vapidKey),
    });

    const { endpoint, keys: { p256dh, auth } } = subscription.toJSON();

    await fetch('/notifications/push/subscribe', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content,
        },
        body: JSON.stringify({ endpoint, key: p256dh, token: auth, encoding: contentEncoding }),
    });
}

async function unsubscribe() {
    if (!('serviceWorker' in navigator) || !('PushManager' in window)) {
        return;
    }

    const registration = await navigator.serviceWorker.ready;
    const subscription = await registration.pushManager.getSubscription();

    if (!subscription) {
        return;
    }

    const { endpoint } = subscription.toJSON();

    await subscription.unsubscribe();

    await fetch('/notifications/push/unsubscribe', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content,
        },
        body: JSON.stringify({ endpoint }),
    });
}
