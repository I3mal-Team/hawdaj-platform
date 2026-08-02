part of 'store_guide_cubit.dart';

abstract class StoreGuideState extends Equatable {
  const StoreGuideState();
  @override
  List<Object?> get props => [];
}

class StoreGuideInitial extends StoreGuideState {
  const StoreGuideInitial();
}

class StoreGuideLoading extends StoreGuideState {
  const StoreGuideLoading();
}

class StoreGuideSuccess extends StoreGuideState {
  final GuideModel guide;
  const StoreGuideSuccess(this.guide);

  @override
  List<Object?> get props => [guide];
}

class StoreGuideError extends StoreGuideState {
  final String message;
  const StoreGuideError(this.message);

  @override
  List<Object?> get props => [message];
}
