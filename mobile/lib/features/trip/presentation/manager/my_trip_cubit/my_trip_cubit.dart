import 'package:bloc/bloc.dart';
import 'package:equatable/equatable.dart';
import 'package:hawdaj/features/trip/data/model/my_trip_model.dart';
import 'package:hawdaj/features/trip/data/repo/trip_repo.dart';
import 'package:infinite_scroll_pagination/infinite_scroll_pagination.dart';

part 'my_trip_state.dart';

class MyTripCubit extends Cubit<MyTripState> {
  final TripRepo tripRepo;
  late final PagingController<int, MyTripModel> pagingController;
  MyTripCubit({required this.tripRepo}) : super(MyTripInitial()) {
    pagingController = PagingController<int, MyTripModel>(firstPageKey: 1);
    pagingController.addPageRequestListener(_fetchPage);

    emit(MyTripLoaded(pagingController));
  }
  Future<void> _fetchPage(int pageKey) async {
    try {
      final result = await tripRepo.myTrip(pageKey);

      result.fold(
        (failure) {
          pagingController.error = failure.errMessage;
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
      emit(MyTripError(error.toString()));
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
