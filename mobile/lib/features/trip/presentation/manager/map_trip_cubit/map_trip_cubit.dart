import 'dart:async';
import 'dart:collection';
import 'dart:typed_data';
import 'dart:ui' as ui;

import 'package:bloc/bloc.dart';
import 'package:flutter/material.dart';
import 'package:google_maps_flutter/google_maps_flutter.dart';
import 'map_trip_state.dart';
import 'package:http/http.dart' as http;

import 'package:hawdaj/core/databases/api/end_points.dart';
import 'package:hawdaj/features/tasneef/data/models/unified_place_model.dart';

class MapTripCubit extends Cubit<MapTripState> {
  MapTripCubit() : super(MapTripState.initial());

  final Completer<GoogleMapController> _controller = Completer();

  static const int _cacheCap = 128;
  final LinkedHashMap<String, BitmapDescriptor> _iconCache =
      LinkedHashMap<String, BitmapDescriptor>();

  Future<void> onMapCreated(GoogleMapController c) async {
    if (!_controller.isCompleted) _controller.complete(c);
    await Future.delayed(const Duration(milliseconds: 200));
    fitAll();
  }

  // -------------------- بيانات الأيام --------------------
  void setDayGroups(List<List<UnifiedPlaceModel>> groups) {
    emit(state.copyWith(dayGroups: groups));
    final all = groups.expand((e) => e).toList();
    setPlacesWithImageMarkers(all); // اعرض الكل كماركرات صور
  }

  void showDay(int dayIndex) {
    final groups = state.dayGroups;
    if (groups == null || dayIndex < 1 || dayIndex > groups.length) return;

    final places = groups[dayIndex - 1];
    setPlacesWithImageMarkers(places); // تحديث ماركرات اليوم فقط
    emit(state.copyWith(selectedDay: dayIndex));
  }

  void clearDayFilter() {
    final all = (state.dayGroups ?? const <List<UnifiedPlaceModel>>[])
        .expand((e) => e)
        .toList();
    if (all.isEmpty) return;

    setPlacesWithImageMarkers(all);
    emit(state.copyWith(selectedDay: null));
  }

  // -------------------- MapType / UI Toggles --------------------
  void toggleMapTypeGroup1() {
    final next = (state.mapType == MapType.normal)
        ? MapType.satellite
        : MapType.normal;
    emit(state.copyWith(mapType: next));
  }

  void toggleMapTypeGroup2() {
    final next = (state.mapType == MapType.terrain)
        ? MapType.hybrid
        : MapType.terrain;
    emit(state.copyWith(mapType: next));
  }

  void toggleListSearchVisibility() {
    emit(state.copyWith(listVisible: !state.listVisible));
  }

  // -------------------- أماكن/ماركرز (بصور دائرية) --------------------
  /// نسخة مريحة لو بتحب تستدعيها من أماكن تانية
  void setPlaces(List<UnifiedPlaceModel> places) {
    setPlacesWithImageMarkers(places);
  }

  Future<void> setPlacesWithImageMarkers(List<UnifiedPlaceModel> places) async {
    // فضّي الماركرز الأول
    emit(state.copyWith(markers: {}));

    // نبني Futures للماركرز
    final futures = <Future<Marker?>>[];
    for (final p in places) {
      futures.add(_toImageMarker(p));
    }

    // حمل على دفعات لتقليل الضغط
    const maxConcurrent = 5;
    final collected = <Marker>{};
    for (int i = 0; i < futures.length; i += maxConcurrent) {
      final batch = futures.sublist(
        i,
        (i + maxConcurrent > futures.length)
            ? futures.length
            : i + maxConcurrent,
      );
      final markers = await Future.wait(batch);
      collected.addAll(markers.whereType<Marker>());
      // حدث الماركرز تدريجيًا عشان المستخدم يشوفهم
      emit(state.copyWith(markers: Set<Marker>.unmodifiable(collected)));
    }

    // ضبط الكاميرا
    if (collected.isNotEmpty) {
      final first = collected.first.position;
      emit(state.copyWith(cameraTarget: first));
      await Future.delayed(const Duration(milliseconds: 150));
      fitAll();
    }
  }

