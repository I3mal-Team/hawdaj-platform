import 'package:flutter_bloc/flutter_bloc.dart';

import 'package:hawdaj/features/saved/data/repo/saved_repo.dart';
import 'saved_state.dart';

class SavedCubit extends Cubit<SavedState> {
  final SavedRepo repo;
  SavedCubit(this.repo) : super(SavedInitial());

  Future<void> addToSaved({
    required int savedId,
    required String savedType,
  }) async {
    emit(SavedLoading());

    final result = await repo.addSaved(savedType: savedType, savedId: savedId);

    result.fold(
      (failure) => emit(SavedError(failure.errMessage)),
      (message) => emit(SavedSuccess(message)),
    );
  }
}
