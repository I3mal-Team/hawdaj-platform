import 'package:equatable/equatable.dart';
import '../../../../data/models/auth_response_model.dart';

abstract class RegisterState extends Equatable {
  const RegisterState();

  @override
  List<Object?> get props => [];
}

class RegisterInitial extends RegisterState {}

class RegisterLoading extends RegisterState {}

class RegisterSuccess extends RegisterState {
  final AuthResponseModel authResponse;

  const RegisterSuccess({required this.authResponse});

  @override
  List<Object?> get props => [authResponse];
}

class RegisterError extends RegisterState {
  final String message;

  const RegisterError({required this.message});

  @override
  List<Object?> get props => [message];
}
