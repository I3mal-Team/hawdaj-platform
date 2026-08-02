import 'package:hawdaj/features/force_update/data/models/app_version_model.dart';

abstract class ForceUpdateRepo {
  Future<AppVersionModel> getLatestAppVersion();
  Future<String> getAppCurrentVersion();
  Future<bool> isUpdateRequired(
    AppVersionModel latestVersion,
    String currentVersion,
  );
}
