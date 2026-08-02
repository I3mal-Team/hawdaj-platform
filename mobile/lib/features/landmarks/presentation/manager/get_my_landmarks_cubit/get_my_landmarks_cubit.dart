import 'package:bloc/bloc.dart';
import 'package:equatable/equatable.dart';
import 'package:hawdaj/features/landmarks/data/models/list_landmark_response.dart';
import 'package:hawdaj/features/landmarks/data/repositories/landmarks_repository_repo.dart';
import 'package:infinite_scroll_pagination/infinite_scroll_pagination.dart';

part 'get_my_landmarks_state.dart';

class GetMyLandmarksCubit extends Cubit<GetMyLandmarksState> {
  final LandmarksRepository repository;
  late final PagingController<int, LandmarkItem> pagingController;

  GetMyLandmarksCubit(this.repository) : super(GetMyLandmarksInitial()) {
    pagingController = PagingController<int, LandmarkItem>(firstPageKey: 1);
    pagingController.addPageRequestListener(_fetchPage);
    emit(GetMyLandmarksLoaded(pagingController));
  }

  Future<void> _fetchPage(int pageKey) async {
    try {
      final result = await repository.getMyLandmarks(pageKey);
      result.fold(
        (failure) {
          pagingController.error = failure.errMessage;
        },
        (landmarkData) {
          final items = landmarkData.items;
          final isLastPage = pageKey >= landmarkData.lastPage;

          if (isLastPage) {
            pagingController.appendLastPage(items);
          } else {
            pagingController.appendPage(items, pageKey + 1);
          }
        },
      );
    } catch (e) {
      pagingController.error = e.toString();
      emit(GetMyLandmarksError(e.toString()));
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
