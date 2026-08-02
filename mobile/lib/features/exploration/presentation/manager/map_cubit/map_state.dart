// map_state.dart
part of 'map_cubit.dart';

abstract class MapState extends Equatable {
  const MapState();
  @override
  List<Object?> get props => [];
}

class MapInitial extends MapState {
  const MapInitial();
}

class MapTypeChanged extends MapState {
  final MapType mapType;
  const MapTypeChanged(this.mapType);

  @override
  List<Object?> get props => [mapType];
}

class ListVisibilityChanged extends MapState {
  final bool visible;
  const ListVisibilityChanged(this.visible);

  @override
  List<Object?> get props => [visible];
}

class ViewAsChanged extends MapState {
  final String viewAs;
  final int regionId;
  final int cityId;
  const ViewAsChanged({
    required this.viewAs,
    required this.regionId,
    required this.cityId,
  });

  @override
  List<Object?> get props => [viewAs, regionId, cityId];
}

class MarkersUpdated extends MapState {
  final Set<Marker> markers;
  const MarkersUpdated(this.markers);

  @override
  List<Object?> get props => [markers];
}

class MapError extends MapState {
  final String message;
  const MapError(this.message);

  @override
  List<Object?> get props => [message];
}
