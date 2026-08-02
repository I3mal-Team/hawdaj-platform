part of 'prepare_trip_wizard_cubit.dart';

class PrepareTripWizardState extends Equatable {
  final PrepareTripModel draft;
  final int index;
  final bool saving;
  final String? error;

  const PrepareTripWizardState({
    required this.draft,
    this.index = 0,
    this.saving = false,
    this.error,
  });

  PrepareTripWizardState copyWith({
    PrepareTripModel? draft,
    int? index,
    bool? saving,
    String? error,
  }) {
    return PrepareTripWizardState(
      draft: draft ?? this.draft,
      index: index ?? this.index,
      saving: saving ?? this.saving,
      error: error,
    );
  }

  @override
  List<Object?> get props => [draft, index, saving, error];
}
