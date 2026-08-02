import 'package:bloc/bloc.dart';
import 'package:equatable/equatable.dart';
import 'package:hawdaj/features/auth/domain/repositories/auth_repository.dart';

part 'forgot_password_state.dart';

class ForgotPasswordCubit extends Cubit<ForgotPasswordState> {
  ForgotPasswordCubit(this.repository) : super(ForgotPasswordInitial());
  final AuthRepository repository;

  Future<void> forgotPassword(String email) async {
    try {
      emit(ForgotPasswordLoading());
      final message = await repository.forgotPassword(email);
      emit(ForgotPasswordSuccess(message: message));
    } catch (e) {
      emit(ForgotPasswordError(message: e.toString()));
    }
  }
}
