import 'package:bloc/bloc.dart';
import 'package:equatable/equatable.dart';
import 'package:hawdaj/features/profile/data/model/favorite_model.dart';
import 'package:hawdaj/features/profile/data/repo/profail_repo.dart';

part 'get_favorites_state.dart';

class GetFavoritesCubit extends Cubit<GetFavoritesState> {
  GetFavoritesCubit(this.profileRepo) : super(GetFavoritesInitial());
  final ProfileRepo profileRepo;

  Future<void> getFavorites() async {
    emit(GetFavoritesLoading());
    final result = await profileRepo.getFavorites();
    result.fold(
      (l) => emit(GetFavoritesFailure(l.errMessage)),
      (r) => emit(GetFavoritesSuccess(r)),
    );
  }
}
