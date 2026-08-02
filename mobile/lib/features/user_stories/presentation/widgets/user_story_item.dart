import 'package:flutter/material.dart';
import 'package:flutter_bloc/flutter_bloc.dart';
import 'package:flutter_screenutil/flutter_screenutil.dart';
import 'package:flutter_svg/svg.dart';
import 'package:hawdaj/core/components/custom_failure_toast.dart';
import 'package:hawdaj/core/components/custom_success_toast.dart';
import 'package:hawdaj/core/components/text_components/generic_text.dart';
import 'package:hawdaj/core/download_helper.dart';
import 'package:hawdaj/core/styles/app_colors.dart';
import 'package:hawdaj/core/utils/app_assets.dart';
import 'package:hawdaj/features/profile/data/model/story_model.dart'
    show StoryModel;
import 'package:hawdaj/features/user_stories/data/models/user_story_model.dart';
import 'package:hawdaj/features/user_stories/presentation/remove/remove_story_cubit.dart';

class UserStoryItem extends StatelessWidget {
  final UserStoryModel story;

  const UserStoryItem({super.key, required this.story});

  @override
  Widget build(BuildContext context) {
    return Container(
      decoration: ShapeDecoration(
        color: Colors.white /* Dark-white */,
        shape: RoundedRectangleBorder(
          side: BorderSide(
            width: 1,
            strokeAlign: BorderSide.strokeAlignCenter,
            color: const Color(0xFFF1F1F1) /* Dark-100 */,
          ),
          borderRadius: BorderRadius.circular(16),
        ),
      ),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          // Story image with action buttons
          Padding(
            padding: const EdgeInsets.all(4.0),
            child: Stack(
              children: [
                ClipRRect(
                  borderRadius: BorderRadius.vertical(
                    top: Radius.circular(12.r),
                    bottom: Radius.circular(12.r),
                  ),
                  child: Image.network(
                    story.file ?? '',
                    width: double.infinity,
                    height: 142.h,
                    fit: BoxFit.cover,
                    errorBuilder: (context, error, stackTrace) {
                      return Container(
                        width: double.infinity,
                        height: 120.h,
                        color: Colors.grey[300],
                        child: const Icon(
                          Icons.image_not_supported,
                          color: Colors.grey,
                        ),
                      );
                    },
                  ),
                ),
                //  Action buttons overlay
                Positioned(
                  top: 8.h,
                  right: 8.w,
                  child: Row(
                    children: [
                      // Download button
                      GestureDetector(
                        onTap: () async {
                          if (story.file != null && story.file!.isNotEmpty) {
                            try {
                              await downloadFile(
                                story.file!,
                                fileName: story.file!.split('/').last,
                              );
                              showCustomSuccessToast("تم تحميل الملف بنجاح ✅");
                            } catch (e) {
                              showCustomFailureToast("حدث خطأ أثناء التحميل ❌");
                            }
                          } else {
                            showCustomFailureToast("لا يوجد ملف للتحميل");
                          }
                        },
                        child: Image.asset(AppAssets.buttonsDownloadStore),
                      ),

                      SizedBox(width: 8.w),
                      // Delete button
                      BlocListener<RemoveStoryCubit, RemoveStoryState>(
                        listener: (context, state) {
                          if (state is RemoveStorySuccess) {
                            Future.delayed(Duration(milliseconds: 100), () {
                              if (Navigator.canPop(context)) {
                                Navigator.pop(context);
                              }
                            });
                            showCustomSuccessToast(state.message);
                            // Navigator.pop(context);
                          }
                          if (state is RemoveStoryError) {
                            showCustomFailureToast(state.error);
                          }
                        },
                        child: GestureDetector(
                          onTap: () {
                            context.read<RemoveStoryCubit>().removeStory(
                              story.id ?? 0,
                            );
                          },
                          child: SvgPicture.asset(AppAssets.buttonsRemoveStore),
                        ),
                      ),
                    ],
                  ),
                ),
              ],
            ),
          ),

          // Story info
          if (story.text != null && story.text != 'null')
            Padding(
              padding: EdgeInsets.all(12.w),
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  // Story name
                  Row(
                    children: [
                      Expanded(
                        child: GenericText(
                          story.text ?? '',
                          color: AppColors.black,
                          size: 12.sp,
                          weight: FontWeight.w500,
                          textAlign: TextAlign.start,
                        ),
                      ),
                    ],
                  ),
                  if (story.text != null && story.text != 'null')
                    SizedBox(height: 8.h),

                  // File size
                ],
              ),
            ),
        ],
      ),
    );
  }
}
