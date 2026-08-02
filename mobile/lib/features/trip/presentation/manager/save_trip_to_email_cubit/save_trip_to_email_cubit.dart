// save_trip_to_email_cubit.dart
import 'dart:convert';
import 'package:bloc/bloc.dart';
import 'package:equatable/equatable.dart';
import 'package:flutter/material.dart';
import 'package:hawdaj/features/trip/data/model/trip_model/save_trip_to_email_params.dart';
import 'package:hawdaj/features/trip/data/repo/trip_repo.dart';

part 'save_trip_to_email_state.dart';

class SaveTripToEmailCubit extends Cubit<SaveTripToEmailState> {
  SaveTripToEmailCubit(this.tripRepo) : super(SaveTripToEmailInitial());

  final TripRepo tripRepo;

  // Controllers تمسكها من الـ UI مباشرة
  final TextEditingController emailController = TextEditingController();
  final TextEditingController nameController =
      TextEditingController(); // user_name
  final TextEditingController tripNameController =
      TextEditingController(); // name (اسم الرحلة)

  /// تنظيف الموارد
  void dispose() {
    emailController.dispose();
    nameController.dispose();
    tripNameController.dispose();
  }

  /// فالدية سريعة للإيميل
  bool _isValidEmail(String v) {
    final s = v.trim();
    if (s.isEmpty) return false;
    final re = RegExp(r'^[^\s@]+@[^\s@]+\.[^\s@]+$');
    return re.hasMatch(s);
  }

  /// يتحقق من الحقول الأساسية (الاسم، الإيميل، اسم الرحلة)
  /// ويرجع رسالة خطأ مناسبة أو null لو كل شيء تمام
  String? validateInputs() {
    if (nameController.text.trim().isEmpty) {
      return 'الاسم مطلوب';
    }
    if (!_isValidEmail(emailController.text)) {
      return 'البريد الإلكتروني غير صالح';
    }
    if (tripNameController.text.trim().isEmpty) {
      return 'اسم الرحلة مطلوب';
    }
    return null; // OK
  }

  /// يبني Params من الكونترولرز + باقي باراميترات الرحلة
  SaveTripToEmailParams buildParams({
    required String date,
    required String days,
    required String endDate,
    required String itemPerDay,
    required List<List<int>> items,
    required String region1,
    required String region2,
    required String startDate,
  }) {
    return SaveTripToEmailParams(
      date: date,
      days: days,
      email: emailController.text.trim(),
      endDate: endDate,
      itemPerDay: itemPerDay,
      items: items,
      name: tripNameController.text.trim(), // اسم الرحلة
      region1: region1,
      region2: region2,
      startDate: startDate,
      userName: nameController.text.trim(), // الاسم
    );
  }

  /// نسخة بديلة لو الـ items جاية كنص JSON
  SaveTripToEmailParams buildParamsFromItemsString({
    required String date,
    required String days,
    required String endDate,
    required String itemPerDay,
    required String itemsAsJsonString,
    required String region1,
    required String region2,
    required String startDate,
  }) {
    final decoded = (itemsAsJsonString.trim().isNotEmpty)
        ? (jsonDecode(itemsAsJsonString) as List)
        : const <dynamic>[];

    final items = decoded
        .map<List<int>>(
          (inner) => (inner as List)
              .map<int>((e) => int.tryParse(e.toString()) ?? 0)
              .toList(),
        )
        .toList();

    return buildParams(
      date: date,
      days: days,
      endDate: endDate,
      itemPerDay: itemPerDay,
      items: items,
      region1: region1,
      region2: region2,
      startDate: startDate,
    );
  }

  /// يستدعي الريبو بعد التحقق من الحقول
  Future<void> submit({
    required String date,
    required String days,
    required String endDate,
    required String itemPerDay,
    required List<List<int>> items,
    required String region1,
    required String region2,
    required String startDate,
  }) async {
    final validation = validateInputs();
    if (validation != null) {
      emit(SaveTripToEmailError(validation));
      return;
    }

    final body = buildParams(
      date: date,
      days: days,
      endDate: endDate,
      itemPerDay: itemPerDay,
      items: items,
      region1: region1,
      region2: region2,
      startDate: startDate,
    );

    await saveTripToEmail(body);
  }

  Future<void> saveTripToEmail(SaveTripToEmailParams body) async {
    emit(SaveTripToEmailLoading());
    final result = await tripRepo.saveTripToEmail(body);
    result.fold(
      (failure) => emit(SaveTripToEmailError(failure.errMessage)),
      (success) => emit(SaveTripToEmailSuccess(success)),
    );
  }
}
