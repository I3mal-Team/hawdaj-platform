import 'package:bloc/bloc.dart';
import 'package:hawdaj/features/tasneef/data/models/unified_place_model.dart';
import 'package:hawdaj/features/trip/data/model/trip_model/prepare_trip_data.dart';
import 'package:hawdaj/features/trip/data/model/trip_model/trip_model.dart';
import 'trip_plan_state.dart';

class TripPlanCubit extends Cubit<TripPlanState> {
  TripPlanCubit(this.model, this.tripModel) : super(TripPlanInitial()) {
    _init();
  }

  final TripData model;
  final TripModel tripModel;

  void _init() {
    final days = _buildDaysFromDates(model.startDate, model.endDate);
    final dailyPlaces = _extractDailyPlaces(tripModel.places);
    emit(TripPlanState(days: days, dailyPlaces: dailyPlaces));
  }

  /// حذف مكان من يوم معيّن
  void removePlace(int dayIndex, int placeIndex) {
    final curr = state.dailyPlaces;
    if (dayIndex < 0 || dayIndex >= curr.length) return;
    if (placeIndex < 0 || placeIndex >= curr[dayIndex].length) return;

    // نسخة جديدة علشان ما نعدلش على الأصلية
    final newDaily = curr.map((d) => List<UnifiedPlaceModel>.from(d)).toList();
    newDaily[dayIndex].removeAt(placeIndex);

    emit(state.copyWith(dailyPlaces: newDaily));
  }

  /// استخراج IDs للأماكن بعد التعديل (تستخدم في الحفظ/المشاركة)
  List<List<int>> mapDailyPlacesToIds() {
    return state.dailyPlaces
        .map(
          (day) => day
              .map((p) => int.tryParse(p.id?.toString() ?? '') ?? 0)
              .where((id) => id != 0)
              .toList(),
        )
        .toList();
  }

  // ------------------ Helpers ------------------
  List<TripDayVM> _buildDaysFromDates(String? start, String? end) {
    final startDt = _parseDate(start);
    final endDt = _parseDate(end);

    if (startDt == null) {
      return [
        TripDayVM(
          index: 1,
          displayDate: (start?.trim().isNotEmpty ?? false)
              ? start!.trim()
              : '—',
        ),
      ];
    }
    if (endDt == null || !endDt.isAfter(startDt)) {
      return [TripDayVM(index: 1, displayDate: _formatArabicDate(startDt))];
    }

    final result = <TripDayVM>[];
    var cursor = DateTime(startDt.year, startDt.month, startDt.day);
    var i = 1;
    while (!cursor.isAfter(endDt)) {
      result.add(TripDayVM(index: i++, displayDate: _formatArabicDate(cursor)));
      cursor = cursor.add(const Duration(days: 1));
    }
    return result;
  }

  String _formatArabicDate(DateTime date) {
    const months = [
      'يناير',
      'فبراير',
      'مارس',
      'أبريل',
      'مايو',
      'يونيو',
      'يوليو',
      'أغسطس',
      'سبتمبر',
      'أكتوبر',
      'نوفمبر',
      'ديسمبر',
    ];
    return '${date.day} ${months[date.month - 1]} ${date.year}';
  }

  DateTime? _parseDate(String? value) {
    if (value == null || value.trim().isEmpty) return null;

    final iso = DateTime.tryParse(value);
    if (iso != null) return iso;

    final partsSlash = value.split('/');
    if (partsSlash.length == 3) {
      final y = int.tryParse(partsSlash[0]);
      final m = int.tryParse(partsSlash[1]);
      final d = int.tryParse(partsSlash[2]);
      if (y != null && m != null && d != null) return DateTime(y, m, d);
    }

    final partsDash = value.split('-');
    if (partsDash.length == 3) {
      final d = int.tryParse(partsDash[0]);
      final m = int.tryParse(partsDash[1]);
      final y = int.tryParse(partsDash[2]);
      if (d != null && m != null && y != null) return DateTime(y, m, d);
    }
    return null;
  }

  List<List<UnifiedPlaceModel>> _extractDailyPlaces(dynamic raw) {
    if (raw == null) return const <List<UnifiedPlaceModel>>[];

    if (raw is List) {
      return raw.map<List<UnifiedPlaceModel>>((dayRaw) {
        if (dayRaw is List) {
          return dayRaw.map<UnifiedPlaceModel>((e) {
            if (e is UnifiedPlaceModel) return e;
            return UnifiedPlaceModel.fromJson(e);
          }).toList();
        }
        if (dayRaw is UnifiedPlaceModel) return <UnifiedPlaceModel>[dayRaw];
        return <UnifiedPlaceModel>[UnifiedPlaceModel.fromJson(dayRaw)];
      }).toList();
    }
    return const <List<UnifiedPlaceModel>>[];
  }
}
