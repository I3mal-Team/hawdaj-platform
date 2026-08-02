import 'package:flutter_bloc/flutter_bloc.dart';
import 'package:hawdaj/core/init/runtime_variables.dart';
import 'package:hawdaj/core/managers/user_info_cubit/user_info_cubit.dart';
import '../../../../data/models/register_request_model.dart';
import '../../../../domain/repositories/auth_repository.dart';
import 'register_state.dart';

class RegisterCubit extends Cubit<RegisterState> {
  final AuthRepository authRepository;

  RegisterCubit({required this.authRepository}) : super(RegisterInitial());

  Future<void> register({
    required String email,
    required String firstName,
    required String lastName,
    required String password,
  }) async {
    emit(RegisterLoading());

    try {
      final registerRequest = RegisterRequestModel(
        email: email,
        firstName: firstName,
        lastName: lastName,
        password: password,
      );

      final authResponse = await authRepository.register(registerRequest);

      if (authResponse.code == 200) {
        emit(RegisterSuccess(authResponse: authResponse));
        await userInfoCubit.setUser(
          authResponse.data!.user,
          authResponse.data!.token,
        );
      } else {
        emit(RegisterError(message: authResponse.message));
        logger.e('Registration failed: ${authResponse.message}');
      }
    } catch (e) {
      emit(RegisterError(message: 'حدث خطأ غير متوقع: ${e.toString()}'));
      logger.e('Registration failed: ${e.toString()}');
    }
  }
}
