part of 'fetch_offers_cubit.dart';

abstract class FetchOffersState extends Equatable {
  const FetchOffersState();

  @override
  List<Object?> get props => [];
}

class FetchOffersInitial extends FetchOffersState {}

class OffersLoading extends FetchOffersState {
  final PagingController<int, OfferItemModel> pagingController;

  const OffersLoading({required this.pagingController});

  @override
  List<Object?> get props => [pagingController];
}

class OffersError extends FetchOffersState {
  final String errMessage;

  const OffersError({required this.errMessage});

  @override
  List<Object?> get props => [errMessage];
}
