import 'package:google_sign_in/google_sign_in.dart';
import 'package:firebase_auth/firebase_auth.dart';
import 'package:sign_in_with_apple/sign_in_with_apple.dart';
import 'dart:convert';
import 'dart:io';
import '../data/models/google_auth_model.dart';
import '../data/models/apple_auth_model.dart';
import '../data/models/social_auth_request_model.dart';

class SocialAuthService {
  static final SocialAuthService _instance = SocialAuthService._internal();
  factory SocialAuthService() => _instance;
  SocialAuthService._internal();

  late GoogleSignIn _googleSignIn;
  final FirebaseAuth _firebaseAuth = FirebaseAuth.instance;

  /// Initialize social auth services with environment variables
  void initialize() {
    // Initialize Google Sign-In (Available on both iOS and Android)
    // Client ID configuration comes from google-services.json (Android) and GoogleService-Info.plist (iOS)
    _googleSignIn = GoogleSignIn(
      scopes: ['email', 'profile'],
      // Force account selection to ensure fresh tokens
      forceCodeForRefreshToken: true,
    );
    print('SocialAuthService initialized');
  }

  /// Check if Google Sign In is available (both iOS and Android)
  static bool get isGoogleSignInAvailable => true;

  /// Check if Apple Sign In is available (iOS only)
  static bool get isAppleSignInAvailable => Platform.isIOS;

  /// Check if Apple Sign In is available on this device (async version)
  Future<bool> checkAppleSignInAvailability() async {
    if (!Platform.isIOS) return false;

    try {
      return await SignInWithApple.isAvailable();
    } catch (e) {
      print('Error checking Apple Sign-In availability: $e');
      return false;
    }
  }

  /// Get available sign-in methods for current platform
  static List<String> get availableSignInMethods {
    List<String> methods = [];

    // Google Sign-In is available on both platforms
    methods.add('google');

    // Apple Sign-In is only available on iOS
    if (Platform.isIOS) {
      methods.add('apple');
    }

    return methods;
  }

  /// Sign in with Google
  Future<GoogleAuthModel?> signInWithGoogle() async {
    try {
      print('Starting Google Sign-In process...');
      final GoogleSignInAccount? googleUser = await _googleSignIn.signIn();

      if (googleUser == null) {
        // User cancelled the sign-in
        print('Google Sign-In cancelled by user');
        return null;
      }

      print('Google user signed in: ${googleUser.email}');
      print('Google user ID: ${googleUser.id}');
      print('Google user display name: ${googleUser.displayName}');

      final GoogleSignInAuthentication googleAuth =
          await googleUser.authentication;

      print(
        'ID Token available: ${googleAuth.idToken != null && googleAuth.idToken!.isNotEmpty}',
      );
      print(
        'Access Token available: ${googleAuth.accessToken != null && googleAuth.accessToken!.isNotEmpty}',
      );

      // For Google Sign-In, we need at least an access token
      // ID token might be null in some configurations, which is acceptable
      if (googleAuth.accessToken == null || googleAuth.accessToken!.isEmpty) {
        throw Exception('Google Sign-In failed: Access token is null or empty');
      }

      final googleAuthModel = GoogleAuthModel(
        idToken: googleAuth.idToken ?? '',
        accessToken: googleAuth.accessToken!,
        email: googleUser.email,
        displayName: googleUser.displayName ?? '',
        photoUrl: googleUser.photoUrl,
        // Store the Google user ID for later use
        googleUserId: googleUser.id,
      );

      print('Google authentication model created successfully');
      return googleAuthModel;
    } catch (error) {
      print('Google Sign-In Error: $error');
      rethrow;
    }
  }

  /// Sign in with Apple (iOS only)
  Future<AppleAuthModel?> signInWithApple() async {
    if (!Platform.isIOS) {
      throw UnsupportedError('Apple Sign In is only available on iOS');
    }

    try {
      // Check if Apple Sign In is available on this device
      final isAvailable = await SignInWithApple.isAvailable();
      if (!isAvailable) {
        throw UnsupportedError(
          'Apple Sign In is not available on this device. Please try on a physical iOS device with iOS 13+ or use a different sign-in method.',
        );
      }

      // Request Apple ID credential
      final credential = await SignInWithApple.getAppleIDCredential(
        scopes: [
          AppleIDAuthorizationScopes.email,
          AppleIDAuthorizationScopes.fullName,
        ],
        nonce: null, // Let the plugin generate a nonce
        state: null, // Optional state parameter
      );

      // Create Firebase credential from Apple credential
      final oauthCredential = OAuthProvider("apple.com").credential(
        idToken: credential.identityToken,
        accessToken: credential.authorizationCode,
      );

      // Sign in to Firebase with Apple credential
      final userCredential = await _firebaseAuth.signInWithCredential(
        oauthCredential,
      );

      return AppleAuthModel(
        identityToken: credential.identityToken,
        authorizationCode: credential.authorizationCode,
        userIdentifier: credential.userIdentifier,
        email: credential.email,
        givenName: credential.givenName,
        familyName: credential.familyName,
        firebaseUser: userCredential.user,
      );
    } catch (e) {
      print('Apple Sign-In Error: $e');
      if (e is SignInWithAppleAuthorizationException) {
        switch (e.code) {
          case AuthorizationErrorCode.canceled:
            print('Apple Sign-In: User cancelled the authorization');
            return null;
          case AuthorizationErrorCode.failed:
            print('Apple Sign-In: Authorization failed');
            throw Exception('Apple Sign-In failed. Please try again.');
          case AuthorizationErrorCode.invalidResponse:
            print('Apple Sign-In: Invalid response from Apple');
            throw Exception(
              'Apple Sign-In received an invalid response. Please try again.',
            );
          case AuthorizationErrorCode.notHandled:
            print('Apple Sign-In: Authorization request not handled');
            throw Exception(
              'Apple Sign-In request was not handled. Please try again.',
            );
          case AuthorizationErrorCode.unknown:
            print('Apple Sign-In: Unknown error occurred - ${e.message}');
            // Error 1000 often indicates simulator issues or missing entitlements
            if (e.message.contains('1000')) {
              throw Exception(
                'Apple Sign-In is not available on the iOS Simulator. Please test on a physical iOS device or use a different sign-in method.',
              );
            }
            throw Exception(
              'Apple Sign-In encountered an unknown error. Please try again or use a different sign-in method.',
            );
          default:
            print('Apple Sign-In: Unhandled error code: ${e.code}');
            throw Exception('Apple Sign-In failed with error: ${e.code}');
        }
      }
      rethrow;
    }
  }

