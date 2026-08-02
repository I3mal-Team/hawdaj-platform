import 'package:flutter_bloc/flutter_bloc.dart';
import 'package:hawdaj/features/auth/domain/repositories/auth_repository.dart';
import 'package:hawdaj/core/utils/auth_manager.dart';
import 'package:equatable/equatable.dart';

part 'logout_state.dart';

class LogoutCubit extends Cubit<LogoutState> {
  final AuthRepository repository;

  LogoutCubit(this.repository) : super(LogoutInitial());

  /// Logout user and clear local storage
  Future<void> logout() async {
    emit(LogoutLoading());

    try {
      // Call API logout
      final logoutResponse = await repository.logout();

      // Always clear local storage regardless of API response
      await AuthManager.logout();

      if (logoutResponse.code == 200) {
        emit(LogoutSuccess(logoutResponse.message));
      } else {
        // Even if API failed, user is logged out locally
        emit(LogoutSuccess('تم تسجيل الخروج بنجاح'));
      }
    } catch (e) {
      // Even if API call fails, clear local storage
      await AuthManager.logout();
      emit(LogoutSuccess('تم تسجيل الخروج بنجاح'));
    }
  }

  /// Reset state to initial
  void resetState() {
    emit(LogoutInitial());
  }
}
