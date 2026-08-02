import 'package:easy_localization/easy_localization.dart';
import 'package:flutter/material.dart';
import 'package:flutter_screenutil/flutter_screenutil.dart';
import 'package:flutter_svg/svg.dart';
import 'package:hawdaj/core/components/spaces.dart';
import 'package:hawdaj/core/styles/app_colors.dart';
import 'package:hawdaj/core/styles/app_text_styles.dart';
import 'package:hawdaj/core/utils/app_assets.dart';
import 'package:hawdaj/features/events/presentation/view/widgets/open_link.dart';

class BookTicketButtomWidget extends StatelessWidget {
  final String ticketLink;
  const BookTicketButtomWidget({super.key, required this.ticketLink});

  @override
  Widget build(BuildContext context) {
    return Positioned(
      bottom: 0,
      left: 0,
      right: 0,
      child: Padding(
        padding: EdgeInsets.all(12.h),
        child: Container(
          decoration: BoxDecoration(
            boxShadow: [
              BoxShadow(
                color: Colors.white.withOpacity(0.9), // 🔥 ظل أبيض
                blurRadius: 25, // مستوى الانتشار
                spreadRadius: 5, // الاتساع
                offset: Offset(0, 5), // اتجاه الظل
              ),
            ],
          ),
          child: Row(
            crossAxisAlignment: CrossAxisAlignment.center,
            children: [
              /// النصوص
              Expanded(
                child: Column(
                  crossAxisAlignment: CrossAxisAlignment.start,
                  children: [
                    Text(
                      'ready_for_fun'.tr(),
                      style: TextStyle(
                        color: Colors.black,
                        fontSize: 12,
                        fontFamily: 'Brando Arabic',
                        fontWeight: FontWeight.w700,
                        height: 1.50,
                      ),
                    ),
                    SizedBox(height: 4),

                    Text(
                      'experience_description'.tr(),
                      style: TextStyle(
                        color: Color(0xFF6A4690),
                        fontSize: 10,
                        fontFamily: 'Brando Arabic',
                        fontWeight: FontWeight.w400,
                        height: 1.40,
                      ),
                    ),
                  ],
                ),
              ),

              SizedBox(width: 12),

              /// زر الحجز
              GestureDetector(
                onTap: () {
                  if (ticketLink == null) return;
                  openLink(ticketLink);
                },
                child: Container(
                  padding: EdgeInsets.symmetric(
                    horizontal: 20.w,
                    vertical: 8.h,
                  ),
                  decoration: BoxDecoration(
                    color: AppColors.primary,
                    borderRadius: BorderRadius.circular(8.r),
                  ),
                  child: Row(
                    children: [
                      SvgPicture.asset(
                        AppAssets.ticketText,
                        width: 16.w,
                        height: 16.h,
                        color: Colors.white,
                      ),
                      WidthSpace(8.w),
                      Text(
                        'book_ticket'.tr(),
                        style: AppTextStyles.font16SemiBold.copyWith(
                          color: Colors.white,
                          fontSize: 14.sp,
                        ),
                      ),
                    ],
                  ),
                ),
              ),
            ],
          ),
        ),
      ),
    );
  }
}
