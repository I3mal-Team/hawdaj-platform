import 'package:hawdaj/core/utils/app_assets.dart';

class OnboardingModel {
  final String image;
  final String title;
  final String description;
  OnboardingModel({
    required this.image,
    required this.title,
    required this.description,
  });

  static List<OnboardingModel> onboardingList = [
    OnboardingModel(
      image: AppAssets.onboarding(1),
      title: 'اكتشف إرث الجزيرة العربية',
      description:
          'هوْدَج يجمع لك كنوز المعرفة من قلب التاريخ والثقافة النجدية والحجازية',
    ),
    OnboardingModel(
      image: AppAssets.onboarding(2),
      title: 'أول نظرة... كل الحكاية',
      description: 'من لحظة الوصول،هوْدَج يفتحلك أبواب التاريخ والمكان...',
    ),
    OnboardingModel(
      image: AppAssets.onboarding(3),
      title: 'رحلة تبدأ من هوْدَج',
      description:
          'زي الرحّالة زمان، كل رحلة في هوْدَج تبدأ من سطر… وتنتهي في أثر.',
    ),
  ];
}
