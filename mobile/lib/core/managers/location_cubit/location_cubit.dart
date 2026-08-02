import 'dart:async';

import 'package:easy_localization/easy_localization.dart';
import 'package:flutter_bloc/flutter_bloc.dart';
import 'package:geolocator/geolocator.dart';
import 'package:hawdaj/core/managers/location_cubit/location_cache.dart';
import 'package:hawdaj/core/repositories/location_repository.dart';

part 'location_state.dart';

class LocationCubit extends Cubit<LocationState> {
  final LocationRepository locationRepository;
  Timer? _timer;

  LocationCubit({required this.locationRepository}) : super(LocationInitial());

  Future<void> fetchCurrentLocation() async {
    emit(LocationLoading());

    try {
      bool serviceEnabled = await Geolocator.isLocationServiceEnabled();
      if (!serviceEnabled) throw Exception('location_permission_denied'.tr());

      LocationPermission permission = await Geolocator.checkPermission();
      if (permission == LocationPermission.denied) {
        permission = await Geolocator.requestPermission();
        if (permission == LocationPermission.denied) {
          throw Exception('location_permission_denied'.tr());
        }
      }

      if (permission == LocationPermission.deniedForever) {
        throw Exception('location_permission_denied'.tr());
      }

      final position = await Geolocator.getCurrentPosition(
        desiredAccuracy: LocationAccuracy.high,
      );

      // 🔥 حفظ أول إحداثيات يتم جلبها
      LocationCache.saveLocation(position.latitude, position.longitude);

      emit(LocationLoaded(position));
      _startPeriodicLocationUpdate();
    } catch (e) {
      emit(LocationError(e.toString()));
    }
  }

  void _startPeriodicLocationUpdate() {
    _timer?.cancel();
    _timer = Timer.periodic(const Duration(seconds: 30), (timer) async {
      print('---->Updating location...');
      try {
        final position = await Geolocator.getCurrentPosition(
          desiredAccuracy: LocationAccuracy.high,
        );

        // 🔥 تحديث الإحداثيات في الكاش كل 30 ثانية
        LocationCache.saveLocation(position.latitude, position.longitude);

        await locationRepository.updateLocation(
          lat: position.latitude,
          lng: position.longitude,
        );
      } catch (e) {
        print('Error updating location: $e');
      }
    });
  }

  @override
  Future<void> close() {
    _timer?.cancel();
    return super.close();
  }
}
