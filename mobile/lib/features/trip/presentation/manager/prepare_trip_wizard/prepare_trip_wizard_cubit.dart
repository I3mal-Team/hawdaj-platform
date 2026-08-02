import 'dart:async';
import 'dart:convert';

import 'package:bloc/bloc.dart';
import 'package:easy_localization/easy_localization.dart';
import 'package:equatable/equatable.dart';
import 'package:flutter/widgets.dart';
import 'package:flutter_bloc/flutter_bloc.dart';
import 'package:hawdaj/core/components/custom_failure_toast.dart';
import 'package:hawdaj/features/trip/data/model/new_trip_prepare_request.dart';
import 'package:hawdaj/features/trip/presentation/manager/prepare_trip_cubit/prepare_trip_cubit.dart';
import 'package:shared_preferences/shared_preferences.dart';

import 'package:hawdaj/features/trip/data/model/trip_model/prepare_trip_model.dart';

part 'prepare_trip_wizard_state.dart';

class PrepareTripWizardCubit extends Cubit<PrepareTripWizardState> {
  PrepareTripWizardCubit()
    : super(
        PrepareTripWizardState(
          draft: const PrepareTripModel(
            categories: [],
            daterange: '',
            funnyPlacePerDay: '',
            lat1: '',
            lat2: '',
            long1: '',
            long2: '',
            price: [],
            region1: '',
            region2: '',
            type: 'trip',
            vehicleType: '',
          ),
        ),
      );

  static const _kDraftKey = 'prepare_trip_draft_v1';
  static const int _stepsCount = 4; // 0..3
  bool get isLastStep => state.index == _stepsCount - 1;
  Future<void> submit(BuildContext context, {bool useNew = false}) async {
    if (!_canProceed(_stepsCount - 1)) {
      final msg = _validationMessage(_stepsCount - 1);
      emit(state.copyWith(error: msg));
      showCustomFailureToast(msg);
      return;
    }

    final updatedDraft = state.draft.copyWith(
      funnyPlacePerDay: useNew
          ? state.draft.funnyPlacePerDay
          : totalPlaces.toString(),
    );

    emit(state.copyWith(draft: updatedDraft)); // تحديث الستيت

    final prepareCubit = context.read<PrepareTripCubit>();

    await prepareCubit.prepareTrip(
      modelV1: updatedDraft,
      modelV2: getNewTripPrepareParams(),
      useNew: useNew,
    );

    // ignore: avoid_print
    print('=== SUBMIT DRAFT ===');
    print(updatedDraft.toJson());
  }

  Future<void> onPrimaryPressed(
    BuildContext context, {
    bool useNew = false,
  }) async {
    if (isLastStep) {
      await submit(context, useNew: useNew);
    } else {
      await next();
    }
  }

  // نحتفظ بالبداية والنهاية داخليًا (من غير ما نعدل الموديل)
  DateTime? _start;
  DateTime? _end;
  DateTime? get startDate => _start;
  DateTime? get endDate => _end;

  String? get startDateStr => _start == null ? null : _fmt(_start!);
  String? get endDateStr => _end == null ? null : _fmt(_end!);

  // PageView
  final PageController pageController = PageController();

  Timer? _saveDebounce;

  // ===== Utilities =====
  String _fmt(DateTime d) =>
      '${d.year.toString().padLeft(4, '0')}/${d.month.toString().padLeft(2, '0')}/${d.day.toString().padLeft(2, '0')}';

  String _buildRange(DateTime start, DateTime end) =>
      '${_fmt(end)}-${_fmt(start)}'; // end-start

  int _daysInclusive(DateTime start, DateTime end) =>
      end.difference(start).inDays + 1;

  void _commitRange() {
    if (_start == null || _end == null) return;

    // تأكد من الترتيب
    var s = _start!;
    var e = _end!;
    if (e.isBefore(s)) {
      final tmp = s;
      s = e;
      e = tmp;
    }

    final range = _buildRange(s, e); // end-start
    emit(state.copyWith(draft: state.draft.copyWith(daterange: range)));
    _scheduleAutoSave();

    final days = _daysInclusive(s, e);
    // اطبع الفرق والـ daterange
    // مثال: عدد أيام الرحلة: 9  |  daterange: 2025/08/26-2025/08/18
    // (ملاحظة: بالصيغة end-start حسب المطلوب)
    // لو عايزها start-end بدّل الـ _buildRange
    // هنا بنطبع الاتنين، وكمان صيغة start-end للوضوح
    // لكن الـ draft يخزن end-start فقط.
    // ignore: avoid_print
    print('عدد أيام الرحلة: $days');
    print('start Date : ${startDateStr ?? "not_specified".tr()}');
    // ignore: avoid_print
    print('daterange: $range');
  }

