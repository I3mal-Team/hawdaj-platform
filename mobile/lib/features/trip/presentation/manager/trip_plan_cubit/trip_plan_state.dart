import 'package:equatable/equatable.dart';
import 'package:hawdaj/features/tasneef/data/models/unified_place_model.dart';

class TripDayVM extends Equatable {
  final int index;
  final String displayDate;
  const TripDayVM({required this.index, required this.displayDate});

  @override
  List<Object?> get props => [index, displayDate];
}

class TripPlanState extends Equatable {
  final List<TripDayVM> days;
  final List<List<UnifiedPlaceModel>> dailyPlaces;

  const TripPlanState({required this.days, required this.dailyPlaces});

  TripPlanState copyWith({
    List<TripDayVM>? days,
    List<List<UnifiedPlaceModel>>? dailyPlaces,
  }) {
    return TripPlanState(
      days: days ?? this.days,
      dailyPlaces: dailyPlaces ?? this.dailyPlaces,
    );
  }

  @override
  List<Object?> get props => [days, dailyPlaces];
}

class TripPlanInitial extends TripPlanState {
  TripPlanInitial() : super(days: const [], dailyPlaces: const []);
}
