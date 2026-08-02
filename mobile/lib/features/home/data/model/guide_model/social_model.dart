// social_model.dart
class SocialModel {
  final String? facebook;
  final String? twitter;
  final String? instagram;
  final String? linkedin;
  final String? youtube;
  final String? personalAccount;

  const SocialModel({
    this.facebook,
    this.twitter,
    this.instagram,
    this.linkedin,
    this.youtube,
    this.personalAccount,
  });

  factory SocialModel.fromJson(Map<String, dynamic> json) {
    return SocialModel(
      facebook: json['facebook'] as String?,
      twitter: json['twitter'] as String?,
      instagram: json['instagram'] as String?,
      linkedin: json['linkedin'] as String?,
      youtube: json['youtube'] as String?,
      // لو الـAPI أحيانًا بيرسل personal_site/PersonalSite هنقرأهم كبدائل
      personalAccount:
          (json['personal_account'] ??
                  json['personal_site'] ??
                  json['PersonalSite'])
              as String?,
    );
  }

  Map<String, dynamic> toJson() {
    return {
      'facebook': facebook,
      'twitter': twitter,
      'instagram': instagram,
      'linkedin': linkedin,
      'youtube': youtube,
      'personal_account': personalAccount,
    };
  }

  factory SocialModel.empty() {
    return const SocialModel(
      facebook: '',
      twitter: '',
      instagram: '',
      linkedin: '',
      youtube: '',
      personalAccount: '',
    );
  }
}
