import 'package:flutter_bloc/flutter_bloc.dart';
import 'package:hawdaj/core/errors/failure.dart';
import 'package:hawdaj/features/home/data/model/slider_model.dart';
import 'package:hawdaj/features/home/data/repo/home_repo.dart';
import 'package:hawdaj/features/home/presentation/manager/sliders_cubit/sliders_state.dart';

class SlidersCubit extends Cubit<SlidersState> {
  final HomeRepo homeRepo;

  SlidersCubit(this.homeRepo) : super(SlidersInitial());

  Future<void> fetchSliders() async {
    emit(SlidersLoading());

    final result = await homeRepo.fetchSliders();

    result.fold(
      (Failure failure) => emit(SlidersError(failure.errMessage)),
      (List<SliderModel> data) => emit(SlidersLoaded(data)),
    );
  }
}
