import 'package:easy_localization/easy_localization.dart';
import 'package:flutter/material.dart';
import 'package:flutter_bloc/flutter_bloc.dart';
import 'package:flutter_screenutil/flutter_screenutil.dart';
import 'package:geolocator/geolocator.dart';
import 'package:google_maps_flutter/google_maps_flutter.dart';
import 'package:hawdaj/core/components/primary_button.dart';
import 'package:hawdaj/core/managers/location_cubit/location_cubit.dart';
import 'package:geocoding/geocoding.dart';
import 'package:hawdaj/features/trip/presentation/view/widgets/trip_start_app_bar.dart';

class MapPickerView extends StatefulWidget {
  const MapPickerView({super.key});

  @override
  State<MapPickerView> createState() => _MapPickerViewState();
}

class _MapPickerViewState extends State<MapPickerView> {
  GoogleMapController? mapController;
  LatLng? selectedPosition;
  String? selectedAddress;

  final LatLng defaultCenter = const LatLng(24.7136, 46.6753); // Riyadh

  @override
  void initState() {
    super.initState();
    _initWithCubitLocation();
  }

  void _initWithCubitLocation() {
    final locationState = context.read<LocationCubit>().state;
    if (locationState is LocationLoaded) {
      final pos = locationState.position;
      setState(() {
        selectedPosition = LatLng(pos.latitude, pos.longitude);
      });
      _getAddressFromLatLng(selectedPosition!);
    } else {
      // fallback to default and try to fetch location manually
      _getCurrentLocation();
    }
  }

  Future<void> _getAddressFromLatLng(LatLng position) async {
    try {
      List<Placemark> placemarks = await placemarkFromCoordinates(
        position.latitude,
        position.longitude,
      );

      if (placemarks.isNotEmpty) {
        Placemark place = placemarks[0];
        setState(() {
          selectedAddress =
              '${place.street}, ${place.subLocality}, ${place.locality}, ${place.country}';
        });
      }
    } catch (e) {
      debugPrint('Error getting address: $e');
    }
  }

  Future<void> _getCurrentLocation() async {
    try {
      bool serviceEnabled = await Geolocator.isLocationServiceEnabled();
      if (!serviceEnabled) return;

      LocationPermission permission = await Geolocator.checkPermission();
      if (permission == LocationPermission.denied) {
        permission = await Geolocator.requestPermission();
        if (permission == LocationPermission.denied ||
            permission == LocationPermission.deniedForever)
          return;
      }

      final position = await Geolocator.getCurrentPosition(
        desiredAccuracy: LocationAccuracy.high,
      );

      final currentLatLng = LatLng(position.latitude, position.longitude);

      setState(() {
        selectedPosition = currentLatLng;
      });
      _getAddressFromLatLng(currentLatLng);

      mapController?.animateCamera(CameraUpdate.newLatLng(currentLatLng));
    } catch (e) {
      // handle error if needed
    }
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      body: Column(
        children: [
          SizedBox(
            height: 120.h,
            child: TripStartAppBar(title: 'choose_location_on_map'.tr()),
          ),
          Expanded(
            child: GoogleMap(
              initialCameraPosition: CameraPosition(
                target: selectedPosition ?? defaultCenter,
                zoom: 12,
              ),
              onMapCreated: (controller) {
                mapController = controller;
                if (selectedPosition != null) {
                  mapController!.animateCamera(
                    CameraUpdate.newLatLng(selectedPosition!),
                  );
                }
              },
              onTap: (LatLng position) {
                setState(() {
                  selectedPosition = position;
                });
                _getAddressFromLatLng(position);
              },
              markers: selectedPosition != null
                  ? {
                      Marker(
                        markerId: const MarkerId('selected'),
                        position: selectedPosition!,
                      ),
                    }
                  : {},
            ),
          ),
          if (selectedAddress != null)
            Container(
              padding: EdgeInsets.symmetric(horizontal: 16.w, vertical: 12.h),
              margin: EdgeInsets.symmetric(horizontal: 16.w, vertical: 8.h),
              decoration: BoxDecoration(
                color: Colors.white,
                borderRadius: BorderRadius.circular(12.r),
                boxShadow: [
                  BoxShadow(
                    color: Colors.black.withOpacity(0.05),
                    blurRadius: 10,
                    offset: const Offset(0, 4),
                  ),
                ],
              ),
              child: Row(
                children: [
                  Icon(
                    Icons.location_on,
                    color: Theme.of(context).primaryColor,
                  ),
                  SizedBox(width: 8.w),
                  Expanded(
                    child: Text(
                      selectedAddress!,
                      style: TextStyle(
                        fontSize: 14.sp,
                        fontWeight: FontWeight.w500,
                      ),
                      maxLines: 2,
                      overflow: TextOverflow.ellipsis,
                    ),
                  ),
                ],
              ),
            ),
          Padding(
            padding: const EdgeInsets.all(8.0),
            child: PrimaryButton(
              title: 'confirm'.tr(),
              onTap: () {
                if (selectedPosition != null) {
                  Navigator.pop(context, {
                    'position': selectedPosition,
                    'address': selectedAddress,
                  });
                }
              },
            ),
          ),
        ],
      ),
    );
  }
}
