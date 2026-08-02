part of 'get_profile_cubit.dart';

sealed class GetProfileState extends Equatable {
  const GetProfileState();

  @override
  List<Object> get props => [];
}

final class GetProfileInitial extends GetProfileState {}

final class GetProfileLoading extends GetProfileState {}

final class GetProfileSuccess extends GetProfileState {
  final ProfilePageResponse userModel;
  const GetProfileSuccess(this.userModel);
  @override
  List<Object> get props => [userModel];
}

final class GetProfileError extends GetProfileState {
  final String errMessage;
  const GetProfileError({required this.errMessage});
  @override
  List<Object> get props => [errMessage];
}
