class LocationCache {
  static double? latitude;
  static double? longitude;

  static void saveLocation(double lat, double lng) {
    latitude = lat;
    longitude = lng;
  }
}
