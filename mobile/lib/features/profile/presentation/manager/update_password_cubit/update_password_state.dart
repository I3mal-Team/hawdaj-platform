part of 'update_password_cubit.dart';

sealed class UpdatePasswordState extends Equatable {
  const UpdatePasswordState();

  @override
  List<Object> get props => [];
}

final class UpdatePasswordInitial extends UpdatePasswordState {}

final class UpdatePasswordLoading extends UpdatePasswordState {}

final class UpdatePasswordSuccess extends UpdatePasswordState {
  final UserModel? user;
  const UpdatePasswordSuccess(this.user);

  @override
  List<Object> get props => [];
}

final class UpdatePasswordError extends UpdatePasswordState {
  final String message;
  const UpdatePasswordError({required this.message});
}
