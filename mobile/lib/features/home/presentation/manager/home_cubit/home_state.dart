import 'package:equatable/equatable.dart';
import 'package:hawdaj/features/home/data/model/home_response.dart';

abstract class HomeState extends Equatable {
  const HomeState();

  @override
  List<Object?> get props => [];
}

class HomeInitial extends HomeState {}

class HomeLoading extends HomeState {}

class HomeLoaded extends HomeState {
  final HomeResponse homeData;

  const HomeLoaded(this.homeData);

  @override
  List<Object?> get props => [homeData];
}

class HomeError extends HomeState {
  final String message;

  const HomeError(this.message);

  @override
  List<Object?> get props => [message];
}
