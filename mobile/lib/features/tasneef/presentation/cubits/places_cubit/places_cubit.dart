import 'package:flutter_bloc/flutter_bloc.dart';
import '../../../domain/repositories/tasneef_repository.dart';
import '../../../data/models/unified_place_model.dart';
import '../../../../../core/databases/api/end_points.dart';
import 'places_state.dart';

class PlacesCubit extends Cubit<PlacesState> {
  final TasneefRepository tasneefRepository;

  PlacesCubit({required this.tasneefRepository}) : super(PlacesInitial());

  List<UnifiedPlaceModel> _allPlaces = [];
  int _currentPage = 1;
  int _totalPages = 1;
  int _total = 0;
  String? search;
  int? regionId;
  int? cityId;

  Future<void> getItemsByType({
    required String type, // 'places', 'stores', 'zads', 'guides'
    int? page,
    int? perPage,
    int? categoryId,
    bool? topFeatured,
    bool? topStores,
    String? categories,
    bool? showNearest,

    int? languageId,
    bool? topRated,

    bool? isOnline,
    String? dateFrom,
    String? dateTo,
    String? daterange,
    String? addressType,
    double? lat,
    double? lng,
    List<int>? foodCategories,
    bool isRefresh = false,
  }) async {
    try {
      if (isRefresh) {
        _currentPage = 1;
        _allPlaces.clear();
        emit(PlacesLoading());
      } else if (page != null && page > 1) {
        if (state is PlacesSuccess) {
          final currentState = state as PlacesSuccess;
          emit(
            PlacesLoadingMore(
              places: currentState.places,
              currentPage: currentState.currentPage,
              totalPages: currentState.totalPages,
              total: currentState.total,
            ),
          );
        }
      } else {
        emit(PlacesLoading());
      }

      String endpoint;
      switch (type) {
        case 'places':
          endpoint = EndPoints.places;
          break;
        case 'stores':
          endpoint = EndPoints.stores;
          break;
        case 'zads':
          endpoint = EndPoints.zads;
          break;
        case 'events':
          endpoint = EndPoints.events;
          break;
        case 'stories':
          endpoint = EndPoints.stories;
          break;
        case 'apps':
          endpoint = EndPoints.applications;
          break;
        case 'guides':
          endpoint = EndPoints.guides;
          break;
        default:
          endpoint = EndPoints.places;
      }

      final result = await tasneefRepository.getItems(
        endpoint: endpoint,
        page: page ?? _currentPage,
        perPage: perPage ?? 12,
        categoryId: categoryId,
        topFeatured: topFeatured,
        topStores: topStores,
        categories: categories,
        showNearest: showNearest,
        regionId: regionId,
        cityId: cityId,
        languageId: languageId,
        topRated: topRated,
        search: search,
        isOnline: isOnline,
        dateFrom: dateFrom,
        dateTo: dateTo,
        daterange: daterange,
        addressType: addressType,
        lat: lat,
        lng: lng,
        foodCategories: foodCategories,
        type: type,
      );

      result.fold((failure) => emit(PlacesError(message: failure.errMessage)), (
        response,
      ) {
        if (response.code == 200) {
          _currentPage = response.data.currentPage;
          _totalPages = response.data.lastPage;
          _total = response.data.total;

          if (isRefresh || page == null || page == 1) {
            _allPlaces = response.data.items;
          } else {
            _allPlaces.addAll(response.data.items);
          }

          emit(
            PlacesSuccess(
              places: List.from(_allPlaces),
              currentPage: _currentPage,
              totalPages: _totalPages,
              total: _total,
              hasNextPage: _currentPage < _totalPages,
            ),
          );
        } else {
          emit(PlacesError(message: response.message));
        }
      });
    } catch (e) {
      emit(PlacesError(message: e.toString()));
    }
  }

