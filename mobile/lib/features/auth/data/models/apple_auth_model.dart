import 'package:firebase_auth/firebase_auth.dart';

class AppleAuthModel {
  final String? identityToken;
  final String? authorizationCode;
  final String? userIdentifier;
  final String? email;
  final String? givenName;
  final String? familyName;
  final User? firebaseUser;

  AppleAuthModel({
    this.identityToken,
    this.authorizationCode,
    this.userIdentifier,
    this.email,
    this.givenName,
    this.familyName,
    this.firebaseUser,
  });

  String get displayName {
    // Try Firebase user display name first
    if (firebaseUser?.displayName != null &&
        firebaseUser!.displayName!.isNotEmpty) {
      return firebaseUser!.displayName!;
    }
    // Then try given name and family name
    if (givenName != null || familyName != null) {
      return '${givenName ?? ''} ${familyName ?? ''}'.trim();
    }
    // Fallback to email username or default
    return email?.split('@').first ?? 'Apple User';
  }

  String get emailAddress {
    return firebaseUser?.email ?? email ?? '';
  }

  String get providerId {
    return firebaseUser?.uid ?? userIdentifier ?? '';
  }

  Map<String, dynamic> toJson() {
    return {
      'identity_token': identityToken,
      'authorization_code': authorizationCode,
      'user_identifier': userIdentifier,
      'email': email,
      'given_name': givenName,
      'family_name': familyName,
    };
  }

  factory AppleAuthModel.fromJson(Map<String, dynamic> json) {
    return AppleAuthModel(
      identityToken: json['identity_token'],
      authorizationCode: json['authorization_code'],
      userIdentifier: json['user_identifier'],
      email: json['email'],
      givenName: json['given_name'],
      familyName: json['family_name'],
    );
  }
}
