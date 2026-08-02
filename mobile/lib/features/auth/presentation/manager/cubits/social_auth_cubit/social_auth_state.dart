part of 'social_auth_cubit.dart';

abstract class SocialAuthState extends Equatable {
  const SocialAuthState();

  @override
  List<Object> get props => [];
}

class SocialAuthInitial extends SocialAuthState {}

class SocialAuthLoading extends SocialAuthState {}

class SocialAuthSuccess extends SocialAuthState {
  final UserModel user;
  final String message;

  const SocialAuthSuccess({required this.user, required this.message});

  @override
  List<Object> get props => [user, message];
}

class SocialAuthError extends SocialAuthState {
  final String message;

  const SocialAuthError(this.message);

  @override
  List<Object> get props => [message];
}