  // Convenience methods for backward compatibility
  Future<void> getPlaces({
    int? page,
    int? perPage,
    int? categoryId,
    bool? topFeatured,
    int? regionId,
    int? cityId,
    String? search,
    bool isRefresh = false,
  }) async {
    await getItemsByType(
      type: 'places',
      page: page,
      perPage: perPage,
      categoryId: categoryId,
      topFeatured: topFeatured,
      isRefresh: isRefresh,
    );
  }

  Future<void> getStores({
    int? page,
    int? perPage,
    bool? topStores,
    bool isRefresh = false,
  }) async {
    await getItemsByType(
      type: 'stores',
      page: page,
      perPage: perPage,
      topStores: topStores,
      isRefresh: isRefresh,
    );
  }

  Future<void> getZads({
    int? page,
    int? perPage,
    String? categories,
    bool? showNearest,
    bool isRefresh = false,
  }) async {
    await getItemsByType(
      type: 'zads',
      page: page,
      perPage: perPage,
      categories: categories,
      showNearest: showNearest,
      isRefresh: isRefresh,
    );
  }

  Future<void> getEvents({
    int? page,
    int? perPage,
    bool isRefresh = false,
  }) async {
    await getItemsByType(
      type: 'events',
      page: page,
      perPage: perPage,
      isRefresh: isRefresh,
    );
  }

  Future<void> getStories({
    int? page,
    int? perPage,
    bool isRefresh = false,
  }) async {
    await getItemsByType(
      type: 'stories',
      page: page,
      perPage: perPage,
      isRefresh: isRefresh,
    );
  }

  Future<void> getApps({
    int? page,
    int? perPage,
    bool isRefresh = false,
  }) async {
    await getItemsByType(
      type: 'apps',
      page: page,
      perPage: perPage,
      isRefresh: isRefresh,
    );
  }

  Future<void> getGuides({
    int? page,
    int? perPage,
    int? regionId,
    int? languageId,
    bool? topRated,
    bool isRefresh = false,
  }) async {
    await getItemsByType(
      type: 'guides',
      page: page,
      perPage: perPage,
      languageId: languageId,
      topRated: topRated,
      isRefresh: isRefresh,
    );
  }

  Future<void> loadMoreItems({
    required String type,
    int? perPage,
    int? categoryId,
    bool? topFeatured,
    bool? topStores,
    String? categories,
    bool? showNearest,
    int? regionId,
    int? cityId,
    int? languageId,
    bool? topRated,
    String? search,
  }) async {
    final state = this.state;
    if (state is PlacesSuccess && state.hasNextPage) {
      await getItemsByType(
        type: type,
        page: _currentPage + 1,
        perPage: perPage,
        categoryId: categoryId,
        topFeatured: topFeatured,
        topStores: topStores,
        categories: categories,
        showNearest: showNearest,
        languageId: languageId,
        topRated: topRated,
      );
    }
  }

  Future<void> refreshItems({
    required String type,
    int? perPage,
    int? categoryId,
    bool? topFeatured,
    bool? topStores,
    String? categories,
    bool? showNearest,
    int? regionId,
    int? cityId,
    int? languageId,
    bool? topRated,
    String? search,
  }) async {
    await getItemsByType(
      type: type,
      page: 1,
      perPage: perPage,
      categoryId: categoryId,
      topFeatured: topFeatured,
      topStores: topStores,
      categories: categories,
      showNearest: showNearest,
      languageId: languageId,
      topRated: topRated,
      isRefresh: true,
    );
  }

  Future<void> filterPlaces({
    required Map<String, dynamic> filterParams,
    int? page,
    int? perPage,
  }) async {
    await getItemsByType(
      type: 'places',
      page: page,
      perPage: perPage,
      isRefresh: true,
    );
  }

  void resetPlaces() {
    _allPlaces.clear();
    _currentPage = 1;
    _totalPages = 1;
    _total = 0;
    emit(PlacesInitial());
  }
}
