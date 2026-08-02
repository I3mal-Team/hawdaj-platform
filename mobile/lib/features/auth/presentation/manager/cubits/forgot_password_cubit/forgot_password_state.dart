part of 'forgot_password_cubit.dart';

sealed class ForgotPasswordState extends Equatable {
  const ForgotPasswordState();

  @override
  List<Object> get props => [];
}

final class ForgotPasswordInitial extends ForgotPasswordState {}

final class ForgotPasswordLoading extends ForgotPasswordState {}

final class ForgotPasswordSuccess extends ForgotPasswordState {
  final String message;
  const ForgotPasswordSuccess({required this.message});
}

final class ForgotPasswordError extends ForgotPasswordState {
  final String message;
  const ForgotPasswordError({required this.message});
}
