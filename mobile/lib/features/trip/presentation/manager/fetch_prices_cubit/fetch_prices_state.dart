part of 'fetch_prices_cubit.dart';

sealed class FetchPricesState extends Equatable {
  const FetchPricesState();

  @override
  List<Object> get props => [];
}

final class FetchPricesInitial extends FetchPricesState {}

final class FetchPricesLoading extends FetchPricesState {}

final class FetchPricesSuccess extends FetchPricesState {
  final List<PricesModel> pricesModel;
  const FetchPricesSuccess(this.pricesModel);
}

final class FetchPricesError extends FetchPricesState {
  final String message;
  const FetchPricesError(this.message);
}
