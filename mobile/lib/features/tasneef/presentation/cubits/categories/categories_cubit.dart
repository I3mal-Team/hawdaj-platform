import 'package:flutter_bloc/flutter_bloc.dart';
import '../../../domain/repositories/tasneef_repository.dart';
import 'categories_state.dart';
import '../../../../../core/databases/api/end_points.dart';

class CategoriesCubit extends Cubit<CategoriesCState> {
  final TasneefRepository repository;
  CategoriesCubit({required this.repository}) : super(CategoriesInitial());

  Future<void> loadMainCategories(String type) async {
    emit(CategoriesLoading());
    String endpoint;
    switch (type) {
      case 'places':
        endpoint = EndPoints.categoriesPlaces; // categories
        break;
      case 'stores':
        endpoint = EndPoints.storesCategories;
        break;
      case 'zads':
        endpoint = EndPoints.zadsCategories;
        break;
      case 'stories':
        endpoint = EndPoints.swalefsCategories;
        break;
      case 'apps':
        endpoint = EndPoints.applicationsCategories;
        break;
      default:
        endpoint = EndPoints.categoriesPlaces;
    }

    final res = await repository.getCategories(endpoint: endpoint);
    res.fold(
      (l) => emit(CategoriesError(l.errMessage)),
      (r) => emit(CategoriesCSuccess(r.data)),
    );
  }
}
