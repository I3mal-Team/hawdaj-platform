import 'package:dio/dio.dart';
import 'package:hawdaj/core/managers/location_cubit/location_cache.dart';

class LocationInterceptor extends Interceptor {
  @override
  void onRequest(RequestOptions options, RequestInterceptorHandler handler) {
    final lat = LocationCache.latitude;
    final lng = LocationCache.longitude;

    if (lat != null && lng != null) {
      options.headers['X-Device-Latitude'] = lat.toString();
      options.headers['X-Device-Longitude'] = lng.toString();
    }

    super.onRequest(options, handler);
  }
}
