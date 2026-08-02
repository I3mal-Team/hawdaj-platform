import 'package:bloc/bloc.dart';
import 'package:equatable/equatable.dart';
import 'package:hawdaj/features/restaurants/data/model/menu_item_model.dart';
import 'package:hawdaj/features/restaurants/data/repo/restaurants_repo.dart';
import 'package:hawdaj/features/restaurants/presentation/view/widgets/download_menu.dart';
import 'package:infinite_scroll_pagination/infinite_scroll_pagination.dart';

part 'menu_state.dart';

class MenuCubit extends Cubit<MenuState> {
  final RestaurantsRepo restaurants;
  final int id;

  late final PagingController<int, MenuItemModel> pagingController;

  MenuCubit({required this.restaurants, required this.id})
    : super(MenuInitial()) {
    pagingController = PagingController<int, MenuItemModel>(firstPageKey: 1);
    pagingController.addPageRequestListener(_fetchPage);
    _fetchPage(1);

    // pagingController.refresh();
    emit(MenuLoading(pagingController: pagingController));
  }

  Future<void> _fetchPage(int pageKey) async {
    try {
      final result = await restaurants.fetchMenu(id, pageKey);

      result.fold(
        (failure) {
          pagingController.error = failure.errMessage;
          emit(MenuError(errMessage: failure.errMessage));
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
      emit(MenuError(errMessage: error.toString()));
    }
  }

  Future<void> downloadMenu() async {
    final items = pagingController.itemList;

    if (items == null || items.isEmpty) {
      emit(const MenuDownloadFailure("لا يوجد منتجات"));
      return;
    }

    emit(MenuDownloadInProgress());

    try {
      await downloadMenuAsPdf(items);
      emit(MenuDownloadSuccess());
    } catch (e) {
      emit(MenuDownloadFailure("فشل في التحميل"));
    }

    // رجع للحالة القديمة حتى لا يتعطل الزر
    emit(MenuLoading(pagingController: pagingController));
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
