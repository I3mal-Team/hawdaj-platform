import 'dart:io';

class SocialLoginPlatform {
  /// Check if Google login should be shown (Available on both iOS and Android)
  static bool get showGoogleLogin => true; // Available on both platforms

  /// Check if Apple login should be shown (iOS only)
  static bool get showAppleLogin => Platform.isIOS;

  /// Check if Twitter login should be shown (hidden for now)
  static bool get showTwitterLogin => false; // Hidden as requested
}
