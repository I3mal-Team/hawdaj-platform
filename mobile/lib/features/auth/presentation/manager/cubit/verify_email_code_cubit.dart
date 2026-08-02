import 'package:bloc/bloc.dart';
import 'package:equatable/equatable.dart';

part 'verify_email_code_state.dart';

class VerifyEmailCodeCubit extends Cubit<VerifyEmailCodeState> {
  VerifyEmailCodeCubit() : super(VerifyEmailCodeInitial());
}
