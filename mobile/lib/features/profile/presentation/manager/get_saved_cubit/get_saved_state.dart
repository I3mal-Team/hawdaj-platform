part of 'get_saved_cubit.dart';

sealed class GetSavedState extends Equatable {
  const GetSavedState();

  @override
  List<Object> get props => [];
}

final class GetSavedInitial extends GetSavedState {}

final class GetSavedLoading extends GetSavedState {}

final class GetSavedSuccess extends GetSavedState {
  final List<SavedItemModel> savedItems;

  const GetSavedSuccess(this.savedItems);
}

final class GetSavedFailure extends GetSavedState {
  final String errMessage;

  const GetSavedFailure(this.errMessage);
}
