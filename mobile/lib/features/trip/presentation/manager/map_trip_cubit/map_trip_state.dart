import 'package:equatable/equatable.dart';
import 'package:google_maps_flutter/google_maps_flutter.dart';
import 'package:hawdaj/features/tasneef/data/models/unified_place_model.dart';

class MapTripState extends Equatable {
  final MapType mapType;
  final bool listVisible;
  final Set<Marker> markers;
  final LatLng cameraTarget;
  final double cameraZoom;
  final List<List<UnifiedPlaceModel>>? dayGroups; // جروبات الأيام
  final int? selectedDay; // اليوم المختار (1-based)
  final String? error;

  const MapTripState({
    required this.mapType,
    required this.listVisible,
    required this.markers,
    required this.cameraTarget,
    required this.cameraZoom,
    this.dayGroups,
    this.selectedDay,
    this.error,
  });

  factory MapTripState.initial() => const MapTripState(
    mapType: MapType.normal,
    listVisible: false,
    markers: {},
    cameraTarget: LatLng(24.0, 43.0),
    cameraZoom: 5.0,
    dayGroups: null,
    selectedDay: null,
    error: null,
  );

  MapTripState copyWith({
    MapType? mapType,
    bool? listVisible,
    Set<Marker>? markers,
    LatLng? cameraTarget,
    double? cameraZoom,
    List<List<UnifiedPlaceModel>>? dayGroups,
    int? selectedDay,
    String? error,
  }) {
    return MapTripState(
      mapType: mapType ?? this.mapType,
      listVisible: listVisible ?? this.listVisible,
      markers: markers ?? this.markers,
      cameraTarget: cameraTarget ?? this.cameraTarget,
      cameraZoom: cameraZoom ?? this.cameraZoom,
      dayGroups: dayGroups ?? this.dayGroups,
      // 👇 أهم تعديل: لو ما بعتّش selectedDay، حافظ على القيمة الحالية
      selectedDay: selectedDay ?? this.selectedDay,
      // 👇 ونفس الفكرة للخطأ
      error: error ?? this.error,
    );
  }

  @override
  List<Object?> get props => [
    mapType,
    listVisible,
    markers,
    cameraTarget,
    cameraZoom,
    dayGroups,
    selectedDay,
    error,
  ];
}
