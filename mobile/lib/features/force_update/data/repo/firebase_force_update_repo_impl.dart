import 'package:cloud_firestore/cloud_firestore.dart';
import 'package:hawdaj/features/force_update/data/models/app_version_model.dart';
import 'package:hawdaj/features/force_update/data/repo/force_update_repo.dart';
import 'package:package_info_plus/package_info_plus.dart';
import 'package:version/version.dart';

class FirebaseForceUpdateRepoImpl implements ForceUpdateRepo {
  @override
  Future<AppVersionModel> getLatestAppVersion() async {
    print('☁️ [FirebaseRepo] Fetching latest version from Firebase...');
    FirebaseFirestore firestore = FirebaseFirestore.instance;
    CollectionReference versionsRef = firestore.collection('app_versions');
    final doc = await versionsRef.doc('latest_version').get();

    if (doc.exists) {
      final data = doc.data() as Map<String, dynamic>;
      print('☁️ [FirebaseRepo] Firebase data: $data');
      final model = AppVersionModel.fromJson(data);
      print(
        '☁️ [FirebaseRepo] Parsed version: ${model.version}, isMandatory: ${model.isMandatory}',
      );
      return model;
    } else {
      print('❌ [FirebaseRepo] No latest_version document found in Firebase');
      throw Exception('No latest version found');
    }
  }

  @override
  Future<String> getAppCurrentVersion() async {
    print('📱 [FirebaseRepo] Getting current app version...');
    final info = await PackageInfo.fromPlatform();
    var version = info.version;
    var buildNumber = info.buildNumber;
    final fullVersion = '$version+$buildNumber';
    print('📱 [FirebaseRepo] Current app version: $fullVersion');
    return fullVersion;
  }

  @override
  Future<bool> isUpdateRequired(
    AppVersionModel latestVersion,
    String currentVersion,
  ) async {
    print('🔍 [FirebaseRepo] Checking if update is required...');
    print(
      '🔍 [FirebaseRepo] Latest: ${latestVersion.version}, Current: $currentVersion',
    );

    var latest = Version.parse(latestVersion.version);
    var current = Version.parse(currentVersion);

    final isNewer = latest > current;
    final isMandatory = latestVersion.isMandatory;
    final isRequired = isNewer && isMandatory;

    print(
      '🔍 [FirebaseRepo] Is newer: $isNewer, Is mandatory: $isMandatory, Is required: $isRequired',
    );

    return isRequired;
  }
}
