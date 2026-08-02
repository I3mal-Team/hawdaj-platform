import 'package:easy_localization/easy_localization.dart';
import 'package:flutter/material.dart';
import 'package:flutter_bloc/flutter_bloc.dart';
import 'package:flutter_screenutil/flutter_screenutil.dart';
import 'package:flutter_svg/svg.dart';
import 'package:go_router/go_router.dart';
import 'package:hawdaj/core/components/bottom_sheet/base_bottom_sheet.dart';
import 'package:hawdaj/core/components/custom_failure_toast.dart';
import 'package:hawdaj/core/components/custom_success_toast.dart';

import 'package:hawdaj/core/components/spaces.dart';
import 'package:hawdaj/core/routing/route_utils.dart';
import 'package:hawdaj/core/routing/routes_keys.dart';

import 'package:hawdaj/core/utils/app_assets.dart';
import 'package:hawdaj/features/auth/presentation/manager/cubits/delete_profile_cubit/delete_profile_cubit.dart';
import 'package:hawdaj/features/auth/presentation/manager/cubits/logout_cubit/logout_cubit.dart';
import 'package:hawdaj/features/home/presentation/view/widgets/app_bar_gradient_background.dart';
import 'package:hawdaj/features/landmarks/presentation/view/widgets/landmark_type_bottom_sheet.dart';
import 'package:hawdaj/features/profile/presentation/manager/get_profile_cubit/get_profile_cubit.dart';
import 'package:hawdaj/features/profile/presentation/views/widgets/language_bottom_sheet.dart';
import 'package:hawdaj/features/profile/presentation/views/widgets/rate_app_bottom_sheet.dart';
import 'package:hawdaj/features/profile/presentation/views/widgets/user_profile_header.dart';
import 'package:hawdaj/features/profile/presentation/views/widgets/points_card.dart';
import 'package:hawdaj/features/profile/presentation/views/widgets/profile_page_item_group.dart';
import 'package:hawdaj/features/profile/presentation/views/widgets/logout_button.dart';
import 'package:hawdaj/features/trip/presentation/view/widgets/bottom_sheet_trip_dealt.dart';

