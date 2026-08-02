import 'package:flutter_bloc/flutter_bloc.dart';
import 'package:hawdaj/core/errors/failure.dart';
import 'package:hawdaj/features/home/data/model/home_response.dart';
import 'package:hawdaj/features/home/data/repo/home_repo.dart';
import 'package:hawdaj/features/home/presentation/manager/home_cubit/home_state.dart';

class HomeCubit extends Cubit<HomeState> {
  final HomeRepo homeRepo;

  HomeCubit(this.homeRepo) : super(HomeInitial());

  Future<void> fetchHome() async {
    emit(HomeLoading());

    final result = await homeRepo.fetchHome();

    result.fold(
      (Failure failure) => emit(HomeError(failure.errMessage)),
      (HomeResponse data) => emit(HomeLoaded(data)),
    );
  }
}
