import 'package:bloc/bloc.dart';
import 'package:equatable/equatable.dart';
import 'package:hawdaj/features/profile/data/model/saved_item_model.dart';
import 'package:hawdaj/features/profile/data/repo/profail_repo.dart';

part 'get_saved_state.dart';

class GetSavedCubit extends Cubit<GetSavedState> {
  GetSavedCubit(this.profileRepo) : super(GetSavedInitial());
  final ProfileRepo profileRepo;

  Future<void> getSaved() async {
    emit(GetSavedLoading());
    final result = await profileRepo.getSaved();
    result.fold(
      (l) => emit(GetSavedFailure(l.errMessage)),
      (r) => emit(GetSavedSuccess(r)),
    );
  }
}
