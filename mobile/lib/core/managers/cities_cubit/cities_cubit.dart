import 'package:flutter_bloc/flutter_bloc.dart';
import 'package:equatable/equatable.dart';
import 'package:hawdaj/core/repositories/cities_repository.dart';
import 'package:hawdaj/features/tasneef/data/models/city_model.dart';

part 'cities_state.dart';

class CitiesCubit extends Cubit<CitiesState> {
  final CitiesRepository citiesRepository;

  CitiesCubit({required this.citiesRepository}) : super(CitiesInitial()) {
    print('🔍 CitiesCubit: Constructor called');
  }

  Future<void> fetchCitiesByRegion(int regionId) async {
    print('🔍 CitiesCubit: fetchCitiesByRegion($regionId) called');
    emit(CitiesLoading());

    print('🔍 CitiesCubit: Calling repository.fetchCitiesByRegion($regionId)');
    final result = await citiesRepository.fetchCitiesByRegion(regionId);

    result.fold(
      (failure) {
        print('🔍 CitiesCubit: Error - ${failure.errMessage}');
        emit(CitiesError(failure.errMessage));
      },
      (cities) {
        print('🔍 CitiesCubit: Success - Got ${cities.length} cities');
        emit(CitiesSuccess(cities));
      },
    );
  }

  List<CityModel> get cities {
    if (state is CitiesSuccess) {
      return (state as CitiesSuccess).cities;
    }
    return [];
  }

  void clear() {
    emit(CitiesInitial());
    cities.clear();
  }

  CityModel? getCityById(int id) {
    if (state is CitiesSuccess) {
      try {
        return (state as CitiesSuccess).cities.firstWhere(
          (city) => city.id == id,
        );
      } catch (e) {
        return null;
      }
    }
    return null;
  }

  bool get isLoaded => state is CitiesSuccess;
  bool get isLoading => state is CitiesLoading;
  bool get hasError => state is CitiesError;
}
