import 'package:equatable/equatable.dart';
import 'package:hawdaj/features/home/data/model/slider_model.dart';

abstract class SlidersState extends Equatable {
  const SlidersState();

  @override
  List<Object?> get props => [];
}

class SlidersInitial extends SlidersState {}

class SlidersLoading extends SlidersState {}

class SlidersLoaded extends SlidersState {
  final List<SliderModel> sliders;

  const SlidersLoaded(this.sliders);

  @override
  List<Object?> get props => [sliders];
}

class SlidersError extends SlidersState {
  final String message;

  const SlidersError(this.message);

  @override
  List<Object?> get props => [message];
}
