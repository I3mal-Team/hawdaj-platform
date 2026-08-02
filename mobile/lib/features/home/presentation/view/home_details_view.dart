import 'package:easy_localization/easy_localization.dart';
import 'package:flutter/material.dart';
import 'package:flutter_rating_bar/flutter_rating_bar.dart';
import 'package:flutter_screenutil/flutter_screenutil.dart';
import 'package:flutter_svg/svg.dart';
import 'package:hawdaj/core/components/bottom_sheet/base_bottom_sheet.dart';
import 'package:hawdaj/core/components/custom_text_field/custom_app_form_text_field.dart';
import 'package:hawdaj/core/components/primary_button.dart';
import 'package:hawdaj/core/components/spaces.dart';
import 'package:hawdaj/core/styles/app_colors.dart';
import 'package:hawdaj/core/styles/app_text_styles.dart';
import 'package:hawdaj/core/utils/app_assets.dart';
import 'package:hawdaj/features/home/presentation/view/widgets/custom_app_bar_home_details.dart';
import 'package:hawdaj/features/home/presentation/view/widgets/details_section.dart';
import 'package:hawdaj/features/home/presentation/view/widgets/howdah_guides_section_items.dart';
import 'package:hawdaj/features/home/presentation/view/widgets/list_of_supplies_section.dart';
import 'package:hawdaj/features/home/presentation/view/widgets/offer_date_range.dart';
import 'package:hawdaj/features/home/presentation/view/widgets/offer_section.dart';
import 'package:hawdaj/features/home/presentation/view/widgets/sliver_section_header.dart';
import 'dart:ui' as ui;

class HomePlacesDetailsView extends StatelessWidget {
  const HomePlacesDetailsView({super.key});

