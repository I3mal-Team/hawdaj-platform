import 'package:flutter_bloc/flutter_bloc.dart';
import 'package:equatable/equatable.dart';
import 'package:hawdaj/core/repositories/regions_repository.dart';
import 'package:hawdaj/features/tasneef/data/models/region_model.dart';

part 'regions_state.dart';

class RegionsCubit extends Cubit<RegionsState> {
  final RegionsRepository regionsRepository;

  RegionsCubit({required this.regionsRepository}) : super(RegionsInitial()) {
    print('🔍 RegionsCubit: Constructor called');
  }

  Future<void> fetchRegions() async {
    print('🔍 RegionsCubit: fetchRegions() called');
    emit(RegionsLoading());

    print('🔍 RegionsCubit: Calling repository.fetchRegions()');
    final result = await regionsRepository.fetchRegions();

    result.fold(
      (failure) {
        print('🔍 RegionsCubit: Error - ${failure.errMessage}');
        emit(RegionsError(failure.errMessage));
      },
      (regions) {
        print('🔍 RegionsCubit: Success - Got ${regions.length} regions');
        emit(RegionsSuccess(regions));
      },
    );
  }

  List<RegionModel> get regions {
    if (state is RegionsSuccess) {
      return (state as RegionsSuccess).regions;
    }
    return [];
  }

  RegionModel? getRegionById(int id) {
    if (state is RegionsSuccess) {
      try {
        return (state as RegionsSuccess).regions.firstWhere(
          (region) => region.id == id,
        );
      } catch (e) {
        return null;
      }
    }
    return null;
  }

  bool get isLoaded => state is RegionsSuccess;
  bool get isLoading => state is RegionsLoading;
  bool get hasError => state is RegionsError;
}
