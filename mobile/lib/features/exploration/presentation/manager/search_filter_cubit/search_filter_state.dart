part of 'search_filter_cubit.dart';

class SearchFilterState extends Equatable {
  final String searchText;
  final int? regionId;
  final int? cityId;
  final bool shouldSearch;

  const SearchFilterState({
    this.searchText = '',
    this.regionId,
    this.cityId,
    this.shouldSearch = false,
  });

  SearchFilterState copyWith({
    String? searchText,
    int? regionId,
    int? cityId,
    bool? shouldSearch,
  }) {
    return SearchFilterState(
      searchText: searchText ?? this.searchText,
      regionId: regionId ?? this.regionId,
      cityId: cityId ?? this.cityId,
      shouldSearch: shouldSearch ?? this.shouldSearch,
    );
  }

  @override
  List<Object?> get props => [searchText, regionId, cityId, shouldSearch];
}
