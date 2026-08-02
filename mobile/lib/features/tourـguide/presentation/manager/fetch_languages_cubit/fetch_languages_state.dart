part of 'fetch_languages_cubit.dart';

sealed class FetchLanguagesState extends Equatable {
  const FetchLanguagesState();

  @override
  List<Object> get props => [];
}

final class FetchLanguagesInitial extends FetchLanguagesState {}

final class FetchLanguagesLoading extends FetchLanguagesState {}

final class FetchLanguagesSuccess extends FetchLanguagesState {
  final List<RegionModel> languages;
  const FetchLanguagesSuccess(this.languages);
}

final class FetchLanguagesFailure extends FetchLanguagesState {
  final String failure;
  const FetchLanguagesFailure(this.failure);
}
