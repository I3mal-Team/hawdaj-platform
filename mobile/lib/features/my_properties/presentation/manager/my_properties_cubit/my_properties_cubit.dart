import 'package:bloc/bloc.dart';
import 'package:equatable/equatable.dart';
import 'package:infinite_scroll_pagination/infinite_scroll_pagination.dart';
import 'package:hawdaj/core/errors/failure.dart';
import 'package:hawdaj/features/my_properties/data/model/my_properties_response.dart';
import 'package:hawdaj/features/my_properties/data/repo/properties_repo.dart';

part 'my_properties_state.dart';

class MyPropertiesCubit extends Cubit<MyPropertiesState> {
  final PropertiesRepo repo;
  final PagingController<int, PropertyItem> pagingController = PagingController(
    firstPageKey: 1,
  );

  /// المفتاح الحالي للفلاتر
  String selectedFilterKey = 'filter_all';

  /// نخزن آخر response حتى نقدر نبدّل الفلاتر بدون إعادة تحميل من الـ API
  MyPropertiesResponse? _cachedResponse;

  MyPropertiesCubit(this.repo) : super(MyPropertiesInitial()) {
    pagingController.addPageRequestListener((pageKey) {
      fetchPage(pageKey);
    });
  }

  /// تغيير الفلتر
  void changeFilter(String filterKey) {
    selectedFilterKey = filterKey;

    // نعمل refresh فقط لو عندنا بيانات جاهزة
    if (_cachedResponse != null) {
      final filteredItems = _filterByType(_cachedResponse!);
      pagingController.itemList = filteredItems;
      emit(MyPropertiesLoaded(items: filteredItems, isLastPage: true));
    } else {
      pagingController.refresh();
    }
  }

  /// تحميل الصفحة الأولى أو البيانات
  Future<void> fetchPage(int pageKey) async {
    emit(MyPropertiesLoading());

    final result = await repo.getMyProperties();

    result.fold(
      (failure) {
        pagingController.error = failure.errMessage;
        emit(MyPropertiesError(failure.errMessage));
      },
      (response) {
        _cachedResponse = response;

        final filteredItems = _filterByType(response);
        pagingController.appendLastPage(filteredItems);

        emit(MyPropertiesLoaded(items: filteredItems, isLastPage: true));
      },
    );
  }

  /// فلترة البيانات محليًا حسب نوع التبويب
  List<PropertyItem> _filterByType(MyPropertiesResponse response) {
    switch (selectedFilterKey) {
      case 'filter_places':
        return response.data.places.items;
      case 'filter_stores':
        return response.data.stores.items;
      case 'filter_events':
        return response.data.events.items;
      case 'filter_zads':
        return response.data.zadElgadels.items;
      default:
        return [
          ...response.data.places.items,
          ...response.data.stores.items,
          ...response.data.events.items,
          ...response.data.zadElgadels.items,
        ];
    }
  }

  @override
  Future<void> close() {
    pagingController.dispose();
    return super.close();
  }
}
