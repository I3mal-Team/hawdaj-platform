part of 'get_search_global_cubit.dart';

sealed class GetSearchGlobalState extends Equatable {
  const GetSearchGlobalState();

  @override
  List<Object> get props => [];
}

final class GetSearchGlobalInitial extends GetSearchGlobalState {}

final class SearchGlobalError extends GetSearchGlobalState {
  final String error;
  const SearchGlobalError(this.error);

  @override
  List<Object> get props => [error];
}

final class SearchGlobalLoaded extends GetSearchGlobalState {
  final PagingController<int, ItemGlodal> pagingController;
  const SearchGlobalLoaded(this.pagingController);

  @override
  List<Object> get props => [pagingController];
}

final class SearchGlobalLoading extends GetSearchGlobalState {}
