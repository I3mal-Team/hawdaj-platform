part of 'stores_details_cubit.dart';

sealed class StoresDetailsState extends Equatable {
  const StoresDetailsState();

  @override
  List<Object> get props => [];
}

final class StoresDetailsInitial extends StoresDetailsState {}

final class StoresDetailsLoading extends StoresDetailsState {}

final class StoresDetailsSuccess extends StoresDetailsState {
  final ZadModel storesDetails;
  const StoresDetailsSuccess(this.storesDetails);
}

final class StoresDetailsError extends StoresDetailsState {
  final String errMessage;
  const StoresDetailsError(this.errMessage);
}
