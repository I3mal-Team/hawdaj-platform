import 'package:flutter_bloc/flutter_bloc.dart';
import 'package:equatable/equatable.dart';
import 'package:hawdaj/features/force_update/data/models/app_version_model.dart';
import 'package:hawdaj/features/force_update/data/repo/force_update_repo.dart';
import 'package:shared_preferences/shared_preferences.dart';

part 'force_update_state.dart';

class ForceUpdateCubit extends Cubit<ForceUpdateState> {
  final ForceUpdateRepo forceUpdateRepo;

  ForceUpdateCubit(this.forceUpdateRepo) : super(ForceUpdateInitial());

  static const String _ignoredVersionKey = 'ignored_update_version';

  /// Check if an update is available and needed
  Future<void> checkForUpdate() async {
    try {
      print('🔄 [ForceUpdate] Starting update check...');
      emit(ForceUpdateLoading());

      final latestVersion = await forceUpdateRepo.getLatestAppVersion();
      final currentVersion = await forceUpdateRepo.getAppCurrentVersion();

      print('📱 [ForceUpdate] Current version: $currentVersion');
      print('☁️ [ForceUpdate] Latest version: ${latestVersion.version}');
      print('🔒 [ForceUpdate] Is mandatory: ${latestVersion.isMandatory}');

      // Check if this version was already ignored by the user
      final ignoredVersion = await _getIgnoredVersion();
      print('🙈 [ForceUpdate] Ignored version: $ignoredVersion');

      // If the latest version was already ignored and it's not mandatory, don't show dialog
      if (!latestVersion.isMandatory &&
          ignoredVersion != null &&
          ignoredVersion == latestVersion.version) {
        print('✅ [ForceUpdate] Update was ignored by user - skipping');
        emit(ForceUpdateNotRequired());
        return;
      }

      final isUpdateRequired = await forceUpdateRepo.isUpdateRequired(
        latestVersion,
        currentVersion,
      );

      print('❓ [ForceUpdate] Is update required: $isUpdateRequired');

      if (isUpdateRequired) {
        // Mandatory update required
        print('🚨 [ForceUpdate] Emitting ForceUpdateRequired state');
        emit(
          ForceUpdateRequired(
            latestVersion: latestVersion,
            currentVersion: currentVersion,
          ),
        );
      } else if (_isNewerVersion(currentVersion, latestVersion.version)) {
        // Optional update available
        print('💡 [ForceUpdate] Emitting ForceUpdateAvailable state');
        emit(
          ForceUpdateAvailable(
            latestVersion: latestVersion,
            currentVersion: currentVersion,
          ),
        );
      } else {
        // App is up to date
        print('✅ [ForceUpdate] App is up to date');
        emit(ForceUpdateNotRequired());
      }
    } catch (e) {
      print('❌ [ForceUpdate] Error: $e');
      emit(ForceUpdateError(message: e.toString()));
    }
  }

  /// Check if the latest version is newer than current version
  bool _isNewerVersion(String current, String latest) {
    try {
      final currentParts = current.split('+')[0].split('.');
      final latestParts = latest.split('+')[0].split('.');

      for (int i = 0; i < 3; i++) {
        final currentNum = int.parse(currentParts[i]);
        final latestNum = int.parse(latestParts[i]);

        if (latestNum > currentNum) return true;
        if (latestNum < currentNum) return false;
      }

      return false; // Versions are equal
    } catch (e) {
      return false;
    }
  }

  /// Save the version that user chose to ignore
  Future<void> ignoreVersion(String version) async {
    try {
      final prefs = await SharedPreferences.getInstance();
      await prefs.setString(_ignoredVersionKey, version);
    } catch (e) {
      // Handle error silently
    }
  }

  /// Get the ignored version
  Future<String?> _getIgnoredVersion() async {
    try {
      final prefs = await SharedPreferences.getInstance();
      return prefs.getString(_ignoredVersionKey);
    } catch (e) {
      return null;
    }
  }

  /// Clear ignored version (useful when user updates the app)
  Future<void> clearIgnoredVersion() async {
    try {
      final prefs = await SharedPreferences.getInstance();
      await prefs.remove(_ignoredVersionKey);
    } catch (e) {
      // Handle error silently
    }
  }
}
