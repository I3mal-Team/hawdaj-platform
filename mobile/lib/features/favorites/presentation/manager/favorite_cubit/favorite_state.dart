import 'package:equatable/equatable.dart';

abstract class FavoriteState extends Equatable {
  const FavoriteState();

  @override
  List<Object?> get props => [];
}

class FavoriteInitial extends FavoriteState {}

class FavoriteLoading extends FavoriteState {}

class FavoriteSuccess extends FavoriteState {
  final String message;
  const FavoriteSuccess(this.message);

  @override
  List<Object?> get props => [message];
}

class FavoriteError extends FavoriteState {
  final String error;
  const FavoriteError(this.error);

  @override
  List<Object?> get props => [error];
}
