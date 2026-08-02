import 'package:cached_network_image/cached_network_image.dart';
import 'package:easy_localization/easy_localization.dart';
import 'package:flutter/material.dart';
import 'package:flutter_screenutil/flutter_screenutil.dart';
import 'package:hawdaj/core/components/spaces.dart';
import 'package:hawdaj/core/databases/api/end_points.dart';
import 'package:hawdaj/core/utils/app_assets.dart';
import 'package:hawdaj/core/utils/app_fonts.dart';
import 'package:hawdaj/features/profile/data/model/profile_reponce.dart';
import 'package:hawdaj/features/profile/presentation/views/widgets/profile_scores_item.dart';
import 'package:shimmer/shimmer.dart';

class UserProfileHeader extends StatelessWidget {
  final String userName;
  final String userEmail;
  final String userImagePath;
  final VoidCallback? onEditTap;
  final VoidCallback? onPointsTap;
  final VoidCallback? onTripsTap;
  final VoidCallback? onStoriesTap;
  final List<ProfileScoreData> scores;
  final ProfilePageResponse user;

  const UserProfileHeader({
    super.key,
    this.userName = ' زيدان',
    this.userEmail = 'ifo.as@gmail.com',
    this.userImagePath = AppAssets.splashBG,
    this.onEditTap,
    this.onPointsTap,
    this.onTripsTap,
    this.onStoriesTap,
    this.scores = const [],
    required this.user,
  });

  @override
  Widget build(BuildContext context) {
    final String? avatarUrl = user.data.personalData.photo;
    return Container(
      padding: EdgeInsets.all(12.r),
      decoration: BoxDecoration(
        borderRadius: BorderRadius.circular(12.r),
        color: const Color(0xffF8FAFC),
      ),
      child: Column(
        children: [
          Row(
            children: [
              Container(
                clipBehavior: Clip.hardEdge,
                width: 56.w,
                height: 56.w,
                decoration: BoxDecoration(
                  borderRadius: BorderRadius.circular(6.r),
                  border: Border.all(color: const Color(0xff6A4690)),
                ),
                child: ClipRRect(
                  borderRadius: BorderRadius.circular(6.r),
                  child: (avatarUrl != null)
                      ? Builder(
                          builder: (context) {
                            // إضافة timestamp للـ URL لإجبار تحديث الـ cache
                            final imageUrlWithTimestamp =
                                avatarUrl!.contains('?')
                                ? '$avatarUrl&t=${DateTime.now().millisecondsSinceEpoch}'
                                : '$avatarUrl?t=${DateTime.now().millisecondsSinceEpoch}';

                            print(
                              "📷 User avatar url => $imageUrlWithTimestamp",
                            );
                            return CachedNetworkImage(
                              imageUrl: imageUrlWithTimestamp,
                              cacheKey:
                                  imageUrlWithTimestamp, // استخدام الـ URL مع timestamp كـ cache key
                              fit: BoxFit.cover,
                              placeholder: (context, url) => const Center(
                                child: SizedBox(
                                  width: 18,
                                  height: 18,
                                  child: CircularProgressIndicator(
                                    strokeWidth: 2,
                                  ),
                                ),
                              ),
                              errorWidget: (context, url, error) {
                                print("❌ Error loading avatar from => $url");
                                return Image.asset(
                                  userImagePath,
                                  fit: BoxFit.cover,
                                );
                              },
                            );
                          },
                        )
                      : Builder(
                          builder: (context) {
                            print("👤 Default user image => $userImagePath");
                            return Image.asset(
                              userImagePath,
                              fit: BoxFit.cover,
                            );
                          },
                        ),
                ),
              ),
              WidthSpace(8.w),
              Expanded(
                child: Column(
                  crossAxisAlignment: CrossAxisAlignment.start,
                  children: [
                    Text(
                      userName,
                      //  textAlign: TextAlign.right,
                      style: TextStyle(
                        color: Colors.black,
                        fontSize: 18.sp,
                        fontFamily: AppFonts.theYearOfTheCamel,
                        fontWeight: FontWeight.w900,
                      ),
                    ),
                    HeightSpace(4.h),
                    Text(
                      userEmail,
                      textAlign: TextAlign.right,
                      style: TextStyle(
                        color: const Color(0xFF4B5565),
                        fontSize: 12.sp,
                        fontFamily: AppFonts.brandoArabic,
                        fontWeight: FontWeight.w500,
                      ),
                    ),
                  ],
                ),
              ),
              WidthSpace(8.w),
              GestureDetector(
                onTap: onEditTap,
                child: Container(
                  padding: EdgeInsets.symmetric(
                    horizontal: 16.w,
                    vertical: 8.h,
                  ),
                  decoration: BoxDecoration(
                    borderRadius: BorderRadius.circular(8.r),
                    color: const Color(0xffF2EBF6),
                  ),
                  child: Row(
                    children: [
                      Image.asset(AppAssets.edit, width: 16.w),
                      WidthSpace(4.w),
                      Text(
                        'edit'.tr(),
                        style: TextStyle(
                          color: const Color(0xFF6A4690),
                          fontSize: 12.sp,
                          fontFamily: AppFonts.brandoArabic,
                          fontWeight: FontWeight.w500,
                        ),
                      ),
                    ],
                  ),
                ),
              ),
            ],
          ),
          HeightSpace(12.h),
          Row(
            children: scores.isEmpty
                ? [
                    // ProfileScoresItem(
                    //   onTap: onPointsTap,
                    //   points: user.data.personalData.totalPoints.toString(),
                    //   iconPath: AppAssets.point,
                    //   title: "points".tr(),
                    // ),
                    WidthSpace(12.w),
                    ProfileScoresItem(
                      onTap: onTripsTap,
                      points: user.data.totalTrips.toString(),
                      iconPath: AppAssets.imageTrip,
                      title: "trips_app".tr(),
                    ),
                    WidthSpace(12.w),
                    ProfileScoresItem(
                      onTap: onStoriesTap,
                      iconPath: AppAssets.imageStore,
                      points: user.data.totalStories.toString(),
                      title: "stories_app".tr(),
                    ),
                  ]
                : scores
                      .map(
                        (score) => [
                          ProfileScoresItem(
                            iconPath: score.iconPath,
                            points: score.points,
                            title: '',
                          ),
                          if (score != scores.last) WidthSpace(12.w),
                        ],
                      )
                      .expand((element) => element)
                      .toList(),
          ),
        ],
      ),
    );
  }
}

