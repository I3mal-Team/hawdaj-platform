import 'package:bloc/bloc.dart';
import 'package:flutter/material.dart';
import 'package:hawdaj/features/trip/data/repo/trip_repo.dart';

part 'save_trip_v2_state.dart';

class SaveTripV2Cubit extends Cubit<SaveTripV2State> {
  SaveTripV2Cubit(this.tripRepo) : super(SaveTripV2Initial());
  final TripRepo tripRepo;

  final tripNameController = TextEditingController();

  String? validateInputs() {
    if (tripNameController.text.trim().isEmpty) {
      return 'الرجاء إدخال اسم الرحلة';
    }
    return null;
  }

  Future<void> saveTripV2({
    required String token,
    required List<List<int>> items,
  }) async {
    emit(SaveTripV2Loading());
    final result = await tripRepo.saveTripV2(
      token: token,
      name: tripNameController.text.trim(),
      items: items,
    );
    result.fold(
      (failure) => emit(SaveTripV2Failure(failure.errMessage)),
      (r) => emit(SaveTripV2Success(r)),
    );
  }

  @override
  Future<void> close() {
    tripNameController.dispose(); // 👈 تنظيف الذاكرة
    return super.close();
  }
}
