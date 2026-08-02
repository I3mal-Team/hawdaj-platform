import 'package:bloc/bloc.dart';
import 'package:equatable/equatable.dart';
import 'package:hawdaj/features/trip/data/model/new/new_my_trips_response.dart';
import 'package:hawdaj/features/trip/data/repo/trip_repo.dart';
import 'package:infinite_scroll_pagination/infinite_scroll_pagination.dart';

part 'new_my_trip_state.dart';

class NewMyTripCubit extends Cubit<NewMyTripState> {
  final TripRepo tripRepo;
  final PagingController<int, TripItem> pagingController = PagingController(
    firstPageKey: 1,
  );

  NewMyTripCubit({required this.tripRepo}) : super(NewMyTripInitial()) {
    pagingController.addPageRequestListener(_fetchPage);
  }

  Future<void> _fetchPage(int pageKey) async {
    try {
      final result = await tripRepo.newMyTrip(pageKey);
      result.fold(
        (failure) {
          pagingController.error = failure.errMessage;
          emit(NewMyTripError(failure.errMessage));
        },
        (response) {
          final items = response.data ?? [];
          final isLastPage = pageKey >= (response.lastPage ?? 1);

          if (isLastPage) {
            pagingController.appendLastPage(items);
          } else {
            pagingController.appendPage(items, pageKey + 1);
          }

          emit(NewMyTripLoaded(pagingController));
        },
      );
    } catch (error) {
      pagingController.error = error.toString();
      emit(NewMyTripError(error.toString()));
    }
  }

  void refresh() {
    pagingController.refresh();
    emit(NewMyTripInitial());
  }

  @override
  Future<void> close() {
    pagingController.dispose();
    return super.close();
  }
}