class ProfileScoreData {
  final String iconPath;
  final String points;

  const ProfileScoreData({required this.iconPath, required this.points});
}

class UserProfileHeaderShimmer extends StatelessWidget {
  const UserProfileHeaderShimmer({super.key});

  @override
  Widget build(BuildContext context) {
    return Shimmer.fromColors(
      baseColor: const Color(0xFFE0E0E0),
      highlightColor: const Color(0xFFF5F5F5),
      child: Container(
        padding: EdgeInsets.all(12.r),
        decoration: BoxDecoration(
          borderRadius: BorderRadius.circular(12.r),
          color: const Color(0xffF8FAFC),
        ),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            // العنوان العلوي
            Row(
              crossAxisAlignment: CrossAxisAlignment.center,
              children: [
                // صورة المستخدم
                Container(
                  width: 56.w,
                  height: 56.w,
                  decoration: BoxDecoration(
                    color: Colors.white,
                    borderRadius: BorderRadius.circular(6.r),
                  ),
                ),
                WidthSpace(12.w),

                // الاسم والبريد
                Expanded(
                  child: Column(
                    crossAxisAlignment: CrossAxisAlignment.start,
                    children: [
                      Container(
                        height: 18.h,
                        width: 110.w,
                        decoration: BoxDecoration(
                          color: Colors.white,
                          borderRadius: BorderRadius.circular(4.r),
                        ),
                      ),
                      HeightSpace(6.h),
                      Container(
                        height: 14.h,
                        width: 160.w,
                        decoration: BoxDecoration(
                          color: Colors.white,
                          borderRadius: BorderRadius.circular(4.r),
                        ),
                      ),
                    ],
                  ),
                ),

                // زر التعديل
                Container(
                  height: 34.h,
                  padding: EdgeInsets.symmetric(horizontal: 12.w),
                  decoration: BoxDecoration(
                    color: Colors.white,
                    borderRadius: BorderRadius.circular(8.r),
                  ),
                  child: Row(
                    children: [
                      Container(
                        width: 16.w,
                        height: 16.h,
                        decoration: BoxDecoration(
                          color: Colors.white,
                          borderRadius: BorderRadius.circular(3.r),
                        ),
                      ),
                      WidthSpace(6.w),
                      Container(
                        width: 36.w,
                        height: 14.h,
                        decoration: BoxDecoration(
                          color: Colors.white,
                          borderRadius: BorderRadius.circular(3.r),
                        ),
                      ),
                    ],
                  ),
                ),
              ],
            ),

            HeightSpace(20.h),

            // عناصر النقاط
            Row(
              children: List.generate(3, (index) {
                return Padding(
                  padding: EdgeInsets.only(right: index != 2 ? 12.w : 0),
                  child: Container(
                    width: 100.w,
                    height: 64.h,
                    decoration: BoxDecoration(
                      color: Colors.white,
                      borderRadius: BorderRadius.circular(10.r),
                    ),
                  ),
                );
              }),
            ),
          ],
        ),
      ),
    );
  }
}
