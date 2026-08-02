class TwitterAuthModel {
  final String accessToken;
  final String tokenSecret;
  final String userId;
  final String screenName;
  final String? email;

  TwitterAuthModel({
    required this.accessToken,
    required this.tokenSecret,
    required this.userId,
    required this.screenName,
    this.email,
  });

  Map<String, dynamic> toJson() {
    return {
      'access_token': accessToken,
      'token_secret': tokenSecret,
      'user_id': userId,
      'screen_name': screenName,
      if (email != null) 'email': email,
    };
  }

  factory TwitterAuthModel.fromJson(Map<String, dynamic> json) {
    return TwitterAuthModel(
      accessToken: json['access_token'],
      tokenSecret: json['token_secret'],
      userId: json['user_id'],
      screenName: json['screen_name'],
      email: json['email'],
    );
  }
}