  Future<Marker?> _toImageMarker(UnifiedPlaceModel p) async {
    final lat = _toDouble(p.lat);
    final lng = _toDouble(p.long);
    if (lat == null || lng == null) return null;

    // اختر عنوان مناسب
    final title = (p.title?.trim().isNotEmpty ?? false)
        ? p.title!.trim()
        : (p.title?.trim().isNotEmpty ?? false)
        ? p.title!.trim()
        : 'مكان بدون اسم';

    // جهّز الأيقونة
    BitmapDescriptor icon;
    final imgPath = _extractImagePath(p);
    if (imgPath != null) {
      try {
        final url = _resolveImageUrl(imgPath);
        icon = await _getCircularMarkerIconFromUrl(
          url,
          logicalSize: 56,
          borderColor: Colors.white,
          borderWidth: 2,
        );
      } catch (_) {
        icon = BitmapDescriptor.defaultMarkerWithHue(BitmapDescriptor.hueRed);
      }
    } else {
      icon = BitmapDescriptor.defaultMarkerWithHue(BitmapDescriptor.hueRed);
    }

    final id = (p.slug?.trim().isNotEmpty ?? false)
        ? p.slug!.trim()
        : '${lat}_${lng}_${DateTime.now().microsecondsSinceEpoch}';

    return Marker(
      markerId: MarkerId(id),
      position: LatLng(lat, lng),
      icon: icon,
      infoWindow: InfoWindow(title: title),
    );
  }

  // -------------------- كاميرا الخريطة --------------------
  Future<void> fitAll() async {
    if (state.markers.isEmpty) return;
    final c = await _controller.future;

    if (state.markers.length == 1) {
      await c.animateCamera(
        CameraUpdate.newCameraPosition(
          CameraPosition(target: state.markers.first.position, zoom: 15),
        ),
      );
      return;
    }

    final b = _boundsFrom(state.markers.map((m) => m.position).toList());
    await c.animateCamera(CameraUpdate.newLatLngBounds(b, 60));
  }

  Future<void> focusOn(UnifiedPlaceModel p, {double zoom = 16}) async {
    final lat = _toDouble(p.lat);
    final lng = _toDouble(p.long);
    if (lat == null || lng == null) return;
    final c = await _controller.future;
    await c.animateCamera(
      CameraUpdate.newCameraPosition(
        CameraPosition(target: LatLng(lat, lng), zoom: zoom),
      ),
    );
  }

  // -------------------- Helpers --------------------
  String? _extractImagePath(UnifiedPlaceModel p) {
    // عدّل هنا لو عندك حقول مختلفة للصور
    final candidates = <String?>[
      p.image, // إن وجدت
      p.coverImage,
    ];
    return candidates.firstWhere(
      (e) => e != null && e.trim().isNotEmpty,
      orElse: () => null,
    );
  }

  String _resolveImageUrl(String path) {
    // لو الرابط كامل رجّعه، غير كده اعتبره relative
    if (path.startsWith('http://') || path.startsWith('https://')) return path;
    return '${EndPoints.imageUrl}$path';
  }

