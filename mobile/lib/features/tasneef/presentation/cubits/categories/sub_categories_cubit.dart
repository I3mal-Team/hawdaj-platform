import 'package:flutter_bloc/flutter_bloc.dart';
import '../../../domain/repositories/tasneef_repository.dart';
import 'categories_state.dart';
import '../../../../../core/databases/api/end_points.dart';

class SubCategoriesCubit extends Cubit<CategoriesCState> {
  final TasneefRepository repository;
  SubCategoriesCubit({required this.repository}) : super(CategoriesInitial());

  Future<void> loadZadFoodCategories() async {
    emit(SubCategoriesLoading());
    final res = await repository.getSubCategories(
      endpoint: EndPoints.zadsFoodCategories,
    );
    res.fold(
      (l) => emit(SubCategoriesError(l.errMessage)),
      (r) => emit(SubCategoriesSuccess(r.data)),
    );
  }
}
