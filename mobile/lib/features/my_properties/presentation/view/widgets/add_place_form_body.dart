import 'package:easy_localization/easy_localization.dart';
import 'package:flutter/material.dart';
import 'package:flutter_bloc/flutter_bloc.dart';
import 'package:flutter_screenutil/flutter_screenutil.dart';
import 'package:flutter_svg/svg.dart';
import 'package:google_maps_flutter/google_maps_flutter.dart';
import 'package:hawdaj/core/components/bottom_sheet/base_bottom_sheet.dart';
import 'package:hawdaj/core/components/custom_date_picker.dart';
import 'package:hawdaj/core/components/custom_success_toast.dart';
import 'package:hawdaj/core/components/custom_text_field/custom_app_form_text_field.dart';
import 'package:hawdaj/core/components/multi_select_drop_down_field.dart';
import 'package:hawdaj/core/components/pop_up/pop_up_item.dart';
import 'package:hawdaj/core/components/primary_button.dart';
import 'package:hawdaj/core/components/spaces.dart';
import 'package:hawdaj/core/managers/cities_cubit/cities_cubit.dart';
import 'package:hawdaj/core/routing/route_utils.dart';
import 'package:hawdaj/core/routing/routes_keys.dart';
import 'package:hawdaj/core/styles/app_colors.dart';
import 'package:hawdaj/core/styles/app_text_styles.dart';
import 'package:hawdaj/core/utils/app_assets.dart';
import 'package:hawdaj/core/views/city_dropdown.dart';
import 'package:hawdaj/core/views/region_drop_down_field.dart';
import 'package:hawdaj/features/my_properties/presentation/manager/add_property_form_cubit/add_property_form_cubit.dart';
import 'package:hawdaj/features/my_properties/presentation/view/widgets/menu_files_selector.dart';

import 'package:hawdaj/features/my_properties/presentation/view/widgets/ownership_proof_selector.dart';
import 'package:hawdaj/features/my_properties/presentation/view/widgets/prices_selector_field.dart';
import 'package:hawdaj/features/my_properties/presentation/view/widgets/property_images_selector.dart';
import 'package:hawdaj/features/my_properties/presentation/view/widgets/property_type_field.dart';
import 'package:hawdaj/features/my_properties/presentation/view/widgets/season_selector_field.dart';
import 'package:hawdaj/features/my_properties/presentation/view/widgets/social_links_list.dart';
import 'package:hawdaj/features/tasneef/presentation/cubits/categories/categories_cubit.dart';
import 'package:hawdaj/features/tasneef/presentation/cubits/categories/categories_state.dart';
import 'package:hawdaj/features/tasneef/presentation/cubits/categories/sub_categories_cubit.dart';
import 'package:hawdaj/features/my_properties/presentation/view/widgets/connection_type_dropdown.dart';
import 'package:hawdaj/features/my_properties/presentation/view/widgets/address_type_dropdown.dart';
import 'package:hawdaj/features/tour%D9%80guide/presentation/view/widgets/drod_down-link.dart';

import 'package:hawdaj/features/tour%D9%80guide/presentation/view/widgets/section_field.dart';
import 'package:hawdaj/features/trip/presentation/view/widgets/trip_start_app_bar.dart';

class AddPlaceFormBody extends StatelessWidget {
  const AddPlaceFormBody({super.key});

