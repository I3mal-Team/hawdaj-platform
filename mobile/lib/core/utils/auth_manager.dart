import 'package:hawdaj/features/auth/data/models/user_model.dart';
import 'package:hawdaj/core/utils/storage_service.dart';
import 'dart:convert';
import 'dart:async';

class AuthManager {
  static final AuthManager _instance = AuthManager._internal();
  factory AuthManager() => _instance;
  AuthManager._internal();

  static const String _userKey = 'user_data';
  static const String _tokenKey = 'auth_token';

  /// Stream Controller to listen to auth changes
  static final StreamController<bool> _authStreamController =
      StreamController<bool>.broadcast();

  /// Public stream for authentication state
  static Stream<bool> get authStream => _authStreamController.stream;

  /// Save user data and notify listeners
  static Future<void> saveUser(UserModel? user, String? token) async {
    if (user == null && token == null) {
      throw 'user and token are both null';
    }
    if (user != null) {
      await StorageService.saveString(_userKey, jsonEncode(user.toJson()));
    }
    if (token != null) {
      await StorageService.saveSecure(_tokenKey, token);
    }
    _authStreamController.add(true); // Notify UI
  }

  /// Get user data
  static Future<UserModel?> getUser() async {
    String? userJson = StorageService.getString(_userKey);
    if (userJson == null || userJson.isEmpty) return null;

    // Get token securely
    String? token = await getToken();
    if (token == null) return null;
    // Attach token to user
    return UserModel.fromJson(jsonDecode(userJson));
  }

  /// Get token securely
  static Future<String?> getToken() async {
    return await StorageService.getSecure(_tokenKey);
  }

  /// Check if user is logged in
  static Future<bool> isLoggedIn() async {
    String? token = await getToken();
    if (token == null || token.isEmpty) return false;

    return true;
  }

  /// Logout user and notify UI
  static Future<void> logout() async {
    await StorageService.removeString(_userKey);
    await StorageService.removeSecure(_tokenKey);
    _authStreamController.add(false); // Notify UI
  }

  /// Dispose stream controller
  static void dispose() {
    _authStreamController.close();
  }
}

//*************** How to use ***************

//***** Save user after login
// await AuthManager.saveUser(user, token);

//***** Check if user is logged in
// bool isLoggedIn = await AuthManager.isLoggedIn();

//***** Logout user
// await AuthManager.logout();

//***** Listen to authentication state in UI (IMPORTANT)
// StreamBuilder<bool>(
//   stream: AuthManager.authStream,
//   builder: (context, snapshot) {
//     bool isLoggedIn = snapshot.data ?? false;
//     return isLoggedIn ? HomeScreen() : LoginScreen();
//   },
// );
