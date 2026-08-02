import 'package:bloc/bloc.dart';
import 'package:equatable/equatable.dart';
import 'package:flutter/widgets.dart';
import 'package:hawdaj/features/auth/data/models/user_model.dart';
import 'package:hawdaj/features/profile/data/repo/profail_repo.dart';

part 'update_password_state.dart';

class UpdatePasswordCubit extends Cubit<UpdatePasswordState> {
  UpdatePasswordCubit(this.profileRepo) : super(UpdatePasswordInitial());

  final ProfileRepo profileRepo;
  final TextEditingController currentPasswordController =
      TextEditingController();
  final TextEditingController passwordController = TextEditingController();
  final TextEditingController passwordconfirmationController =
      TextEditingController();

  Future<void> updatePassword() async {
    final currentPassword = currentPasswordController.text.trim();
    final newPassword = passwordController.text.trim();
    final confirmPassword = passwordconfirmationController.text.trim();

    final validationMessage = _validateInputs(
      currentPassword: currentPassword,
      newPassword: newPassword,
      confirmPassword: confirmPassword,
    );

    if (validationMessage != null) {
      emit(UpdatePasswordError(message: validationMessage));
      return;
    }

    emit(UpdatePasswordLoading());

    final result = await profileRepo.updatePassword(
      currentPassword: currentPassword,
      password: newPassword,
      passwordconfirmation: confirmPassword,
    );

    result.fold(
      (l) => emit(UpdatePasswordError(message: l.errMessage)),
      (r) => emit(UpdatePasswordSuccess(r)),
    );
  }

  String? _validateInputs({
    required String currentPassword,
    required String newPassword,
    required String confirmPassword,
  }) {
    if (currentPassword.isEmpty ||
        newPassword.isEmpty ||
        confirmPassword.isEmpty) {
      return 'All fields are required.';
    }

    if (newPassword.length < 8) {
      return 'Password must be at least 8 characters.';
    }

    if (newPassword != confirmPassword) {
      return 'Passwords do not match.';
    }

    return null; // All validations passed
  }
}
