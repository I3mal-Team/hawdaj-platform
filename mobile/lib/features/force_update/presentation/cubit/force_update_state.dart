part of 'force_update_cubit.dart';

abstract class ForceUpdateState extends Equatable {
  const ForceUpdateState();

  @override
  List<Object?> get props => [];
}

class ForceUpdateInitial extends ForceUpdateState {}

class ForceUpdateLoading extends ForceUpdateState {}

class ForceUpdateRequired extends ForceUpdateState {
  final AppVersionModel latestVersion;
  final String currentVersion;

  const ForceUpdateRequired({
    required this.latestVersion,
    required this.currentVersion,
  });

  @override
  List<Object?> get props => [latestVersion, currentVersion];
}

class ForceUpdateAvailable extends ForceUpdateState {
  final AppVersionModel latestVersion;
  final String currentVersion;

  const ForceUpdateAvailable({
    required this.latestVersion,
    required this.currentVersion,
  });

  @override
  List<Object?> get props => [latestVersion, currentVersion];
}

class ForceUpdateNotRequired extends ForceUpdateState {}

class ForceUpdateError extends ForceUpdateState {
  final String message;

  const ForceUpdateError({required this.message});

  @override
  List<Object?> get props => [message];
}
