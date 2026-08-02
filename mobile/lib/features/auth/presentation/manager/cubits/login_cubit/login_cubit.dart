import 'package:flutter_bloc/flutter_bloc.dart';
import 'package:hawdaj/core/managers/user_info_cubit/user_info_cubit.dart';
import 'package:hawdaj/features/auth/data/models/login_request_model.dart';
import 'package:hawdaj/features/auth/data/models/user_model.dart';
import 'package:hawdaj/features/auth/domain/repositories/auth_repository.dart';
import 'package:equatable/equatable.dart';

part 'login_state.dart';

class LoginCubit extends Cubit<LoginState> {
  final AuthRepository repository;

  LoginCubit(this.repository) : super(LoginInitial());

  /// Login with email/phone and password
  Future<void> login({required String email, required String password}) async {
    emit(LoginLoading());

    try {
      final loginRequest = LoginRequestModel(email: email, password: password);

      final authResponse = await repository.login(loginRequest);

      // Check if login was successful
      if (authResponse.code == 200 && authResponse.data != null) {
        // Save user and token to secure storage

        await userInfoCubit.setUser(
          authResponse.data!.user,
          authResponse.data!.token,
        );

        emit(LoginSuccess(authResponse.data!.user));
      } else {
        // Login failed
        emit(LoginError(authResponse.message));
      }
    } catch (e) {
      emit(LoginError('حدث خطأ غير متوقع: ${e.toString()}'));
    }
  }

  /// Reset state to initial
  void resetState() {
    emit(LoginInitial());
  }
}
