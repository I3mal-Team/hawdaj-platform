part of 'my_trip_cubit.dart';

sealed class MyTripState extends Equatable {
  const MyTripState();

  @override
  List<Object?> get props => [];
}

final class MyTripInitial extends MyTripState {
  const MyTripInitial();
}

final class MyTripLoaded extends MyTripState {
  final PagingController<int, MyTripModel> pagingController;
  const MyTripLoaded(this.pagingController);
  // لا نضع الـ controller داخل props لتجنّب مشاكل المقارنة
  @override
  List<Object?> get props => [];
}

final class MyTripError extends MyTripState {
  final String message;
  const MyTripError(this.message);

  @override
  List<Object?> get props => [message];
}