  Future<BitmapDescriptor> _getCircularMarkerIconFromUrl(
    String url, {
    required double logicalSize,
    Color borderColor = Colors.transparent,
    double borderWidth = 0,
    Duration timeout = const Duration(seconds: 10),
  }) async {
    final cacheKey = '$url|$logicalSize|${borderColor.value}|$borderWidth';
    final cached = _iconCache[cacheKey];
    if (cached != null) {
      // حدّث ترتيب LRU
      _iconCache.remove(cacheKey);
      _iconCache[cacheKey] = cached;
      return cached;
    }

    final uri = Uri.parse(url);
    final resp = await http.get(uri).timeout(timeout);
    if (resp.statusCode != 200) {
      throw Exception('Failed to load marker image');
    }

    final bytes = resp.bodyBytes;

    // DPI آمن حتى لو views فاضية
    final views = ui.PlatformDispatcher.instance.views;
    final dpr = views.isNotEmpty ? views.first.devicePixelRatio : 3.0;
    final int targetSize = (logicalSize * dpr).round().clamp(32, 512);

    final codec = await ui.instantiateImageCodec(
      bytes,
      targetWidth: targetSize,
      targetHeight: targetSize,
    );
    final frame = await codec.getNextFrame();
    final image = frame.image;

    final recorder = ui.PictureRecorder();
    final canvas = Canvas(recorder);
    final double size = targetSize.toDouble();
    final rect = Rect.fromLTWH(0, 0, size, size);
    final center = Offset(size / 2, size / 2);
    final radius = size / 2;

    if (borderWidth > 0) {
      final borderPaint = Paint()
        ..color = borderColor
        ..style = PaintingStyle.fill
        ..isAntiAlias = true;
      canvas.drawCircle(center, radius, borderPaint);
    }

    final clip = Path()
      ..addOval(Rect.fromCircle(center: center, radius: radius - borderWidth));
    canvas.save();
    canvas.clipPath(clip);

    final imgW = image.width.toDouble();
    final imgH = image.height.toDouble();
    final srcSize = imgW < imgH ? imgW : imgH;
    final src = Rect.fromCenter(
      center: Offset(imgW / 2, imgH / 2),
      width: srcSize,
      height: srcSize,
    );

    final paint = Paint()..isAntiAlias = true;
    canvas.drawImageRect(image, src, rect.deflate(borderWidth), paint);
    canvas.restore();

    final outputImage = await recorder.endRecording().toImage(
      targetSize,
      targetSize,
    );
    final byteData = await outputImage.toByteData(
      format: ui.ImageByteFormat.png,
    );
    if (byteData == null) throw Exception('Failed to encode marker image');

    final markerBytes = byteData.buffer.asUint8List();
    final descriptor = BitmapDescriptor.fromBytes(markerBytes);

    _iconCache[cacheKey] = descriptor;
    if (_iconCache.length > _cacheCap) {
      _iconCache.remove(_iconCache.keys.first);
    }

    return descriptor;
  }

  static LatLngBounds _boundsFrom(List<LatLng> pts) {
    double? x0, x1, y0, y1;
    for (final p in pts) {
      if (x0 == null) {
        x0 = x1 = p.latitude;
        y0 = y1 = p.longitude;
      } else {
        if (p.latitude > x1!) x1 = p.latitude;
        if (p.latitude < x0) x0 = p.latitude;
        if (p.longitude > y1!) y1 = p.longitude;
        if (p.longitude < y0!) y0 = p.longitude; // كان متعلّق عندك، أصلحته
      }
    }
    return LatLngBounds(
      southwest: LatLng(x0!, y0!),
      northeast: LatLng(x1!, y1!),
    );
  }

  double? _toDouble(Object? v) {
    if (v == null) return null;
    if (v is double) return v;
    if (v is int) return v.toDouble();
    return double.tryParse(v.toString());
  }

  Completer<GoogleMapController> get mapController => _controller;

  void addUserLocationMarker(LatLng position) {
    final newMarkers = Set<Marker>.from(state.markers)
      ..removeWhere(
        (m) => m.markerId.value == 'user_location',
      ) // ✅ إزالة القديم
      ..add(
        Marker(
          markerId: const MarkerId('user_location'),
          position: position,
          icon: BitmapDescriptor.defaultMarkerWithHue(
            BitmapDescriptor.hueAzure,
          ),
          infoWindow: const InfoWindow(title: 'موقعي الحالي'),
        ),
      );

    emit(state.copyWith(markers: newMarkers, cameraTarget: position));
  }
}
