import 'package:easy_localization/easy_localization.dart';
import 'package:flutter/material.dart';
import 'package:flutter_screenutil/flutter_screenutil.dart';
import 'package:hawdaj/core/routing/route_utils.dart';
import 'package:hawdaj/core/routing/routes_keys.dart';
import 'package:hawdaj/features/home/data/model/application_model/application_model.dart';
import 'package:hawdaj/features/home/presentation/view/widgets/application_items.dart';
import 'package:hawdaj/features/home/presentation/view/widgets/head_title_section.dart';

class ApplicationsSection extends StatelessWidget {
  const ApplicationsSection({
    super.key,
    required this.applications,
    this.itemAspectRatio = 1.77,
  });

  final List<ApplicationModel> applications;
  final double itemAspectRatio;

  @override
  Widget build(BuildContext context) {
    return Column(
      crossAxisAlignment: CrossAxisAlignment.start,
      children: [
        HeadTitleSection(
          title: "apps".tr(),
          onTap: () {
            push(RoutesKeys.kTasneefAppsListView, context);
          },
        ),

        LayoutBuilder(
          builder: (context, constraints) {
            // حساب الارتفاع بناءً على عرض الشاشة
            final itemHeight = constraints.maxWidth * 0.44;

            return SizedBox(
              height: itemHeight,
              child: ListView.separated(
                scrollDirection: Axis.horizontal,
                padding: EdgeInsets.symmetric(horizontal: 16.w),
                physics: const BouncingScrollPhysics(),
                itemCount: applications.length,
                separatorBuilder: (_, __) => SizedBox(width: 12.w),
                itemBuilder: (context, index) {
                  return SizedBox(
                    width: itemHeight * itemAspectRatio,
                    child: ApplicationItems(application: applications[index]),
                  );
                },
              ),
            );
          },
        ),
      ],
    );
  }
}
