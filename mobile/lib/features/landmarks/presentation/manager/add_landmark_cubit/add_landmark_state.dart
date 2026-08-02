part of 'add_landmark_cubit.dart';

abstract class AddLandmarkState extends Equatable {
  const AddLandmarkState();

  @override
  List<Object?> get props => [];
}

class AddLandmarkInitial extends AddLandmarkState {}

class AddLandmarkReady extends AddLandmarkState {
  final int imageCount;
  
  const AddLandmarkReady({this.imageCount = 0});
  
  @override
  List<Object?> get props => [imageCount];
}

class AddLandmarkLoading extends AddLandmarkState {}

class AddLandmarkSuccess extends AddLandmarkState {
  final LandmarkModel landmark;

  const AddLandmarkSuccess(this.landmark);

  @override
  List<Object?> get props => [landmark];
}

class AddLandmarkError extends AddLandmarkState {
  final String message;

  const AddLandmarkError(this.message);

  @override
  List<Object?> get props => [message];
}
