import 'package:bloc/bloc.dart';
import 'package:equatable/equatable.dart';
import 'package:hawdaj/features/trip/data/model/enhanced_trip_response.dart';
import 'package:hawdaj/features/trip/data/repo/trip_repo.dart';

part 'prepare_trip_show_state.dart';

class PrepareTripShowCubit extends Cubit<PrepareTripShowState> {
  final TripRepo tripRepo;
  final String token;

  PrepareTripShowCubit(this.tripRepo, this.token)
    : super(PrepareTripShowInitial()) {
    showTrip();
  }

  Future<void> showTrip() async {
    emit(PrepareTripShowLoading());

    final result = await tripRepo.showPrepareTrip(token);

    result.fold(
      (failure) => emit(PrepareTripShowFailure(failure.errMessage)),
      (response) => emit(PrepareTripShowSuccess(response)),
    );
  }

  /// حذف مكان من يوم معيّن في الفترة (morning/evening)
  void removePlaceFromDay({
    required int dayIndex,
    required String period, // "morning" or "evening"
    required int placeIndex,
  }) {
    final currentState = state;
    if (currentState is! PrepareTripShowSuccess) return;

    final data = currentState.response.data;
    if (data == null || dayIndex >= data.enhancedData.length) return;

    final day = data.enhancedData[dayIndex];

    final morningCount = day.morning?.places.length ?? 0;
    final eveningCount = day.evening?.places.length ?? 0;
    final totalPlaces = morningCount + eveningCount;

    print(
      '🔹 قبل الحذف: صباحًا = $morningCount | مساءً = $eveningCount | المجموع = $totalPlaces',
    );

    // ❌ لا نحذف لو مفيش غير مكان واحد فقط في اليوم كله
    if (totalPlaces <= 1) {
      print('🚫 لا يمكن حذف آخر مكان في اليوم');
      return;
    }

    EnhancedPeriod? updatedMorning = day.morning;
    EnhancedPeriod? updatedEvening = day.evening;

    if (period == "morning") {
      final morningPlaces = List<Place>.from(day.morning?.places ?? []);
      if (placeIndex >= 0 && placeIndex < morningPlaces.length) {
        morningPlaces.removeAt(placeIndex);
        updatedMorning = day.morning?.copyWith(places: morningPlaces);
        print('✅ حذف من الصباح، العدد الآن: ${morningPlaces.length}');
      } else {
        print('⚠️ الفهرس غير صالح في الصباح');
      }
    } else if (period == "evening") {
      final eveningPlaces = List<Place>.from(day.evening?.places ?? []);
      if (placeIndex >= 0 && placeIndex < eveningPlaces.length) {
        eveningPlaces.removeAt(placeIndex);
        updatedEvening = day.evening?.copyWith(places: eveningPlaces);
        print('✅ حذف من المساء، العدد الآن: ${eveningPlaces.length}');
      } else {
        print('⚠️ الفهرس غير صالح في المساء');
      }
    }

    final updatedDay = day.copyWith(
      morning: updatedMorning,
      evening: updatedEvening,
    );

    final updatedDays = [...data.enhancedData];
    updatedDays[dayIndex] = updatedDay;

    final updatedData = data.copyWith(enhancedData: updatedDays);
    final updatedResponse = currentState.response.copyWith(data: updatedData);

    emit(PrepareTripShowSuccess(updatedResponse));

    // 🔹 بعد الحذف
    final newMorningCount = updatedMorning?.places.length ?? 0;
    final newEveningCount = updatedEvening?.places.length ?? 0;
    final newTotal = newMorningCount + newEveningCount;

    print(
      '🔸 بعد الحذف: صباحًا = $newMorningCount | مساءً = $newEveningCount | المجموع = $newTotal',
    );
    print('✅ الحالة الجديدة أُرسلت بنجاح');
  }

  /// 👇 منطق مركزي: التحقق هل مسموح حذف المكان أم لا
  bool canDeletePlace({required int dayIndex, required String period}) {
    final currentState = state;
    if (currentState is! PrepareTripShowSuccess) return false;

    final data = currentState.response.data;
    if (data == null || dayIndex >= data.enhancedData.length) return false;

    final day = data.enhancedData[dayIndex];
    final morningCount = day.morning?.places.length ?? 0;
    final eveningCount = day.evening?.places.length ?? 0;
    final totalPlaces = morningCount + eveningCount;

    // ❌ لو مفيش غير مكان واحد في اليوم كله → لا نحذف
    if (totalPlaces <= 1) return false;

    return true;
  }

  // 🔒 نقل مكان من فترة إلى أخرى داخل نفس اليوم
  void movePlaceToOtherPeriod({
    required int dayIndex,
    required String fromPeriod, // 'morning' or 'evening'
    required int placeIndex,
  }) {
    final currentState = state;
    if (currentState is! PrepareTripShowSuccess) return;

    final data = currentState.response.data;
    if (data == null || dayIndex >= data.enhancedData.length) return;

    final day = data.enhancedData[dayIndex];
    final updatedMorning = List<Place>.from(day.morning?.places ?? []);
    final updatedEvening = List<Place>.from(day.evening?.places ?? []);

    if (fromPeriod == 'morning') {
      if (placeIndex >= 0 && placeIndex < updatedMorning.length) {
        final place = updatedMorning.removeAt(placeIndex);
        updatedEvening.add(place);
      }
    } else if (fromPeriod == 'evening') {
      if (placeIndex >= 0 && placeIndex < updatedEvening.length) {
        final place = updatedEvening.removeAt(placeIndex);
        updatedMorning.add(place);
      }
    }

    final updatedDay = day.copyWith(
      morning: day.morning?.copyWith(places: updatedMorning),
      evening: day.evening?.copyWith(places: updatedEvening),
    );

    final updatedDays = [...data.enhancedData];
    updatedDays[dayIndex] = updatedDay;

    final updatedData = data.copyWith(enhancedData: updatedDays);
    final updatedResponse = currentState.response.copyWith(data: updatedData);

    emit(PrepareTripShowSuccess(updatedResponse));
  }
}
