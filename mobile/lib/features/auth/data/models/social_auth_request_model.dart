class SocialAuthRequestModel {
  final String providerType; // 'google' or 'twitter'
  final String providerId; // The user ID from the provider
  final String? email;
  final String? name;

  SocialAuthRequestModel({
    required this.providerType,
    required this.providerId,
    this.email,
    this.name,
  });

  Map<String, dynamic> toJson() {
    return {
      'provider_type': providerType,
      'provider_id': providerId,
      if (email != null) 'email': email,
      if (name != null) 'name': name,
    };
  }

  factory SocialAuthRequestModel.fromJson(Map<String, dynamic> json) {
    return SocialAuthRequestModel(
      providerType: json['provider_type'],
      providerId: json['provider_id'],
      email: json['email'],
      name: json['name'],
    );
  }
}
