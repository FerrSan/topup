import Echo from 'laravel-echo';
import Pusher from 'pusher-js';
window.Pusher = Pusher;

const PUSHER_KEY = import.meta.env.VITE_PUSHER_APP_KEY;

if (PUSHER_KEY) {
  window.Echo = new Echo({
  broadcaster: 'pusher',
  key: import.meta.env.VITE_PUSHER_APP_KEY,
  cluster: import.meta.env.VITE_PUSHER_APP_CLUSTER ?? 'mt1',
  forceTLS: true,
});

} else {
  console.warn('[Echo] VITE_PUSHER_APP_KEY not set, skipping Echo init.');
}
