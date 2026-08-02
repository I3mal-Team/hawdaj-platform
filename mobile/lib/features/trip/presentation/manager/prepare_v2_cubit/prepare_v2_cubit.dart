import 'package:bloc/bloc.dart';
import 'package:equatable/equatable.dart';

part 'prepare_v2_state.dart';

class PrepareV2Cubit extends Cubit<PrepareV2State> {
  PrepareV2Cubit() : super(PrepareV2Initial());
}