  // ===== Public setters for date pickers =====
  void setStartDate(DateTime date) {
    _start = DateTime(date.year, date.month, date.day);
    // لو النهاية موجودة وأقدم من البداية، نساويها بالبداية
    if (_end != null && _end!.isBefore(_start!)) {
      _end = _start;
    }
    _commitRange();
  }

  void setEndDate(DateTime date) {
    _end = DateTime(date.year, date.month, date.day);
    // لو البداية موجودة وأحدث من النهاية، نساوي البداية بالنهاية
    if (_start != null && _start!.isAfter(_end!)) {
      _start = _end;
    }
    _commitRange();
  }

  // Getter اختياري لو حبيت تستخدمه في الواجهة
  int get tripDays {
    final r = state.draft.daterange;
    if (r.isEmpty || !r.contains('-')) return 0;
    try {
      final parts = r.split('-'); // [end, start]
      final end = DateTime.parse(parts[0].replaceAll('/', '-'));
      final start = DateTime.parse(parts[1].replaceAll('/', '-'));
      return _daysInclusive(start, end);
    } catch (_) {
      return 0;
    }
  }

  // ===== Navigation =====
  Future<void> goTo(int i) async {
    if (i < 0) return;
    emit(state.copyWith(index: i));
    await pageController.animateToPage(
      i,
      duration: const Duration(milliseconds: 300),
      curve: Curves.easeInOut,
    );
  }

  String _validationMessage(int step) {
    switch (step) {
      case 0:
        return 'validation_step_0'.tr();
      case 1:
        return 'validation_step_1'.tr();
      case 2:
        return 'validation_step_2'.tr();
      case 3:
        return 'validation_step_3'.tr();
      default:
        return 'validation_default'.tr();
    }
  }

  bool _canProceed(int step) {
    switch (step) {
      case 0:
        return state.draft.daterange.isNotEmpty;
      case 1:
        return state.draft.region1.isNotEmpty && state.draft.region2.isNotEmpty;
      case 2:
        return state.draft.funnyPlacePerDay.isNotEmpty &&
            state.draft.price.isNotEmpty &&
            state.draft.vehicleType.isNotEmpty;
      case 3:
        return state.draft.categories.isNotEmpty;
      default:
        return true;
    }
  }

  Future<void> next() async {
    final i = state.index;
    if (!_canProceed(i)) {
      final msg = _validationMessage(i);

      emit(state.copyWith(error: msg));
      showCustomFailureToast(msg);
      return;
    }
    await goTo(i + 1);
  }

  Future<void> back() async => goTo(state.index - 1);

  // ===== Persistence =====
  Future<void> hydrate() async {
    final prefs = await SharedPreferences.getInstance();
    final raw = prefs.getString(_kDraftKey);
    if (raw != null && raw.isNotEmpty) {
      final map = json.decode(raw) as Map<String, dynamic>;
      final draft = PrepareTripModel.fromJson(map);
      emit(state.copyWith(draft: draft));

      // لو لاقينا daterange محفوظ، نعيد ملء _start/_end للاتساق
      final r = draft.daterange;
      if (r.isNotEmpty && r.contains('-')) {
        try {
          final parts = r.split('-'); // [end, start]
          _end = DateTime.parse(parts[0].replaceAll('/', '-'));
          _start = DateTime.parse(parts[1].replaceAll('/', '-'));
        } catch (_) {}
      }
    }
  }

  void _scheduleAutoSave() {
    _saveDebounce?.cancel();
    _saveDebounce = Timer(const Duration(milliseconds: 450), saveDraft);
    if (!state.saving) emit(state.copyWith(saving: true));
  }

  PrepareTripModel getPrepareTripModel() {
    return state.draft.copyWith(funnyPlacePerDay: totalPlaces.toString());
  }

  NewTripPrepareParams getNewTripPrepareParams() {
    final start = _start ?? DateTime.now();
    final end = _end ?? DateTime.now();

    return NewTripPrepareParams(
      startDate: _fmt(start).replaceAll('/', '-'),
      endDate: _fmt(end).replaceAll('/', '-'),
      startRegionId: state.draft.region1,
      endRegionId: state.draft.region2,
      placesPerDay: state.draft.funnyPlacePerDay,
      categories: state.draft.categories.map((e) => e.toString()).toList(),
      priceRange: state.draft.price.map((e) => e.toString()).toList(),
      vehicleType: state.draft.vehicleType,
    );
  }