class ProfileView extends StatelessWidget {
  const ProfileView({super.key});

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      backgroundColor: Colors.white,
      body: CustomScrollView(
        slivers: [
          SliverToBoxAdapter(
            child: SizedBox(
              height: 130.h,
              child: Stack(
                children: [
                  const AppBarGradientBackground(),
                  Center(
                    child: SvgPicture.asset(
                      AppAssets.howdaj,
                      height: 36.h,
                      fit: BoxFit.contain,
                    ),
                  ),
                ],
              ),
            ),
          ),

          SliverToBoxAdapter(
            child: Padding(
              padding: EdgeInsets.symmetric(horizontal: 16.w, vertical: 8.h),
              child: Column(
                children: [
                  BlocBuilder<GetProfileCubit, GetProfileState>(
                    builder: (context, state) {
                      if (state is GetProfileLoading) {
                        return const Center(child: UserProfileHeaderShimmer());
                      }

                      if (state is GetProfileError) {
                        return Column(
                          children: [
                            Text(state.errMessage, textAlign: TextAlign.center),
                            ElevatedButton(
                              onPressed: () =>
                                  context.read<GetProfileCubit>().getProfile(),
                              child: Text("retry".tr()),
                            ),
                          ],
                        );
                      }

                      if (state is GetProfileSuccess) {
                        final personal = state.userModel.data.personalData;
                        final userName = (personal.fullName ?? '').isNotEmpty
                            ? personal.fullName
                            : '${personal.firstName} ${personal.lastName}'
                                  .trim();

                        return UserProfileHeader(
                          user: state.userModel,
                          userEmail: personal.email ?? "",
                          userName: userName ?? "",
                          onPointsTap: () async {
                            // final result = await context.push(RoutesKeys.kPointsView);
                            // if (result == true && context.mounted) {
                            //   context.read<GetProfileCubit>().getProfile();
                            // }
                          },
                          onTripsTap: () async {
                            final result = await context.push(
                              RoutesKeys.kNewMyTripView,
                            );
                            if (result == true && context.mounted) {
                              context.read<GetProfileCubit>().getProfile();
                            }
                          },
                          onStoriesTap: () async {
                            final result = await context.push(
                              RoutesKeys.kUserStoriesView,
                            );
                            if (result == true && context.mounted) {
                              context.read<GetProfileCubit>().getProfile();
                            }
                          },
                          onEditTap: () async {
                            // استخدام البيانات المحدثة من state مباشرة
                            final currentState = context
                                .read<GetProfileCubit>()
                                .state;
                            if (currentState is GetProfileSuccess) {
                              final result = await context.push(
                                RoutesKeys.kEditProfileView,
                                extra: currentState
                                    .userModel, // استخدام البيانات الأحدث
                              );

                              if (result == true && context.mounted) {
                                context.read<GetProfileCubit>().getProfile();
                              }
                            }
                          },
                        );
                      }

                      return const SizedBox.shrink();
                    },
                  ),

                  HeightSpace(16.h),

                  /// نقاط هودج
                  PointsCard(
                    onButtonTap: () {
                      //     push(RoutesKeys.kLandMarkView, context);
                      showLandmarkTypeBottomSheet(context: context).then((
                        value,
                      ) {
                        if (value != null) {
                          GoRouter.of(
                            context,
                          ).push(RoutesKeys.kAddLandmarkView, extra: value);
                        }
                      });
                    },
                  ),

                  HeightSpace(16.h),

                  /// الإعدادات
                  ProfilePageItemGroup(
                    subtitle: 'settings'.tr(),
                    items: [
                      SettingItemData(
                        iconPath: AppAssets.tagUser,
                        title: "tour_guide_profile".tr(),
                        onTap: () async {
                          final state = context.read<GetProfileCubit>().state;
                          print("🔍 ProfileView onTap State: $state");
                          if (state is GetProfileSuccess) {
                            final guide = state.userModel.data.userGuideData;
                            print("🔍 ProfileView Guide Data: $guide");

                            final isAdding = guide == null;
                            final result = await push(
                              isAdding
                                  ? RoutesKeys.kAddTourGuideDetailsView
                                  : RoutesKeys.kTourGuideView,
                              context,
                              extra: state.userModel,
                            );

                            // تحديث فقط إذا رجعت الصفحة بنتيجة true (أي تم الحفظ)
                            if (result == true && context.mounted) {
                              print("✅ Profile updated, refreshing data...");
                              await context
                                  .read<GetProfileCubit>()
                                  .getProfile();

                              // الحصول على الـ state المحدث بعد getProfile()
                              if (context.mounted) {
                                final updatedState = context
                                    .read<GetProfileCubit>()
                                    .state;
                                print("🔄 Updated State: $updatedState");

                                // التحقق من البيانات المحدثة
                                if (updatedState is GetProfileSuccess) {
                                  final updatedGuide =
                                      updatedState.userModel.data.userGuideData;
                                  print("🔄 Updated Guide Data: $updatedGuide");

                                  // إذا كنا نضيف مرشداً جديداً وتم الحفظ بنجاح، ننتقل لصفحة التفاصيل
                                  if (isAdding && updatedGuide != null) {
                                    print(
                                      "✅ Guide added successfully, navigating to details...",
                                    );
                                    push(
                                      RoutesKeys.kTourGuideView,
                                      context,
                                      extra: updatedState.userModel,
                                    );
                                  }
                                }
                              }
                            }
                          }
                        },
                      ),
                      SettingItemData(
                        iconPath: AppAssets.buildings2,
                        title: "my_properties".tr(),
                        onTap: () {
                          push(RoutesKeys.kMyPropertiesView, context);
                        },
                      ),
                      SettingItemData(
                        iconPath: AppAssets.mapIcon,
                        title: 'my_trips'.tr(),
                        onTap: () async {
                          final result = await push(
                            RoutesKeys.kNewMyTripView,
                            context,
                          );
                          if (result == true && context.mounted) {
                            context.read<GetProfileCubit>().getProfile();
                          }
                        },
                      ),
                      SettingItemData(
                        iconPath: AppAssets.story,
                        title: "stories".tr(),
                        onTap: () async {
                          final result = await push(
                            RoutesKeys.kUserStoriesView,
                            context,
                          );
                          if (result == true && context.mounted) {
                            context.read<GetProfileCubit>().getProfile();
                          }
                        },
                      ),
                      SettingItemData(
                        iconPath: AppAssets.heart,
                        title: "Favorites".tr(),
                        onTap: () {
                          push(RoutesKeys.kFavoriteView, context);
                        },
                      ),
                      SettingItemData(
                        iconPath: AppAssets.saveIcon,
                        title: "Saved_Items".tr(),
                        onTap: () {
                          push(RoutesKeys.kSavedView, context);
                        },
                      ),
                      SettingItemData(
                        iconPath: AppAssets.key,
                        title: "change_password".tr(),
                        onTap: () {
                          push(RoutesKeys.kUpdatePasswordView, context);
                        },
                      ),
                      SettingItemData(
                        iconPath: AppAssets.global,
                        title: "change_language".tr(),
                        onTap: () {
                          baseBottomSheet(
                            context: context,
                            title: 'change_language'.tr(),
                            child: const LanguageBottomSheet(),
                            hideNavBar: true,
                          );
                        },
                      ),

                      //kMyPropertiesView
                    ],
                  ),

                  /// معلومات عنا
                  ProfilePageItemGroup(
                    subtitle: "about_us".tr(),
                    items: [
                      SettingItemData(
                        iconPath: AppAssets.call,
                        title: "contact_us".tr(),
                        onTap: () {
                          push(RoutesKeys.kContactUsView, context);
                        },
                      ),
                      SettingItemData(
                        iconPath: AppAssets.securitySafe,
                        title: "terms_and_conditions".tr(),
                        onTap: () {
                          push(RoutesKeys.kTermsAndConditionsView, context);
                        },
                      ),
                    ],
                  ),

                  /// أخرى
                  ProfilePageItemGroup(
                    show: false,
                    subtitle: "others".tr(),
                    items: [
                      SettingItemData(
                        iconPath: AppAssets.likeDislike,
                        title: "rate_app".tr(),
                        onTap: () {
                          baseBottomSheet(
                            context: context,
                            title: 'rate_app'.tr(),
                            child: const RateAppBottomSheet(),
                            hideNavBar: true,
                          );
                        },
                      ),
                      SettingItemData(
                        iconPath: AppAssets.directboxSend,
                        title: "share_app".tr(),
                        onTap: () {},
                      ),
                    ],
                  ),

                  HeightSpace(16.h),

                  /// تسجيل الخروج
                  LogoutButton(
                    onTap: () {
                      baseBottomSheet(
                        context: context,
                        title: "logout".tr(),
                        hideNavBar: false,
                        child: BlocConsumer<LogoutCubit, LogoutState>(
                          listener: (context, state) {
                            if (state is LogoutSuccess) {
                              Navigator.of(context).pop();
                              showCustomSuccessToast(state.message);
                              context.go(RoutesKeys.kLoginView);
                            }
                            if (state is LogoutError) {
                              showCustomFailureToast(state.message);
                            }
                          },
                          builder: (context, state) {
                            return BottomSheetTripDealt(
                              cancel: 'cancel'.tr(),
                              confirm: state is LogoutLoading
                                  ? "logout_loading".tr()
                                  : 'confirm'.tr(),
                              title: "logout_title".tr(),
                              message: "logout_message".tr(),
                              onTapConfirm: state is LogoutLoading
                                  ? () {}
                                  : () {
                                      context.read<LogoutCubit>().logout();
                                    },
                              onTapCancel: () {
                                Navigator.of(context).pop();
                              },
                            );
                          },
                        ),
                      );
                    },
                  ),
                  HeightSpace(10.h),
                  // حذف الحساب
                  LogoutButton(
                    text: "delete_account".tr(),

                    onTap: () {
                      baseBottomSheet(
                        context: context,
                        title: "delete_account".tr(),
                        hideNavBar: false,
                        child:
                            BlocConsumer<
                              DeleteProfileCubit,
                              DeleteProfileState
                            >(
                              listener: (context, state) {
                                if (state is DeleteProfileSuccess) {
                                  Navigator.of(context).pop();
                                  showCustomSuccessToast(state.message);
                                  context.go(RoutesKeys.kLoginView);
                                }
                                if (state is DeleteProfileError) {
                                  showCustomFailureToast(state.message);
                                }
                              },
                              builder: (context, state) {
                                return BottomSheetTripDealt(
                                  image: AppAssets.trash,
                                  cancel: 'cancel'.tr(),
                                  confirm: state is DeleteProfileLoading
                                      ? "delete_loading".tr()
                                      : 'confirm'.tr(),
                                  title: "delete_account_title".tr(),
                                  message: "delete_account_message".tr(),
                                  onTapConfirm: state is LogoutLoading
                                      ? () {}
                                      : () {
                                          context
                                              .read<DeleteProfileCubit>()
                                              .delete();
                                        },
                                  onTapCancel: () {
                                    Navigator.of(context).pop();
                                  },
                                );
                              },
                            ),
                      );
                    },
                  ),
                ],
              ),
            ),
          ),
        ],
      ),
    );
  }
}