  @override
  Widget build(BuildContext context) {
    final type = context.watch<AddPropertyFormCubit>().state.type;

    return Column(
      children: [
        SizedBox(
          height: 120.h,
          child: TripStartAppBar(title: 'add_new_place_title'.tr()),
        ),

        Expanded(
          child: ListView(
            padding: const EdgeInsets.all(16),
            children: [
              Text(
                'place_info'.tr(),
                style: AppTextStyles.font16Bold.copyWith(
                  color: AppColors.dark60,
                  height: 1.25,
                ),
              ),
              SizedBox(height: 12.h),
              Text(
                "property_type_label".tr(),
                style: AppTextStyles.font14Medium.copyWith(color: Colors.black),
              ),
              HeightSpace(2.h),

              GestureDetector(child: PropertyTypeDropdown()),
              HeightSpace(16.h),
              if (type != 'event')
                BlocBuilder<CategoriesCubit, CategoriesCState>(
                  builder: (context, state) {
                    if (state is CategoriesLoading) {
                      return const Center(child: CircularProgressIndicator());
                    }

                    if (state is CategoriesCSuccess) {
                      final cubit = context.watch<AddPropertyFormCubit>();
                      final selectedIds = cubit.state.selectedCategoryIds ?? [];

                      // ✅ نتأكد إن كل الكاتيجوري عنده id و name
                      final items = state.categories
                          .where((cat) => cat.id != null && cat.name != null)
                          .map(
                            (cat) =>
                                GlobalPopUpData(id: cat.id!, title: cat.name!),
                          )
                          .toList();

                      // ✅ نتأكد إن selectedIds كلها موجودة ضمن العناصر
                      final validSelectedIds = selectedIds
                          .where((id) => items.any((item) => item.id == id))
                          .toList();

                      return SectionField(
                        label: 'sections_label'.tr(),
                        child: MultiSelectDropDownField(
                          hint: 'sections_label'.tr(),
                          items: items,
                          selectedIds: validSelectedIds,
                          leadingIconPath: AppAssets.hashtag,
                          onChanged: cubit.updateSelectedCategories,
                        ),
                      );
                    }

                    return const SizedBox.shrink();
                  },
                ),

              SectionField(
                label: 'property_name_label'.tr(),
                child: CustomTextField(
                  hint: 'property_name_label'.tr(),
                  initialValue: context
                      .read<AddPropertyFormCubit>()
                      .state
                      .title,
                  leadingIconPath: AppAssets.text,
                  onChange: context.read<AddPropertyFormCubit>().updateTitle,
                ),
              ),

              // 🏪 Store-specific fields
              if (type == 'store') ...[
                HeightSpace(16.h),
                Text(
                  "store_type".tr(),
                  style: AppTextStyles.font14Regular.copyWith(
                    color: AppColors.black,
                  ),
                ),
                HeightSpace(2.h),
                const ConnectionTypeDropdown(),

                if (context.watch<AddPropertyFormCubit>().state.conType ==
                    'local') ...[
                  HeightSpace(16.h),
                  Text(
                    "select_address_type".tr(),
                    style: AppTextStyles.font14Regular.copyWith(
                      color: AppColors.black,
                    ),
                  ),
                  HeightSpace(2.h),
                  const AddressTypeDropdown(),

                  // Show fields based on address_type for local stores
                  HeightSpace(16.h),

                  // If address_type is 'link' - show link field
                  if (context.watch<AddPropertyFormCubit>().state.addressType ==
                      'link')
                    SectionField(
                      label: context.tr('store_link_label'),
                      child: CustomTextField(
                        hint: context.tr('enter_store_link'),
                        initialValue: context
                            .watch<AddPropertyFormCubit>()
                            .state
                            .address,
                        leadingIconPath: AppAssets.link,
                        onChange: context
                            .read<AddPropertyFormCubit>()
                            .updateAddress,
                      ),
                    ),

                  // If address_type is 'map' - show map address field
                  if (context.watch<AddPropertyFormCubit>().state.addressType ==
                      'map')
                    SectionField(
                      label: 'property_address_label'.tr(),
                      child: GestureDetector(
                        onTap: () async {
                          final result = await push<Map<String, dynamic>>(
                            RoutesKeys.kMapPickerView,
                            context,
                          );
                          if (result != null) {
                            final LatLng pos = result['position'];
                            final String? address = result['address'];
                            context.read<AddPropertyFormCubit>().updateLocation(
                              pos.latitude,
                              pos.longitude,
                            );
                            if (address != null) {
                              context
                                  .read<AddPropertyFormCubit>()
                                  .updateAddress(address);
                            }
                          }
                        },
                        child: CustomTextField(
                          enabled: false,
                          hint: 'property_address_label'.tr(),
                          initialValue: context
                              .watch<AddPropertyFormCubit>()
                              .state
                              .address,
                          trailing: SvgPicture.asset(
                            AppAssets.mapsIconText,
                            width: 80.w,
                            height: 24.h,
                          ),
                          leadingIconPath: AppAssets.locationIcon,
                          onChange: context
                              .read<AddPropertyFormCubit>()
                              .updateAddress,
                        ),
                      ),
                    ),
                ],

                // Show link field if connection type is 'online'
                if (context.watch<AddPropertyFormCubit>().state.conType ==
                    'online') ...[
                  HeightSpace(16.h),
                  SectionField(
                    label: context.tr('store_link_label'),
                    child: CustomTextField(
                      hint: context.tr('enter_store_link'),
                      initialValue: context
                          .watch<AddPropertyFormCubit>()
                          .state
                          .address,
                      leadingIconPath: AppAssets.link,
                      height: 48.h,
                      onChange: context
                          .read<AddPropertyFormCubit>()
                          .updateAddress,
                    ),
                  ),
                ],
              ],

              // 🗺️ Address field (hidden for stores - they have their own fields above)
              if (type != 'store')
                SectionField(
                  label: 'property_address_label'.tr(),
                  child: GestureDetector(
                    onTap: () async {
                      final result = await push<Map<String, dynamic>>(
                        RoutesKeys.kMapPickerView,
                        context,
                      );
                      if (result != null) {
                        final LatLng pos = result['position'];
                        final String? address = result['address'];
                        context.read<AddPropertyFormCubit>().updateLocation(
                          pos.latitude,
                          pos.longitude,
                        );
                        if (address != null) {
                          context.read<AddPropertyFormCubit>().updateAddress(
                            address,
                          );
                        }
                      }
                    },
                    child: CustomTextField(
                      enabled: false,
                      hint: 'property_address_label'.tr(),
                      initialValue: context
                          .watch<AddPropertyFormCubit>()
                          .state
                          .address,
                      trailing: GestureDetector(
                        // onTap: () async {
                        //   final result = await push<Map<String, dynamic>>(
                        //     RoutesKeys.kMapPickerView,
                        //     context,
                        //   );
                        //   if (result != null) {
                        //     final LatLng pos = result['position'];
                        //     final String? address = result['address'];
                        //     context.read<AddPropertyFormCubit>().updateLocation(
                        //       pos.latitude,
                        //       pos.longitude,
                        //     );
                        //     if (address != null) {
                        //       context
                        //           .read<AddPropertyFormCubit>()
                        //           .updateAddress(address);
                        //     }
                        //   }
                        // },
                        child: SvgPicture.asset(
                          AppAssets.mapsIconText,
                          width: 80.w,
                          height: 24.h,
                        ),
                      ),
                      leadingIconPath: AppAssets.locationIcon,
                      onChange: context
                          .read<AddPropertyFormCubit>()
                          .updateAddress,
                    ),
                  ),
                ),
              HeightSpace(16.h),
              //وصف الممتلك
              SectionField(
                label: "description_label".tr(),
                child: CustomTextField(
                  hint: 'enter_description_hint'.tr(),
                  initialValue: context
                      .read<AddPropertyFormCubit>()
                      .state
                      .description,
                  leadingIconPath: AppAssets.infoGrey,

                  svgColor: AppColors.grey,
                  maxLines: 5,
                  height: 100.h,
                  onChange: (v) {
                    context.read<AddPropertyFormCubit>().updateDescription(v);
                  },
                ),
              ),

              // 🗺️ Region field (hidden only for online stores)
              if (!(type == 'store' &&
                  context.watch<AddPropertyFormCubit>().state.conType ==
                      'online')) ...[
                Text(
                  'region_label'.tr(),
                  style: AppTextStyles.font14Regular.copyWith(
                    color: AppColors.black,
                  ),
                ),
                HeightSpace(2.h),
                RegionDropdown(
                  icon: AppAssets.mapSvg,
                  selectedRegionId: context
                      .watch<AddPropertyFormCubit>()
                      .state
                      .selectedRegionId,
                  onChanged: (regionId) {
                    final cubit = context.read<AddPropertyFormCubit>();
                    cubit.updateRegionId(regionId);
                    cubit.updateCityId(null);
                    if (regionId != null) {
                      context.read<CitiesCubit>().fetchCitiesByRegion(regionId);
                    } else {
                      context.read<CitiesCubit>().clear();
                    }
                  },
                  onClear: () {
                    final cubit = context.read<AddPropertyFormCubit>();
                    cubit.updateRegionId(null);
                    cubit.updateCityId(null);
                    context.read<CitiesCubit>().clear();
                  },
                  hint: 'choose_regions_hint'.tr(),
                ),
                HeightSpace(16.h),
              ],

              // 🗺️ City field (hidden only for online stores)
              if (!(type == 'store' &&
                  context.watch<AddPropertyFormCubit>().state.conType ==
                      'online')) ...[
                Text(
                  'choose_city'.tr(),
                  style: AppTextStyles.font14Regular.copyWith(
                    color: AppColors.black,
                  ),
                ),
                HeightSpace(2.h),
                CityDropdown(
                  icon: AppAssets.mapCity,
                  selectedCityId: context
                      .watch<AddPropertyFormCubit>()
                      .state
                      .selectedCityId,
                  onChanged: (value) {
                    context.read<AddPropertyFormCubit>().updateCityId(value);
                  },
                  onClear: () {
                    context.read<AddPropertyFormCubit>().updateCityId(null);
                  },
                  hint: 'choose_city'.tr(),
                ),
              ],

              if (type == 'zad') ...[
                HeightSpace(16.h),
                BlocBuilder<SubCategoriesCubit, CategoriesCState>(
                  builder: (context, state) {
                    if (state is SubCategoriesLoading) {
                      return const Center(child: CircularProgressIndicator());
                    }

                    if (state is SubCategoriesSuccess) {
                      final cubit = context.read<AddPropertyFormCubit>();

                      // ✅ القيم المختارة من الفورم
                      final selectedIds = cubit.state.foodCategories ?? [];

                      // ✅ تحويل التصنيفات إلى بيانات العرض
                      final items = state.subCategories
                          .map(
                            (cat) => GlobalPopUpData(
                              id: cat.id,
                              title: cat.name ?? '',
                            ),
                          )
                          .toList();

                      // ✅ فلترة القيم المختارة بحيث تكون كلها موجودة فعلاً في القائمة
                      final validSelectedIds = selectedIds
                          .where((id) => items.any((item) => item.id == id))
                          .toList();

                      return SectionField(
                        label: 'food_categories_label'.tr(),
                        child: MultiSelectDropDownField(
                          hint: 'choose_food_categories_hint'.tr(),

                          items: items,
                          selectedIds:
                              validSelectedIds, // ✅ استخدم القيم المفلترة
                          leadingIconPath: AppAssets.barcode,
                          onChanged: (v) {
                            cubit.updateFoodCategories(v);
                          },
                        ),
                      );
                    }

                    if (state is SubCategoriesError) {
                      return Center(
                        child: Text(
                          ' ${state.message}',
                          style: const TextStyle(color: Colors.red),
                        ),
                      );
                    }

                    return const SizedBox.shrink();
                  },
                ),
              ],

              //   رابط الفيديو
              if (type == 'event') ...[
                HeightSpace(16.h),
                //رابط الفيديو
                SectionField(
                  label: 'video_link_label'.tr(),
                  child: CustomTextField(
                    hint: 'video_link_label'.tr(),
                    leadingIconPath: AppAssets.videoVertical,
                    onChange: (v) {
                      context.read<AddPropertyFormCubit>().updateVideoUrl(v);
                    },
                  ),
                ),
                //رابط التذكرة
                SectionField(
                  label: 'ticket_link_label'.tr(),
                  child: CustomTextField(
                    hint: 'ticket_link_label'.tr(),
                    leadingIconPath: AppAssets.ticketText,
                    onChange: (v) {
                      context.read<AddPropertyFormCubit>().updateTicketLink(v);
                    },
                  ),
                ),

                //تاريخ البداية
                SectionField(
                  label: 'start_date_label'.tr(),
                  child: CustomDatePicker(
                    hint: 'choose_start_date'.tr(),

                    formatPattern: 'yyyy-MM-dd',
                    firstDate: DateTime.now(),
                    onDatePicked: context
                        .read<AddPropertyFormCubit>()
                        .updateDateFrom,
                  ),
                ),
                SectionField(
                  label: 'end_date_label'.tr(),
                  child: CustomDatePicker(
                    hint: "choose_end_date".tr(),

                    formatPattern: 'yyyy-MM-dd',
                    onDatePicked: context
                        .read<AddPropertyFormCubit>()
                        .updateDateTo,
                  ),
                ),
              ],

              if (type == 'place') ...[
                HeightSpace(16.h),
                SeasonSelectorFieldNew(),
              ],
              if (type == 'place') const PricesSelectorField(),

              const SizedBox(height: 16),
              const PropertyImagesSelector(),
              if (type == 'zad') MenuFilesSelector(),
              const SizedBox(height: 16),
              const OwnershipProofSelector(),

              const SizedBox(height: 16),
              Row(
                mainAxisAlignment: MainAxisAlignment.spaceBetween,
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  Expanded(
                    child: Text(
                      'add_social_link_title'.tr(),
                      style: AppTextStyles.font16Bold,
                    ),
                  ),
                  Flexible(
                    child: PrimaryButton(
                      width: 100.w,
                      height: 32.h,
                      padding: EdgeInsets.zero,
                      backgroundColor: Color(0xFFF2EBF6),
                      iconPath: AppAssets.add,
                      textColor: AppColors.primary,

                      title: 'add_button'.tr(),
                      onTap: () async {
                        final result = await baseBottomSheet(
                          title: 'add_social_link_title'.tr(),
                          context: context,
                          child: SocialMediaDropdown(
                            availablePlatforms: context
                                .read<AddPropertyFormCubit>()
                                .availablePlatforms,
                            onSave: (platform, link) {
                              context
                                  .read<AddPropertyFormCubit>()
                                  .addSocialLink(platform, link);
                            },
                          ),
                          hideNavBar: true,
                        );
                        if (context.mounted && result == true) {
                          showCustomSuccessToast(
                            'social_link_added_success'.tr(),
                          );
                        }
                      },
                    ),
                  ),
                ],
              ),

              SocialLinksList(),
              const SizedBox(height: 16),
            ],
          ),
        ),
      ],
    );
  }
}
