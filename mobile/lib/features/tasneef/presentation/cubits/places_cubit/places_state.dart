import 'package:equatable/equatable.dart';
import '../../../data/models/unified_place_model.dart';

abstract class PlacesState extends Equatable {
  const PlacesState();

  @override
  List<Object?> get props => [];
}

class PlacesInitial extends PlacesState {}

class PlacesLoading extends PlacesState {}

class PlacesSuccess extends PlacesState {
  final List<UnifiedPlaceModel> places;
  final int currentPage;
  final int totalPages;
  final int total;
  final bool hasNextPage;

  const PlacesSuccess({
    required this.places,
    required this.currentPage,
    required this.totalPages,
    required this.total,
    required this.hasNextPage,
  });

  @override
  List<Object?> get props => [
    places,
    currentPage,
    totalPages,
    total,
    hasNextPage,
  ];

  PlacesSuccess copyWith({
    List<UnifiedPlaceModel>? places,
    int? currentPage,
    int? totalPages,
    int? total,
    bool? hasNextPage,
  }) {
    return PlacesSuccess(
      places: places ?? this.places,
      currentPage: currentPage ?? this.currentPage,
      totalPages: totalPages ?? this.totalPages,
      total: total ?? this.total,
      hasNextPage: hasNextPage ?? this.hasNextPage,
    );
  }
}

class PlacesError extends PlacesState {
  final String message;

  const PlacesError({required this.message});

  @override
  List<Object?> get props => [message];
}

class PlacesLoadingMore extends PlacesState {
  final List<UnifiedPlaceModel> places;
  final int currentPage;
  final int totalPages;
  final int total;

  const PlacesLoadingMore({
    required this.places,
    required this.currentPage,
    required this.totalPages,
    required this.total,
  });

  @override
  List<Object?> get props => [places, currentPage, totalPages, total];
}
