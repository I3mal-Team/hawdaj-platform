part of 'menu_cubit.dart';

abstract class MenuState extends Equatable {
  const MenuState();

  @override
  List<Object?> get props => [];
}

class MenuInitial extends MenuState {}

class MenuLoading extends MenuState {
  final PagingController<int, MenuItemModel> pagingController;

  const MenuLoading({required this.pagingController});

  @override
  List<Object?> get props => [pagingController];
}

class MenuError extends MenuState {
  final String errMessage;

  const MenuError({required this.errMessage});

  @override
  List<Object?> get props => [errMessage];
}

class MenuDownloadInProgress extends MenuState {
  const MenuDownloadInProgress();

  @override
  List<Object?> get props => [];
}

class MenuDownloadSuccess extends MenuState {
  const MenuDownloadSuccess();

  @override
  List<Object?> get props => [];
}

class MenuDownloadFailure extends MenuState {
  final String message;

  const MenuDownloadFailure(this.message);

  @override
  List<Object?> get props => [message];
}
