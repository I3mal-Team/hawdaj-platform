part of 'delete_profile_cubit.dart';

abstract class DeleteProfileState extends Equatable {
  const DeleteProfileState();

  @override
  List<Object> get props => [];
}

class DeleteProfileInitial extends DeleteProfileState {}

class DeleteProfileLoading extends DeleteProfileState {}

class DeleteProfileSuccess extends DeleteProfileState {
  final String message;

  const DeleteProfileSuccess(this.message);

  @override
  List<Object> get props => [message];
}

class DeleteProfileError extends DeleteProfileState {
  final String message;

  const DeleteProfileError(this.message);

  @override
  List<Object> get props => [message];
}
