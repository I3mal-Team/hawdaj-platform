part of 'add_property_cubit.dart';

sealed class AddPropertyState extends Equatable {
  const AddPropertyState();

  @override
  List<Object?> get props => [];
}

final class AddPropertyInitial extends AddPropertyState {}

final class AddPropertyLoading extends AddPropertyState {}

final class AddPropertySuccess extends AddPropertyState {
  final String message;
  const AddPropertySuccess(this.message);

  @override
  List<Object?> get props => [message];
}

final class AddPropertyError extends AddPropertyState {
  final String message;
  const AddPropertyError(this.message);

  @override
  List<Object?> get props => [message];
}
