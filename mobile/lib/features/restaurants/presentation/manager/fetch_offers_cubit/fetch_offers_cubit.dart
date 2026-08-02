import 'package:bloc/bloc.dart';
import 'package:equatable/equatable.dart';
import 'package:hawdaj/features/restaurants/data/model/offer_item_model.dart';
import 'package:hawdaj/features/restaurants/data/repo/restaurants_repo.dart';
import 'package:infinite_scroll_pagination/infinite_scroll_pagination.dart';

part 'fetch_offers_state.dart';

class FetchOffersCubit extends Cubit<FetchOffersState> {
  final RestaurantsRepo restaurants;
  final int id;

  late final PagingController<int, OfferItemModel> pagingController;

  FetchOffersCubit({required this.restaurants, required this.id})
    : super(FetchOffersInitial()) {
    pagingController = PagingController<int, OfferItemModel>(firstPageKey: 1);
    pagingController.addPageRequestListener(_fetchPage);

    // pagingController.refresh();
    emit(OffersLoading(pagingController: pagingController));
  }

  Future<void> _fetchPage(int pageKey) async {
    try {
      final result = await restaurants.fetchOffers(id, pageKey);

      result.fold(
        (failure) {
          pagingController.error = failure.errMessage;
          emit(OffersError(errMessage: failure.errMessage));
        },
        (response) {
          final items = response.data ?? [];
          final isLastPage = pageKey >= (response.lastPage ?? 1);

          if (isLastPage) {
            pagingController.appendLastPage(items);
          } else {
            final nextPageKey = pageKey + 1;
            pagingController.appendPage(items, nextPageKey);
          }
        },
      );
    } catch (error) {
      pagingController.error = error.toString();
      emit(OffersError(errMessage: error.toString()));
    }
  }

  void refresh() {
    pagingController.refresh();
  }

  @override
  Future<void> close() {
    pagingController.dispose();
    return super.close();
  }
}
