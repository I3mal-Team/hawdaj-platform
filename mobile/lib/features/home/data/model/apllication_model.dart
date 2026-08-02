class AppPromotionModel {
  final String appName;
  final String description;
  final String logoImage;
  final bool isFeatured;
  final String googlePlayUrl;
  final String appStoreUrl;

  AppPromotionModel({
    required this.appName,
    required this.description,
    required this.logoImage,
    required this.isFeatured,
    required this.googlePlayUrl,
    required this.appStoreUrl,
  });
}

final AppPromotionModel appInfo = AppPromotionModel(
  appName: "تطبيق المسافر",
  description:
      "مهما كانت رحلتك، المسافر هي بوابتك لحجز رحلتك التي تتمناها خلال دقائق فقط.",
  logoImage: "assets/images/almosafer_logo.png",
  isFeatured: true,
  googlePlayUrl: "https://play.google.com/store/apps/details?id=almosafer",
  appStoreUrl: "https://apps.apple.com/app/id1234567890",
);
