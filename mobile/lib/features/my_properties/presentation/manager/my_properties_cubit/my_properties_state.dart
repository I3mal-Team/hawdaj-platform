part of 'my_properties_cubit.dart';

abstract class MyPropertiesState extends Equatable {
  const MyPropertiesState();

  @override
  List<Object?> get props => [];
}

class MyPropertiesInitial extends MyPropertiesState {}

class MyPropertiesLoading extends MyPropertiesState {}

class MyPropertiesLoaded extends MyPropertiesState {
  final List<PropertyItem> items;
  final bool isLastPage;

  const MyPropertiesLoaded({required this.items, required this.isLastPage});

  @override
  List<Object?> get props => [items, isLastPage];
}

class MyPropertiesError extends MyPropertiesState {
  final String message;

  const MyPropertiesError(this.message);

  @override
  List<Object?> get props => [message];
}
