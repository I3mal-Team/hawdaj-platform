class GoogleAuthModel {
  final String idToken;
  final String accessToken;
  final String email;
  final String displayName;
  final String? photoUrl;
  final String googleUserId;

  GoogleAuthModel({
    required this.idToken,
    required this.accessToken,
    required this.email,
    required this.displayName,
    required this.googleUserId,
    this.photoUrl,
  });

  Map<String, dynamic> toJson() {
    return {
      'id_token': idToken,
      'access_token': accessToken,
      'email': email,
      'display_name': displayName,
      'google_user_id': googleUserId,
      if (photoUrl != null) 'photo_url': photoUrl,
    };
  }

  factory GoogleAuthModel.fromJson(Map<String, dynamic> json) {
    return GoogleAuthModel(
      idToken: json['id_token'],
      accessToken: json['access_token'],
      email: json['email'],
      displayName: json['display_name'],
      googleUserId: json['google_user_id'],
      photoUrl: json['photo_url'],
    );
  }
}
