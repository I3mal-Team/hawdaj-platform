part of 'fetch_category_cubit.dart';

sealed class FetchCategoryState extends Equatable {
  const FetchCategoryState();

  @override
  List<Object> get props => [];
}

final class FetchCategoryInitial extends FetchCategoryState {}

final class FetchCategoryLoading extends FetchCategoryState {}

final class FetchCategorySuccess extends FetchCategoryState {
  final List<PricesModel> categoryList;
  const FetchCategorySuccess(this.categoryList);

  @override
  List<Object> get props => [categoryList];
}

final class FetchCategoryError extends FetchCategoryState {
  final String message;
  const FetchCategoryError(this.message);

  @override
  List<Object> get props => [message];
}
