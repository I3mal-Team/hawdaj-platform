import 'package:flutter_bloc/flutter_bloc.dart';
import 'package:equatable/equatable.dart';
import 'package:flutter/material.dart';
import 'package:hawdaj/core/utils/auth_manager.dart';
import 'package:hawdaj/features/auth/data/models/user_model.dart';
import 'package:hawdaj/features/rates/data/repo/rate_repo.dart';

part 'rate_state.dart';

class RateCubit extends Cubit<RateState> {
  RateCubit(this.repo) : super(RateInitial());

  final RateRepo repo;

  // Controllers يديرها الكيوبت
  final nameController = TextEditingController();
  final emailController = TextEditingController();
  final rateTextController = TextEditingController();

  // حالة المستخدم
  bool _hasUser = false;
  bool get hasUser => _hasUser;

  // التقييم (بدون قيمة افتراضية)
  double _rating = 0.0;
  double get rating => _rating;

  /// نادِها قبل فتح الـBottomSheet
  Future<void> init() async {
    emit(RateLoading());
    try {
      final UserModel? user = await AuthManager.getUser();
      _hasUser = user != null;

      if (user != null) {
        nameController.text = (user.fullName ?? '').trim();
        emailController.text = (user.email ?? '').trim();
      }

      // لا تعيين لقيمة افتراضية هنا (_rating قد يظل 0)
      emit(
        RateReady(
          name: nameController.text,
          email: emailController.text,
          rating: _rating,
        ),
      );
    } catch (e) {
      emit(RateError('تعذّر تهيئة النموذج: ${e.toString()}'));
    }
  }

  /// تحديث قيمة التقييم من الـUI
  void setRating(double value) {
    _rating = value;
    final current = state;
    if (current is RateReady) {
      emit(current.copyWith(rating: _rating));
    }
  }

  /// الإرسال — بدون قيمة افتراضية: يمنع الإرسال لو التقييم = 0
  Future<void> submit({
    required String type,
    required String parentId,
    bool dryRun = false, // اختياري: للطباعة فقط أثناء الاختبار
  }) async {
    emit(RateLoading());

    final name = nameController.text.trim();
    final email = emailController.text.trim();
    final rateText = rateTextController.text.trim();

    if (_rating == 0.0) {
      emit(RateError('من فضلك اختر تقييم أولاً'));
      return;
    }

    final rate = _rating.round();

    final result = await repo.addRate(
      name: name,
      rate: rate,
      email: email,
      rateText: rateText,
      type: type,
      parentId: parentId,
    );

    result.fold((l) => emit(RateError(l.errMessage)), (r) => emit(RateAdded()));
  }

  @override
  Future<void> close() {
    nameController.dispose();
    emailController.dispose();
    rateTextController.dispose();
    return super.close();
  }

  void reset() {
    nameController.clear();
    emailController.clear();
    rateTextController.clear();
    _rating = 0.0;
  }
}
