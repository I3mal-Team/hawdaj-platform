import 'package:flutter/material.dart';
import 'package:flutter_screenutil/flutter_screenutil.dart';
import 'package:hawdaj/core/components/spaces.dart';
import 'package:hawdaj/core/styles/app_text_styles.dart';

class TripCardWidget extends StatelessWidget {
  final String imagePath;
  final String title;
  final String description;
  final String buttonText;
  final VoidCallback? onButtonPressed;

  const TripCardWidget({
    super.key,
    required this.imagePath,
    required this.title,
    required this.description,
    required this.buttonText,
    this.onButtonPressed,
  });

  @override
  Widget build(BuildContext context) {
    return ClipRRect(
      borderRadius: BorderRadius.circular(24.r),
      child: Stack(
        children: [
          Image.asset(
            imagePath,
            width: double.infinity,
            height: 180.h,
            fit: BoxFit.cover,
            alignment: Alignment.center,
          ),
          Container(
            width: double.infinity,
            height: 180.h,
            color: Colors.black.withOpacity(0.08),
          ),
          Positioned(
            top: 24.h,
            left: 0,
            right: 0,
            child: Column(
              children: [
                Text(
                  title,
                  style: AppTextStyles.font24Regular.copyWith(
                    color: Colors.black,
                    fontWeight: FontWeight.bold,
                  ),

                  textAlign: TextAlign.center,
                ),
                HeightSpace(8.h),
                SizedBox(
                  width: 337.w,
                  height: 60.h,
                  child: Text(
                    description,
                    textAlign: TextAlign.center,
                    maxLines: 3,
                    overflow: TextOverflow.ellipsis,

                    style: AppTextStyles.font14Regular.copyWith(
                      color: const Color(0xFF202939),
                      // height: 1.43,
                    ),
                  ),
                ),

                Padding(
                  padding: EdgeInsets.symmetric(vertical: 4.h),
                  child: ElevatedButton(
                    style: ElevatedButton.styleFrom(
                      backgroundColor: Colors.white,
                      foregroundColor: Colors.black,
                      elevation: 0,
                      shape: RoundedRectangleBorder(
                        borderRadius: BorderRadius.circular(12.r),
                      ),
                    ),
                    onPressed: onButtonPressed,
                    child: Padding(
                      padding: EdgeInsets.symmetric(
                        horizontal: 24.w,
                        vertical: 8.h,
                      ),
                      child: Text(
                        buttonText,
                        style: AppTextStyles.font12Bold.copyWith(
                          color: const Color(0xFF202939),
                        ),
                      ),
                    ),
                  ),
                ),
              ],
            ),
          ),
        ],
      ),
    );
  }
}
