import 'package:easy_localization/easy_localization.dart';
import 'package:flutter/material.dart';
import 'package:flutter_screenutil/flutter_screenutil.dart';
import 'package:hawdaj/core/components/spaces.dart';
import 'package:hawdaj/core/styles/app_colors.dart';
import 'package:hawdaj/core/styles/app_text_styles.dart';
import 'package:hawdaj/features/places/presentation/view/widgets/places_details_items_body.dart';
import 'package:hawdaj/features/trip/data/model/enhanced_trip_response.dart';

class DayHeaderCard extends StatelessWidget {
  const DayHeaderCard({super.key, required this.model});
  final EnhancedDay model;

  @override
  Widget build(BuildContext context) {
    final cityName = model.city?.name?.isNotEmpty == true
        ? model.city!.name!
        : 'city_not_specified'.tr();
    final cityDesc = model.city?.description?.isNotEmpty == true
        ? model.city!.description!
        : "no_city_description".tr();

    return Container(
      width: double.infinity,
      padding: const EdgeInsets.symmetric(vertical: 8, horizontal: 8),
      decoration: ShapeDecoration(
        color: const Color(0xFFF8FBFF),
        shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(8)),
      ),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          // Header Row (day + date + count)
          Row(
            children: [
              Container(
                width: 24,
                height: 24,
                padding: const EdgeInsets.symmetric(horizontal: 8, vertical: 3),
                decoration: ShapeDecoration(
                  color: const Color(0xFFD5C2E4),
                  shape: RoundedRectangleBorder(
                    borderRadius: BorderRadius.circular(4),
                  ),
                ),
                child: Center(
                  child: Text(
                    '${model.dayNumber}',
                    style: AppTextStyles.font16Bold.copyWith(
                      color: AppColors.black,
                      height: 1.20,
                    ),
                  ),
                ),
              ),
              WidthSpace(4.h),
              Expanded(
                child: Text(
                  '${"your_day".tr()} ${localizedDayOrder(context, model.dayNumber)}',
                  //  textAlign: TextAlign.right,
                  maxLines: 1,
                  overflow: TextOverflow.ellipsis,
                  style: AppTextStyles.font14Bold.copyWith(
                    color: const Color(0xFF232323),
                    height: 1.20,
                  ),
                ),
              ),
              Text(
                '- ${"day_label".tr()} ${formatArabicDate(model.date, context: context)} -',
                style: AppTextStyles.font12Medium.copyWith(
                  color: const Color(0xFF3A4980),
                ),
              ),
              WidthSpace(4.w),
              Text(
                '${"places_count".tr()} ${model.totalPlaces} -',
                style: AppTextStyles.font12Medium.copyWith(
                  color: AppColors.black,
                ),
              ),
            ],
          ),

          HeightSpace(8.h),

          // City info
          Text(
            cityName,
            style: AppTextStyles.font14Regular.copyWith(
              color: AppColors.black,
              height: 1.4,
            ),
          ),
          HeightSpace(4.h),
          SizedBox(
            width: double.infinity,
            child: Text(
              cityDesc,
              // textAlign: TextAlign.right,
              style: AppTextStyles.font14Medium.copyWith(
                color: const Color(0xFF717680),
                height: 1.43,
              ),
            ),
          ),
        ],
      ),
    );
  }
}

// ====== اليوم الترتيبي حسب اللغة ======
String localizedDayOrder(BuildContext context, int number) {
  final lang = context.locale.languageCode;

  if (lang == 'ar') {
    switch (number) {
      case 1:
        return 'ordinal_first'.tr();
      case 2:
        return 'ordinal_second'.tr();
      case 3:
        return 'ordinal_third'.tr();
      case 4:
        return 'ordinal_fourth'.tr();
      case 5:
        return 'ordinal_fifth'.tr();
      case 6:
        return 'ordinal_sixth'.tr();
      case 7:
        return 'ordinal_seventh'.tr();
      case 8:
        return 'ordinal_eighth'.tr();
      case 9:
        return 'ordinal_ninth'.tr();
      case 10:
        return 'ordinal_tenth'.tr();
      case 11:
        return 'ordinal_eleventh'.tr();
      case 12:
        return 'ordinal_twelfth'.tr();
      case 20:
        return 'ordinal_twentieth'.tr();
      case 30:
        return 'ordinal_thirtieth'.tr();
      default:
        return '$number';
    }
  } else {
    if (number >= 11 && number <= 13) return '${number}th';
    switch (number % 10) {
      case 1:
        return '${number}st';
      case 2:
        return '${number}nd';
      case 3:
        return '${number}rd';
      default:
        return '${number}th';
    }
  }
}

// ====== تحويل رقم اليوم إلى صيغة عربية ======
String _arabicDayOrder(int number) {
  final units = [
    '',
    'الأول',
    'الثاني',
    'الثالث',
    'الرابع',
    'الخامس',
    'السادس',
    'السابع',
    'الثامن',
    'التاسع',
    'العاشر',
  ];

  final teens = [
    '',
    'الحادي عشر',
    'الثاني عشر',
    'الثالث عشر',
    'الرابع عشر',
    'الخامس عشر',
    'السادس عشر',
    'السابع عشر',
    'الثامن عشر',
    'التاسع عشر',
  ];

  final tens = [
    '',
    '',
    'العشرون',
    'الثلاثون',
    'الأربعون',
    'الخمسون',
    'الستون',
    'السبعون',
    'الثمانون',
    'التسعون',
  ];

  if (number <= 10) return units[number];
  if (number <= 19) return teens[number - 10];

  final ten = number ~/ 10;
  final unit = number % 10;

  final tenPart = tens[ten];
  final unitPart = units[unit];

  if (unit == 0) {
    return tenPart;
  } else {
    return '$unitPart و$tenPart';
  }
}
