import Echo from 'laravel-echo'

window.bootstrap = require('bootstrap')
window.Pusher = require('pusher-js')
window.axios = require('axios')
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest'

try {
  window.Echo = new Echo({
    broadcaster: 'pusher',
    key: 'bae3160ce349d284eace',
    cluster: 'mt1',
    forceTLS: false,
    wsHost: window.location.hostname,
    wsPort: 6001,
  })

  /* window.Echo = new Echo({
      broadcaster: "pusher",
      key: process.env.MIX_PUSHER_APP_KEY,
      cluster: process.env.MIX_PUSHER_APP_CLUSTER,
      wsHost: window.location.hostname,
      wsPort: 6001,
      // wssPort: 6001,
      disableStats: true,
      enabledTransports: ['ws', 'wss'],
      // forceTLS: true,
      transports: ["websocket", "polling", "flashsocket"],
  }) */
} catch (e) {
  console.log(e)
}
