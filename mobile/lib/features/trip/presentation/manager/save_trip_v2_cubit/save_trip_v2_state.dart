part of 'save_trip_v2_cubit.dart';

abstract class SaveTripV2State {}

class SaveTripV2Initial extends SaveTripV2State {}

class SaveTripV2Loading extends SaveTripV2State {}

class SaveTripV2Success extends SaveTripV2State {
  final String message;
  SaveTripV2Success(this.message);
}

class SaveTripV2Failure extends SaveTripV2State {
  final String error;
  SaveTripV2Failure(this.error);
}
