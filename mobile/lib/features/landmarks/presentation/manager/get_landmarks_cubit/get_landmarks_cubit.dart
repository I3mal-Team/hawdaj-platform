import 'package:bloc/bloc.dart';
import 'package:equatable/equatable.dart';
import 'package:hawdaj/features/landmarks/data/models/list_landmark_response.dart';
import 'package:hawdaj/features/landmarks/data/repositories/landmarks_repository_repo.dart';
import 'package:infinite_scroll_pagination/infinite_scroll_pagination.dart';

part 'get_landmarks_state.dart';

class GetLandmarksCubit extends Cubit<GetLandmarksState> {
  final LandmarksRepository repository;
  late final PagingController<int, LandmarkItem> pagingController;

  GetLandmarksCubit(this.repository) : super(GetLandmarksInitial()) {
    pagingController = PagingController<int, LandmarkItem>(firstPageKey: 1);
    pagingController.addPageRequestListener(_fetchPage);
    emit(GetLandmarksLoaded(pagingController));
  }

  Future<void> _fetchPage(int pageKey) async {
    try {
      final result = await repository.getLandmarks(pageKey);
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
      emit(GetLandmarksError(e.toString()));
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
