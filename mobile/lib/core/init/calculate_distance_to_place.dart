import 'package:geolocator/geolocator.dart';

Future<double> calculateDistanceToPlace(double destLat, double destLong) async {
  try {
    print('🔹 Starting distance calc...');
    bool serviceEnabled = await Geolocator.isLocationServiceEnabled();
    print('🔹 Service enabled: $serviceEnabled');
    if (!serviceEnabled) {
      throw Exception('❌ Location services are disabled.');
    }

    LocationPermission permission = await Geolocator.checkPermission();
    print('🔹 Permission: $permission');

    if (permission == LocationPermission.denied) {
      permission = await Geolocator.requestPermission();
      print('🔹 Permission after request: $permission');
      if (permission == LocationPermission.denied) {
        throw Exception('❌ Location permissions are denied.');
      }
    }

    if (permission == LocationPermission.deniedForever) {
      throw Exception('🚫 Location permissions are permanently denied.');
    }

    final current = await Geolocator.getCurrentPosition(
      desiredAccuracy: LocationAccuracy.high,
    );

    print('📍 Current position: ${current.latitude}, ${current.longitude}');
    print('📍 Destination: $destLat, $destLong');

    final distanceInMeters = Geolocator.distanceBetween(
      current.latitude,
      current.longitude,
      destLat,
      destLong,
    );

    final distanceInKm = distanceInMeters / 1000;
    print('✅ Distance: $distanceInKm km');
    return distanceInKm;
  } catch (e) {
    print('❌ ERROR: $e');
    rethrow;
  }
}
