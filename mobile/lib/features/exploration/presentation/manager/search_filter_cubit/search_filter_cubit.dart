import 'package:flutter_bloc/flutter_bloc.dart';
import 'package:equatable/equatable.dart';
import 'package:hawdaj/core/managers/cities_cubit/cities_cubit.dart';
import 'package:hawdaj/features/exploration/presentation/manager/get_search_global_cubit/get_search_global_cubit.dart';

part 'search_filter_state.dart';

class SearchFilterCubit extends Cubit<SearchFilterState> {
  final CitiesCubit citiesCubit;
  final GetSearchGlobalCubit searchCubit;

  SearchFilterCubit({required this.citiesCubit, required this.searchCubit})
    : super(const SearchFilterState());

  void _syncWithSearchCubit(SearchFilterState newState) {
    if (newState.shouldSearch) {
      searchCubit.updateFilters(
        newSearch: newState.searchText,
        newRegionId: newState.regionId,
        newCityId: newState.cityId,
      );
      searchCubit.refresh();
    }
  }

  // تحديث نص البحث
  void updateSearch(String searchText) {
    final newState = state.copyWith(searchText: searchText, shouldSearch: true);
    emit(newState);
    _syncWithSearchCubit(newState);
  }

  // تحديث المنطقة — يمسح المدينة تلقائيًا
  void updateRegion(int? regionId) {
    final newState = state.copyWith(
      regionId: regionId,
      cityId: null, // مسح المدينة عند تغيير المنطقة
      shouldSearch: true,
    );
    emit(newState);
    _syncWithSearchCubit(newState);

    if (regionId != null) {
      citiesCubit.fetchCitiesByRegion(regionId);
    } else {
      // لما نمسح المنطقة، نفضّي قائمة المدن (لو عندك دالة جاهزة)
      citiesCubit.clear();
    }
  }

  // تحديث المدينة
  void updateCity(int? cityId) {
    final newState = state.copyWith(cityId: cityId, shouldSearch: true);
    emit(newState);
    _syncWithSearchCubit(newState);
  }

  // ===== دوال المسح/الإزالة المطلوبة =====

  // مسح البحث فقط
  void clearSearch() {
    final newState = state.copyWith(searchText: '', shouldSearch: true);
    emit(newState);
    _syncWithSearchCubit(newState);
  }

  void clearCity() {
    final newState = state.copyWith(cityId: 0, shouldSearch: true);
    emit(newState);
    _syncWithSearchCubit(newState);
    citiesCubit.clear();
  }

  // مسح المنطقة (ويمسح المدينة معها)
  void clearRegion() {
    final newState = state.copyWith(regionId: 0, cityId: 0, shouldSearch: true);
    emit(newState);
    _syncWithSearchCubit(newState);
    citiesCubit.clear();
  }

  // مسح الكل (بحث + منطقة + مدينة)
  void clearAllFilters() {
    final newState = state.copyWith(
      searchText: '',
      regionId: 0,
      cityId: 0,
      shouldSearch: true,
    );
    emit(newState);
    _syncWithSearchCubit(newState);
    citiesCubit.clear();
  }
}
