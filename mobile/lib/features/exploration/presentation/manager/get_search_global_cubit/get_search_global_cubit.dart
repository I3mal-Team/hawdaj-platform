import 'package:bloc/bloc.dart';
import 'package:equatable/equatable.dart';
import 'package:flutter/widgets.dart';
import 'package:hawdaj/features/exploration/data/model/glodal_search/item.dart';
import 'package:hawdaj/features/exploration/data/repo/exploration_repo.dart';
import 'package:infinite_scroll_pagination/infinite_scroll_pagination.dart';

part 'get_search_global_state.dart';

class GetSearchGlobalCubit extends Cubit<GetSearchGlobalState> {
  GetSearchGlobalCubit({required this.explorationRepo})
    : super(GetSearchGlobalInitial()) {
    pagingController = PagingController<int, ItemGlodal>(firstPageKey: 1);
    pagingController.addPageRequestListener(_fetchPage);
    emit(SearchGlobalLoaded(pagingController));
  }

  final ExplorationRepo explorationRepo;
  late final PagingController<int, ItemGlodal> pagingController;

  final TextEditingController searchController = TextEditingController();
  int? regionId;
  int? cityId;
  String? viewAs;

  void refresh() => pagingController.refresh();

  void updateFilters({
    String? newSearch,
    int? newRegionId,
    int? newCityId,
    String? newViewAs,
  }) {
    if (newSearch != null) {
      searchController.text = newSearch;
    }
    regionId = newRegionId;
    cityId = newCityId;
    viewAs = newViewAs;
    refresh();
  }

  Future<void> _fetchPage(int pageKey) async {
    try {
      final result = await explorationRepo.getSearchGlobal(
        pageKey,
        searchController.text,
        regionId,
        cityId,
        viewAs,
      );

      result.fold(
        (failure) {
          pagingController.error = failure.errMessage;
          emit(SearchGlobalError(failure.errMessage));
        },
        (response) {
          final items = response.data ?? <ItemGlodal>[];
          final lastPage = response.lastPage ?? pageKey;
          final isLastPage = pageKey >= lastPage;

          if (isLastPage) {
            pagingController.appendLastPage(items);
          } else {
            pagingController.appendPage(items, pageKey + 1);
          }
        },
      );
    } catch (e) {
      final msg = e.toString();
      pagingController.error = msg;
      emit(SearchGlobalError(msg));
    }
  }

  @override
  Future<void> close() {
    pagingController.dispose();
    searchController.dispose();
    return super.close();
  }
}
