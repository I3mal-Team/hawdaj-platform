import 'package:chucker_flutter/chucker_flutter.dart';
import 'package:shared_preferences/shared_preferences.dart';

class AppEnvironmentManager {
  static final AppEnvironmentManager _instance =
      AppEnvironmentManager._internal();
  factory AppEnvironmentManager() => _instance;
  AppEnvironmentManager._internal();

  late String baseUrl;
  late String imageUrl;
  late String imageUploadUrl;

  String get currentEnv => _currentEnv;
  String _currentEnv = 'production';

  bool isChuckerEnabled = false;

  Future<void> init() async {
    final prefs = await SharedPreferences.getInstance();

    final env = prefs.getString('environment') ?? 'production';
    isChuckerEnabled = prefs.getBool('chuckerEnabled') ?? false;
    ChuckerFlutter.showOnRelease = isChuckerEnabled;

    if (env == 'test') {
      await setTest(save: false);
    } else {
      await setProduction(save: false);
    }
  }

  Future<void> setProduction({bool save = true}) async {
    baseUrl = 'https://dashboard.hawdaj.net/api/';
    imageUrl = 'https://dashboard.hawdaj.net/';
    imageUploadUrl = 'https://dashboard.hawdaj.net/uploads/';
    _currentEnv = 'production';
    if (save) {
      final prefs = await SharedPreferences.getInstance();
      prefs.setString('environment', 'production');
    }
  }

  Future<void> setTest({bool save = true}) async {
    baseUrl = 'https://test.dashboard.hawdaj.net/api/';
    imageUrl = 'https://test.dashboard.hawdaj.net/';
    imageUploadUrl = 'https://test.dashboard.hawdaj.net/uploads/';
    _currentEnv = 'test';
    if (save) {
      final prefs = await SharedPreferences.getInstance();
      prefs.setString('environment', 'test');
    }
  }

  Future<void> setChuckerEnabled(bool value) async {
    isChuckerEnabled = value;
    ChuckerFlutter.showOnRelease = value;
    final prefs = await SharedPreferences.getInstance();
    prefs.setBool('chuckerEnabled', value);
  }
}