  @override
  Widget build(BuildContext context) {
    final isRtl = Directionality.of(context) == ui.TextDirection.rtl;
    return Scaffold(
      body: Padding(
        padding: EdgeInsets.symmetric(horizontal: 8.w, vertical: 8.h),
        child: CustomScrollView(
          physics: const AlwaysScrollableScrollPhysics(),
          slivers: [
            CustomAppBarHomeDetails(
              showSaveButton: true,
              isFavorite: false,
              isSaved: false,
            ),
            SliverToBoxAdapter(child: HeightSpace(16.h)),
            DetailsSection(
              title: '',
              rating: '4.9',
              address: 'المملكة العربية, مكة المكرمة, جدة',
              description: 'قصر سلوى قصر أثري تاريخي يقع شمال الدرعية...الخ',
            ),
            SliverToBoxAdapter(child: HeightSpace(16.h)),
            InfoHeader(
              title: "الخصائص",
              subtitle: "تاريخ الانشاء: 25  يناير 2020",
              image: AppAssets.calendarSvg,
            ),
            //  PropertiesGrid(),
            SliverToBoxAdapter(child: HeightSpace(16.h)),

            InfoHeader(
              title: "location".tr(),
              subtitle: "location_subtitle".tr(),
              image: AppAssets.info,
            ),

            SliverToBoxAdapter(
              child: Container(
                width: double.infinity,
                height: 193.h,
                clipBehavior: Clip.antiAlias,
                decoration: ShapeDecoration(
                  image: DecorationImage(
                    image: AssetImage(
                      AppAssets.map, // Use the map image asset
                    ),
                    fit: BoxFit.fill,
                  ),
                  shape: RoundedRectangleBorder(
                    side: BorderSide(
                      width: 1,
                      color: const Color(0xFFEEF2F6) /* Color-Neutrals-100 */,
                    ),
                    borderRadius: BorderRadius.circular(8),
                  ),
                ),
              ),
            ),

            SliverToBoxAdapter(child: HeightSpace(16.h)),
            InfoHeader(
              title: "reviews".tr(),
              image: AppAssets.info,
              child: GestureDetector(
                onTap: () {},
                child: Row(
                  children: [
                    Text(
                      "see_all".tr(),
                      style: AppTextStyles.font14Regular.copyWith(
                        color: AppColors.grey,
                      ),
                    ),
                    HeightSpace(4.h),
                    Transform(
                      alignment: Alignment.center,
                      transform: Matrix4.rotationY(isRtl ? 0 : 3.1416),
                      child: SvgPicture.asset(AppAssets.arrowLeft),
                    ),
                  ],
                ),
              ),
            ),
            SliverToBoxAdapter(child: HeightSpace(16.h)),

            //ListOfSuppliesSection
            InfoHeader(
              title: "قائمة الزاد",
              subtitle: "عرض لجميع المنتجات والاصناف ",
              image: AppAssets.documentText,
            ),
            SliverToBoxAdapter(child: HeightSpace(16.h)),
            SliverToBoxAdapter(child: ListOfSuppliesSection()),
            SliverToBoxAdapter(child: HeightSpace(16.h)),
            //OffersAvailableItems
            InfoHeader(
              title: "العروض المتاحة",
              subtitle: "استمتع بالعروض المتاحة لدينا",
              image: AppAssets.ticket,
            ),
            SliverToBoxAdapter(child: HeightSpace(16.h)),

            // OfferSection(
            //   destLat:  ,
            //   destLong: 0.0,
            // ),
            SliverToBoxAdapter(child: HeightSpace(16.h)),
            SliverToBoxAdapter(
              child: SizedBox(
                height: 110.h,
                child: ListView.separated(
                  scrollDirection: Axis.horizontal,
                  //  padding: const EdgeInsets.symmetric(horizontal: 16),
                  itemCount: 4,
                  separatorBuilder: (_, __) => SizedBox(width: 12.w),
                  itemBuilder: (context, index) {
                    return SizedBox(
                      width: 283.w,
                      child: HowdahGuidesSectionItems(
                        title: "اسم المرشد",
                        description: "وصف المرشد",
                        imageUrl: AppAssets.user,
                        type: "guide",
                        parentId: "0",
                        rating: "0.0",
                        onTap: () {},
                      ),
                    );
                  },
                ),
              ),
            ),
            SliverToBoxAdapter(child: HeightSpace(16.h)),
            SliverToBoxAdapter(
              child: Container(
                width: double.infinity,
                padding: const EdgeInsets.symmetric(
                  horizontal: 8,
                  vertical: 12,
                ),
                decoration: ShapeDecoration(
                  color: const Color(0xFFFCFCFD) /* Color-Neutrals-25 */,
                  shape: RoundedRectangleBorder(
                    side: BorderSide(
                      width: 1,
                      color: const Color(0xFFF8FAFC) /* Color-Neutrals-50 */,
                    ),
                    borderRadius: BorderRadius.circular(8),
                  ),
                ),
                child: OfferDateRange(
                  startDate: "02/06/2025",
                  endDate: "02/06/2025",
                  iconColorOne: AppColors.successGreen,
                  iconColorTwo: AppColors.redcolor,
                ),
              ),
            ),
            SliverToBoxAdapter(child: HeightSpace(16.h)),
            SliverToBoxAdapter(
              child: Align(
                alignment: Alignment.center,
                child: PrimaryButton(
                  title: "share_button".tr(),
                  onTap: () {
                    baseBottomSheet(
                      context: context,
                      title: '',
                      hideNavBar: true,
                      child: Column(
                        mainAxisSize: MainAxisSize.min,
                        children: [
                          HeightSpace(16.h),
                          Image.asset(
                            AppAssets.imagOk,
                            width: 80.w,
                            height: 80.h,
                          ),
                          HeightSpace(20.h),
                          Padding(
                            padding: EdgeInsets.symmetric(horizontal: 16.w),
                            child: Text(
                              "اترك تقييمًا يعبر عن مدي رضاءك",
                              style: AppTextStyles.font16Medium.copyWith(
                                color: AppColors.obsidianBlack,
                              ),
                            ),
                          ),
                          HeightSpace(12.h),
                          Text(
                            'نحن نحب أن نعرف! تقييمك للخدمة.',
                            textAlign: TextAlign.center,
                            style: AppTextStyles.font14Regular.copyWith(
                              color: AppColors.grey,
                            ),
                          ),
                          HeightSpace(30.h),
                          RatingBar.builder(
                            initialRating: 3,
                            minRating: 1,
                            allowHalfRating: false,
                            itemCount: 5,
                            itemPadding: const EdgeInsets.symmetric(
                              horizontal: 6,
                            ),

                            itemBuilder: (context, _) =>
                                SvgPicture.asset(AppAssets.star),
                            onRatingUpdate: (value) {},
                          ),
                          HeightSpace(20.h),
                          CustomTextField(
                            hint: "اترك رأيك إذا أردت ...",
                            maxLines: 6,
                          ),
                          HeightSpace(20.h),
                          Row(
                            children: [
                              Expanded(
                                child: PrimaryButton(
                                  title: 'المشاركة',
                                  onTap: () {
                                    Navigator.pop(context);
                                  },
                                  active: true,
                                ),
                              ),
                              WidthSpace(16.w),
                              Expanded(
                                child: PrimaryButton(
                                  title: 'إلغاء',
                                  backgroundColor: Color(0xFFF5F7F8),
                                  textColor: AppColors.obsidianBlack,
                                  onTap: () {
                                    Navigator.pop(context);
                                  },
                                  active: true,
                                ),
                              ),
                            ],
                          ),
                        ],
                      ),
                    );
                  },
                  iconPath: AppAssets.svgMessage,
                  width: 200.w,
                ),
              ),
            ),
          ],
        ),
      ),
    );
  }
}
