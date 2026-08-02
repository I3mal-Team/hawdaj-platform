import 'package:equatable/equatable.dart';
import '../../../data/models/category_model.dart';

abstract class CategoriesCState extends Equatable {
  const CategoriesCState();
  @override
  List<Object?> get props => [];
}

class CategoriesInitial extends CategoriesCState {}

class CategoriesLoading extends CategoriesCState {}

class CategoriesCSuccess extends CategoriesCState {
  final List<CategoryModel> categories;
  const CategoriesCSuccess(this.categories);
  @override
  List<Object?> get props => [categories];
}

class CategoriesError extends CategoriesCState {
  final String message;
  const CategoriesError(this.message);
  @override
  List<Object?> get props => [message];
}

class SubCategoriesLoading extends CategoriesCState {}

class SubCategoriesSuccess extends CategoriesCState {
  final List<CategoryModel> subCategories;
  const SubCategoriesSuccess(this.subCategories);
  @override
  List<Object?> get props => [subCategories];
}

class SubCategoriesError extends CategoriesCState {
  final String message;
  const SubCategoriesError(this.message);
  @override
  List<Object?> get props => [message];
}