  Future<void> saveDraft() async {
    try {
      final prefs = await SharedPreferences.getInstance();
      await prefs.setString(_kDraftKey, json.encode(state.draft.toJson()));
      emit(state.copyWith(saving: false));
    } catch (e) {
      emit(state.copyWith(saving: false, error: e.toString()));
    }
  }

  Future<void> clearDraft() async {
    final prefs = await SharedPreferences.getInstance();
    await prefs.remove(_kDraftKey);
    _start = null;
    _end = null;
    emit(
      state.copyWith(
        draft: state.draft.copyWith(
          categories: [],
          daterange: '',
          funnyPlacePerDay: '',
          lat1: '',
          lat2: '',
          long1: '',
          long2: '',
          price: [],
          region1: '',
          region2: '',
          type: 'trip',
          vehicleType: '',
        ),
      ),
    );
  }

  int get placesPerDay {
    final s = state.draft.funnyPlacePerDay.trim();
    final n = int.tryParse(s);

    return (n == null || n < 0) ? 0 : n;
  }

  int get totalPlaces => tripDays * placesPerDay;
  // ===== Setters الأخرى =====
  void setCategories(List<int> v) {
    emit(state.copyWith(draft: state.draft.copyWith(categories: v)));
    _scheduleAutoSave();
  }

  void setDateRange(String v) {
    // لو عندك مصدر خارجي بيرسل range جاهز بصيغة end-start
    emit(state.copyWith(draft: state.draft.copyWith(daterange: v)));
    _scheduleAutoSave();
    // اطبع للتأكد
    // ignore: avoid_print
    print('daterange: $v');
    print('startDate: ${startDateStr ?? 'غير محدد'}');
    print('endDate: ${endDateStr ?? 'غير محدد'}');
    // ignore: avoid_print
    print('عدد أيام الرحلة: $tripDays');
  }

  /// يمسح اختيارات الويزارد فقط (تاريخ/مناطق/فئات/أسعار/إحداثيات) ويرجّع للس tep الأولى.
  /// [persist] = true لو عايز تحفظ التصفير في SharedPreferences، وإلا تصفير بالذاكرة فقط.
  Future<void> resetWizardSelections({bool persist = false}) async {
    _start = null;
    _end = null;

    emit(
      state.copyWith(
        index: 0,
        draft: state.draft.copyWith(
          daterange: '',
          region1: '',
          region2: '',
          funnyPlacePerDay: '',
          categories: [],
          price: [],
          lat1: '',
          long1: '',
          lat2: '',
          long2: '',
          // نسيب type/vehicleType زي ما هما
        ),
        saving: false,
        error: null,
      ),
    );

    // نرجّع الـ PageView لأول صفحة لو متوصّل
    if (pageController.hasClients) {
      pageController.jumpToPage(0);
    }

    if (persist) {
      await saveDraft(); // نحفظ التصفير كمسودة
    } else {
      _saveDebounce?.cancel(); // إلغاء أي حفظ معلّق
    }
  }

  void setFunnyPerDay(String v) {
    emit(state.copyWith(draft: state.draft.copyWith(funnyPlacePerDay: v)));
    _scheduleAutoSave();
  }

  String? _region1Name;
  String? _region2Name;

  String? get region1Name => _region1Name;
  String? get region2Name => _region2Name;

  void setRegion1(String v, String name) {
    _region1Name = name; // حفظ الاسم للعرض فقط
    emit(state.copyWith(draft: state.draft.copyWith(region1: v)));
    _scheduleAutoSave();
  }

  void setRegion2(String v, String name) {
    // ignore: avoid_print
    _region2Name = name; // حفظ الاسم للعرض فقط
    print('Setting region2: $v');
    emit(state.copyWith(draft: state.draft.copyWith(region2: v)));
    _scheduleAutoSave();
  }

  void setCoords1({required String lat, required String lng}) {
    emit(
      state.copyWith(
        draft: state.draft.copyWith(lat1: lat, long1: lng),
      ),
    );
    _scheduleAutoSave();
  }

  void setCoords2({required String lat, required String lng}) {
    emit(
      state.copyWith(
        draft: state.draft.copyWith(lat2: lat, long2: lng),
      ),
    );
    _scheduleAutoSave();
  }

  void setPrices(List<int> v) {
    emit(state.copyWith(draft: state.draft.copyWith(price: v)));
    _scheduleAutoSave();
  }

  void setType(String v) {
    emit(state.copyWith(draft: state.draft.copyWith(type: v)));
    _scheduleAutoSave();
  }

  void setVehicleType(String v) {
    emit(state.copyWith(draft: state.draft.copyWith(vehicleType: v)));
    _scheduleAutoSave();
  }

  @override
  Future<void> close() {
    _saveDebounce?.cancel();
    pageController.dispose();
    return super.close();
  }
}
