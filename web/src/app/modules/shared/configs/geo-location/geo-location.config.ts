export const GEO_LOCATION_CONFIG = {
  // Cache duration in milliseconds (5 minutes)
  CACHE_DURATION: 300000,

  // Geolocation timeout in milliseconds
  GEOLOCATION_TIMEOUT: 10000,

  // Request timeout in milliseconds
  REQUEST_TIMEOUT: 15000,

  // Headers to use for location
  HEADERS: {
    LATITUDE: 'X-Device-Latitude',
    LONGITUDE: 'X-Device-Longitude',
    ACCURACY: 'X-Device-Accuracy',
    SOURCE: 'X-Location-Source'
  },

  // URLs for IP-based location fallback
  IP_LOCATION_SERVICES: [
    'https://ipapi.co/json/',
    'https://ipinfo.io/json/'
  ],

  // LocalStorage keys
  STORAGE_KEYS: {
    DEVICE_LOCATION: 'hawdaj_geo_device_location',
    LOCATION_TIMESTAMP: 'hawdaj_geo_location_timestamp'
  }
} as const;
