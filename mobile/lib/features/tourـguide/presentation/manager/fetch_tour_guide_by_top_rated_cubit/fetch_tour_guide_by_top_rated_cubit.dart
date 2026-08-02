import 'package:equatable/equatable.dart';
import 'package:flutter_bloc/flutter_bloc.dart';
import 'package:hawdaj/features/tasneef/data/models/unified_place_model.dart';
import 'package:hawdaj/features/tasneef/data/models/unified_response_model.dart';
import 'package:hawdaj/features/tour%D9%80guide/data/repo/tour_guide_repo.dart';

part 'fetch_tour_guide_by_top_rated_state.dart';

class FetchTourGuideByTopRatedCubit
    extends Cubit<FetchTourGuideByTopRatedState> {
  final TourGuideRepo repo;

  FetchTourGuideByTopRatedCubit({required this.repo})
    : super(FetchTourGuideByTopRatedInitial());

  // Cache
  final List<UnifiedPlaceModel> _items = [];
  int _currentPage = 1;
  int _totalPages = 1;
  int _total = 0;

  Future<void> fetch({
    int page = 1,
    int perPage = 12,
    int excludedId = 0,
    bool topRated = true,
    bool isRefresh = false,
  }) async {
    try {
      if (isRefresh) {
        _currentPage = 1;
        _items.clear();
        emit(FetchTourGuideByTopRatedLoading());
      } else if (page > 1 && state is FetchTourGuideByTopRatedSuccess) {
        final s = state as FetchTourGuideByTopRatedSuccess;
        emit(
          FetchTourGuideByTopRatedLoadingMore(
            fetchTourGuideByTopRated: s.fetchTourGuideByTopRated,
            currentPage: s.currentPage,
            totalPages: s.totalPages,
            total: s.total,
          ),
        );
      } else {
        emit(FetchTourGuideByTopRatedLoading());
      }

      final result = await repo.fetchTourGuideByTopRated(
        page,
        perPage,
        excludedId,
        topRated,
      );

      result.fold(
        (failure) =>
            emit(FetchTourGuideByTopRatedError(message: failure.errMessage)),
        (UnifiedResponseModel response) {
          if (response.code == 200) {
            final data = response.data;

            _currentPage = data.currentPage;
            _totalPages = data.lastPage;
            _total = data.total;

            if (isRefresh || page == 1) {
              _items
                ..clear()
                ..addAll(data.items);
            } else {
              _items.addAll(data.items);
            }

            emit(
              FetchTourGuideByTopRatedSuccess(
                fetchTourGuideByTopRated: List.unmodifiable(_items),
                currentPage: _currentPage,
                totalPages: _totalPages,
                total: _total,
                hasNextPage: _currentPage < _totalPages,
              ),
            );
          } else {
            emit(FetchTourGuideByTopRatedError(message: response.message));
          }
        },
      );
    } catch (e) {
      emit(FetchTourGuideByTopRatedError(message: e.toString()));
    }
  }

  Future<void> loadMore({
    int perPage = 12,
    int excludedId = 0,
    bool topRated = true,
  }) async {
    final s = state;
    if (s is FetchTourGuideByTopRatedSuccess && s.hasNextPage) {
      await fetch(
        page: _currentPage + 1,
        perPage: perPage,
        excludedId: excludedId,
        topRated: topRated,
      );
    }
  }

  Future<void> refresh({
    int perPage = 12,
    int excludedId = 0,
    bool topRated = true,
  }) async {
    await fetch(
      page: 1,
      perPage: perPage,
      excludedId: excludedId,
      topRated: topRated,
      isRefresh: true,
    );
  }

  void reset() {
    _items.clear();
    _currentPage = 1;
    _totalPages = 1;
    _total = 0;
    emit(FetchTourGuideByTopRatedInitial());
  }
}
