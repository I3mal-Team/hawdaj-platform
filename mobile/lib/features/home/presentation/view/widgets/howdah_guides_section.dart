import 'package:easy_localization/easy_localization.dart';
import 'package:flutter/material.dart';
import 'package:flutter_screenutil/flutter_screenutil.dart';
import 'package:hawdaj/core/components/spaces.dart';
import 'package:hawdaj/core/routing/route_utils.dart';
import 'package:hawdaj/core/routing/routes_keys.dart';
import 'package:hawdaj/features/home/data/model/guide_model/guide_response.dart';
import 'package:hawdaj/features/home/presentation/view/widgets/head_title_section.dart';
import 'package:hawdaj/features/home/presentation/view/widgets/howdah_guides_section_items.dart';

class HowdahGuidesSection extends StatelessWidget {
  const HowdahGuidesSection({super.key, required this.guideResponse});
  final GuideResponse guideResponse;

  @override
  Widget build(BuildContext context) {
    final guides = guideResponse.items ?? [];

    if (guides.isEmpty) {
      return const SizedBox(); // أو Placeholder نصي مثلاً
      // return Padding(
      //   padding: EdgeInsets.symmetric(horizontal: 16.w, vertical: 8.h),
      //   child: Text(
      //     "لا توجد أدلة حالياً".tr(),
      //     style: TextStyle(fontSize: 14.sp, color: Colors.grey),
      //   ),
      // );
    }

    return Column(
      crossAxisAlignment: CrossAxisAlignment.start,
      children: [
        HeadTitleSection(
          title: "howdaj_guides".tr(),
          onTap: () {
            push(RoutesKeys.kTasneefTourGuidesListView, context);
          },
        ),
        HeightSpace(12.h),
        SizedBox(
          height: 110.h,
          child: ListView.separated(
            scrollDirection: Axis.horizontal,
            padding: const EdgeInsets.symmetric(horizontal: 16),
            itemCount: guides.length,
            separatorBuilder: (_, __) => SizedBox(width: 12.w),
            itemBuilder: (context, index) {
              final guide = guides[index];
              return SizedBox(
                width: 283.w,
                child: HowdahGuidesSectionItems(
                  title: guide.name,
                  description: guide.description,
                  imageUrl: guide.image,
                  rating: guide.rate.toString(),
                  type: guide.type,
                  parentId: guide.id.toString(),
                  showBorder: true,
                  onTap: () {
                    push(
                      RoutesKeys.kTourGuideDetailsView,
                      context,
                      extra: guide.id,
                    );
                  },
                ),
              );
            },
          ),
        ),
      ],
    );
  }
}