  /// Sign in with Twitter (temporarily disabled - will implement with web auth later)
  Future<Map<String, dynamic>?> signInWithTwitter() async {
    throw UnimplementedError(
      'Twitter authentication will be implemented using web auth in a future update',
    );
  }

  /// Create social auth request for Google
  SocialAuthRequestModel createGoogleAuthRequest(GoogleAuthModel googleAuth) {
    // Try to extract user ID from ID token, with fallback options
    String providerId = _getGoogleProviderId(googleAuth);

    return SocialAuthRequestModel(
      providerType: 'google',
      providerId: providerId,
      email: googleAuth.email,
      name: googleAuth.displayName,
    );
  }

  /// Get Google provider ID with multiple fallback strategies
  String _getGoogleProviderId(GoogleAuthModel googleAuth) {
    print('Determining provider ID for Google user: ${googleAuth.email}');

    // Strategy 1: Try to extract from ID token if available
    if (googleAuth.idToken.isNotEmpty) {
      print('Attempting to extract provider ID from ID token...');
      try {
        final providerId = _extractProviderIdFromIdToken(googleAuth.idToken);
        print('Successfully extracted provider ID from JWT: $providerId');
        return providerId;
      } catch (e) {
        print('Failed to extract provider ID from JWT token: $e');
      }
    } else {
      print('ID token is empty, skipping JWT extraction');
    }

    // Strategy 2: Use the Google user ID directly
    if (googleAuth.googleUserId.isNotEmpty) {
      print('Using Google user ID as provider ID: ${googleAuth.googleUserId}');
      return googleAuth.googleUserId;
    } else {
      print('Google user ID is empty');
    }

    // Strategy 3: If all else fails, create a hash from email (last resort)
    if (googleAuth.email.isNotEmpty) {
      // Create a simple hash from email as a fallback
      final emailHash = googleAuth.email.hashCode.abs().toString();
      print('Using email hash as provider ID fallback: $emailHash');
      return emailHash;
    }

    throw Exception('Unable to determine provider ID for Google user');
  }

  /// Extract provider ID (sub) from JWT ID token
  String _extractProviderIdFromIdToken(String idToken) {
    try {
      // Check if token is empty or null
      if (idToken.isEmpty) {
        throw Exception('ID token is empty');
      }

      // JWT has 3 parts separated by dots
      final parts = idToken.split('.');
      if (parts.length != 3) {
        throw Exception(
          'Invalid JWT token format - expected 3 parts, got ${parts.length}',
        );
      }

      // Decode the payload (second part)
      final payload = parts[1];

      // Add padding if needed for base64 decoding
      String normalizedPayload = payload;
      switch (payload.length % 4) {
        case 2:
          normalizedPayload += '==';
          break;
        case 3:
          normalizedPayload += '=';
          break;
      }

      final decodedBytes = base64Url.decode(normalizedPayload);
      final decodedPayload = utf8.decode(decodedBytes);
      final payloadMap = jsonDecode(decodedPayload) as Map<String, dynamic>;

      // Extract sub field (user ID)
      final sub = payloadMap['sub'];
      if (sub == null || sub.toString().isEmpty) {
        throw Exception('No sub field found in JWT token payload');
      }

      return sub.toString();
    } catch (e) {
      print('Error extracting provider ID from token: $e');
      rethrow;
    }
  }

  /// Create social auth request for Apple
  SocialAuthRequestModel createAppleAuthRequest(AppleAuthModel appleAuth) {
    return SocialAuthRequestModel(
      providerType: 'apple',
      providerId: appleAuth.providerId,
      email: appleAuth.emailAddress,
      name: appleAuth.displayName,
    );
  }

  /// Create social auth request for Twitter (placeholder)
  SocialAuthRequestModel createTwitterAuthRequest(
    Map<String, dynamic> twitterAuth,
  ) {
    return SocialAuthRequestModel(
      providerType: 'twitter',
      providerId: twitterAuth['user_id'] ?? '',
      email: twitterAuth['email'],
      name: twitterAuth['screen_name'],
    );
  }

  /// Sign out from Google
  Future<void> signOutGoogle() async {
    try {
      await _googleSignIn.signOut();
    } catch (error) {
      print('Google Sign-Out Error: $error');
    }
  }

  /// Sign out from Apple (via Firebase)
  Future<void> signOutApple() async {
    try {
      await _firebaseAuth.signOut();
    } catch (error) {
      print('Apple Sign-Out Error: $error');
    }
  }

  /// Sign out from Twitter (Twitter login doesn't have a built-in sign out)
  Future<void> signOutTwitter() async {
    // Twitter login doesn't maintain session, so no explicit sign out needed
    print('Twitter sign out completed');
  }

  /// Sign out from all social providers
  Future<void> signOutAll() async {
    await Future.wait([signOutGoogle(), signOutApple(), signOutTwitter()]);
  }
}
