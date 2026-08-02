import 'package:cached_network_image/cached_network_image.dart';
import 'package:flutter/material.dart';
import 'package:flutter_bloc/flutter_bloc.dart';
import 'package:flutter_screenutil/flutter_screenutil.dart';
import 'package:hawdaj/core/components/bottom_sheet/base_bottom_sheet.dart';
import 'package:hawdaj/core/components/custom_failure_toast.dart';
import 'package:hawdaj/core/components/primary_button.dart';
import 'package:hawdaj/core/components/seconday_button.dart';
import 'package:hawdaj/core/components/spaces.dart';
import 'package:hawdaj/core/databases/api/end_points.dart';
import 'package:hawdaj/core/utils/app_assets.dart';
import 'package:hawdaj/features/profile/presentation/manager/edit_profile_cubit/edit_profile_cubit.dart';

class UserImage extends StatelessWidget {
  const UserImage({super.key, required this.userPhoto});

  final String? userPhoto;

  @override
  Widget build(BuildContext context) {
    return BlocListener<EditProfileCubit, EditProfileState>(
      listener: (context, state) {
        if (state.errorMessage != null) {
          showCustomFailureToast(state.errorMessage!);
        }
      },
      child: BlocBuilder<EditProfileCubit, EditProfileState>(
        builder: (context, state) {
          final cubit = context.read<EditProfileCubit>();

          return GestureDetector(
            onTap: () {
              baseBottomSheet(
                context: context,
                child: Column(
                  children: [
                    _buildProfileImage(state),
                    HeightSpace(20.h),
                    PrimaryButton(
                      title: 'تغير صورة الملف الشخصي',
                      onTap: () {
                        Navigator.pop(context);

                        baseBottomSheet(
                          context: context,
                          child: Column(
                            children: [
                              _buildProfileImage(state),
                              HeightSpace(20.h),
                              PrimaryButton(
                                title: 'التقاط صورة بالكاميرا',
                                onTap: () {
                                  Navigator.pop(context);
                                  cubit.setImageFromCamera(context);
                                },
                                height: 50,
                                width: double.infinity,
                              ),
                              HeightSpace(20.h),
                              SecondaryButton(
                                title: 'تحميل من المعرض',
                                onTap: () {
                                  cubit.setImageFromGallery();
                                  Navigator.pop(context);
                                },
                              ),
                            ],
                          ),
                          hideNavBar: false,
                        );
                      },
                      height: 50,
                      width: double.infinity,
                    ),
                    HeightSpace(10.h),

                    if (state.imageFile != null) ...[
                      HeightSpace(10.h),
                      PrimaryButton(
                        title: 'إزالة الصورة المختارة',
                        onTap: () {
                          cubit.clearPickedImage();
                          Navigator.pop(context);
                        },
                        height: 50,
                        width: double.infinity,
                        backgroundColor: Colors.red,
                      ),
                    ],
                    HeightSpace(20.h),
                    SecondaryButton(
                      title: "إغلاق",
                      onTap: () => Navigator.pop(context),
                    ),
                  ],
                ),
                hideNavBar: false,
              );
            },
            child: Stack(
              clipBehavior: Clip.none,
              children: [
                _buildProfileImage(state),
                Positioned(
                  bottom: -10.r,
                  right: -10.r,
                  child: Container(
                    padding: EdgeInsets.all(5.r),
                    decoration: BoxDecoration(
                      borderRadius: BorderRadius.circular(4.r),
                      color: const Color(0xff6A4690),
                      border: Border.all(width: 2.r, color: Colors.white),
                    ),
                    width: 32.w,
                    height: 32.w,
                    child: Image.asset(AppAssets.edit, color: Colors.white),
                  ),
                ),
              ],
            ),
          );
        },
      ),
    );
  }

  Widget _buildProfileImage(EditProfileState state) {
    return Container(
      clipBehavior: Clip.hardEdge,
      width: 86.w,
      height: 86.h,
      decoration: BoxDecoration(
        borderRadius: BorderRadius.circular(12.r),
        border: Border.all(width: 1, color: const Color(0xff6A4690)),
      ),
      child: state.imageFile != null
          ? Image.file(
              state.imageFile!,
              width: double.infinity,
              height: double.infinity,
              fit: BoxFit.cover,
            )
          : (state.imageUrl != null && state.imageUrl!.isNotEmpty)
          ? Builder(
              builder: (context) {
                final baseUrl = state.imageUrl!.startsWith('http')
                    ? state.imageUrl!
                    : state.imageUrl!;
                final imageUrlWithTimestamp = baseUrl.contains('?')
                    ? '$baseUrl&t=${DateTime.now().millisecondsSinceEpoch}'
                    : '$baseUrl?t=${DateTime.now().millisecondsSinceEpoch}';

                return CachedNetworkImage(
                  imageUrl: imageUrlWithTimestamp,
                  cacheKey: imageUrlWithTimestamp,
                  fit: BoxFit.cover,
                  width: double.infinity,
                  height: double.infinity,
                  errorWidget: (context, url, error) =>
                      const Icon(Icons.person),
                );
              },
            )
          : (userPhoto != null && userPhoto!.isNotEmpty)
          ? Builder(
              builder: (context) {
                final baseUrl = userPhoto!.startsWith('http')
                    ? userPhoto!
                    : userPhoto!;
                final imageUrlWithTimestamp = baseUrl.contains('?')
                    ? '$baseUrl&t=${DateTime.now().millisecondsSinceEpoch}'
                    : '$baseUrl?t=${DateTime.now().millisecondsSinceEpoch}';

                return CachedNetworkImage(
                  imageUrl: imageUrlWithTimestamp,
                  cacheKey: imageUrlWithTimestamp,
                  fit: BoxFit.cover,
                  width: double.infinity,
                  height: double.infinity,
                  errorWidget: (context, url, error) =>
                      const Icon(Icons.person),
                );
              },
            )
          : const Icon(Icons.person, size: 40),
    );
  }
}
