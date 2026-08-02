import 'package:bloc/bloc.dart';
import 'package:equatable/equatable.dart';
import 'package:hawdaj/core/utils/auth_manager.dart';
import 'package:hawdaj/features/auth/domain/repositories/auth_repository.dart';

part 'delete_profile_state.dart';

class DeleteProfileCubit extends Cubit<DeleteProfileState> {
  DeleteProfileCubit(this.repository) : super(DeleteProfileInitial());
  final AuthRepository repository;

  Future<void> delete() async {
    emit(DeleteProfileLoading());

    try {
      // Call API logout
      final logoutResponse = await repository.deleteProfile();

      // Always clear local storage regardless of API response
      await AuthManager.logout();

      if (logoutResponse.code == 200) {
        emit(DeleteProfileSuccess(logoutResponse.message));
      } else {
        // Even if API failed, user is logged out locally
        emit(DeleteProfileSuccess('تم تسجيل الخروج بنجاح'));
      }
    } catch (e) {
      // Even if API call fails, clear local storage
      await AuthManager.logout();
      emit(DeleteProfileSuccess('تم تسجيل الخروج بنجاح'));
    }
  }

  /// Reset state to initial
  void resetState() {
    emit(DeleteProfileInitial());
  }
}
