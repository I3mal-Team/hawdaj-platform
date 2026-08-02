part of 'fetch_tour_guide_by_top_rated_cubit.dart';

abstract class FetchTourGuideByTopRatedState extends Equatable {
  const FetchTourGuideByTopRatedState();

  @override
  List<Object?> get props => [];
}

class FetchTourGuideByTopRatedInitial extends FetchTourGuideByTopRatedState {}

class FetchTourGuideByTopRatedLoading extends FetchTourGuideByTopRatedState {}

class FetchTourGuideByTopRatedSuccess extends FetchTourGuideByTopRatedState {
  final List<UnifiedPlaceModel> fetchTourGuideByTopRated;
  final int currentPage;
  final int totalPages;
  final int total;
  final bool hasNextPage;

  const FetchTourGuideByTopRatedSuccess({
    required this.fetchTourGuideByTopRated,
    required this.currentPage,
    required this.totalPages,
    required this.total,
    required this.hasNextPage,
  });

  @override
  List<Object?> get props => [
    fetchTourGuideByTopRated,
    currentPage,
    totalPages,
    total,
    hasNextPage,
  ];

  FetchTourGuideByTopRatedSuccess copyWith({
    List<UnifiedPlaceModel>? fetchTourGuideByTopRated,
    int? currentPage,
    int? totalPages,
    int? total,
    bool? hasNextPage,
  }) {
    return FetchTourGuideByTopRatedSuccess(
      fetchTourGuideByTopRated:
          fetchTourGuideByTopRated ?? this.fetchTourGuideByTopRated,
      currentPage: currentPage ?? this.currentPage,
      totalPages: totalPages ?? this.totalPages,
      total: total ?? this.total,
      hasNextPage: hasNextPage ?? this.hasNextPage,
    );
  }
}

class FetchTourGuideByTopRatedError extends FetchTourGuideByTopRatedState {
  final String message;

  const FetchTourGuideByTopRatedError({required this.message});

  @override
  List<Object?> get props => [message];
}

class FetchTourGuideByTopRatedLoadingMore
    extends FetchTourGuideByTopRatedState {
  final List<UnifiedPlaceModel> fetchTourGuideByTopRated;
  final int currentPage;
  final int totalPages;
  final int total;

  const FetchTourGuideByTopRatedLoadingMore({
    required this.fetchTourGuideByTopRated,
    required this.currentPage,
    required this.totalPages,
    required this.total,
  });

  @override
  List<Object?> get props => [
    fetchTourGuideByTopRated,
    currentPage,
    totalPages,
    total,
  ];
}
