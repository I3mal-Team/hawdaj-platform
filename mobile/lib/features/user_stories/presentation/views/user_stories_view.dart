import 'package:easy_localization/easy_localization.dart';
import 'package:flutter/material.dart';
import 'package:flutter_bloc/flutter_bloc.dart';
import 'package:flutter_screenutil/flutter_screenutil.dart';
import 'package:hawdaj/core/components/text_components/generic_text.dart';
import 'package:hawdaj/core/routing/route_utils.dart';
import 'package:hawdaj/core/styles/app_colors.dart';

import 'package:hawdaj/core/utils/auth_manager.dart';

import 'package:hawdaj/features/trip/presentation/view/widgets/trip_start_app_bar.dart'
    show TripStartAppBar;
import 'package:hawdaj/features/user_stories/presentation/cubit/user_stories_cubit.dart';
import 'package:hawdaj/features/user_stories/presentation/cubit/user_stories_state.dart';
import 'package:hawdaj/features/user_stories/presentation/widgets/user_story_item.dart';
import 'package:hawdaj/features/user_stories/presentation/widgets/add_story_bottom_sheet.dart';
import 'package:hawdaj/features/user_stories/presentation/cubit/add_story_cubit.dart';
import 'package:hawdaj/core/services/service_locator.dart';
import 'package:hawdaj/features/user_stories/data/repositories/user_stories_repository.dart';

class UserStoriesView extends StatefulWidget {
  const UserStoriesView({super.key});

  @override
  State<UserStoriesView> createState() => _UserStoriesViewState();
}

class _UserStoriesViewState extends State<UserStoriesView> {
  int _selectedTabIndex = 1; // Default to "منشوراتي" (My Posts)

  @override
  void initState() {
    super.initState();
    _checkAuthAndFetchStories();
  }

  Future<void> _checkAuthAndFetchStories() async {
    final isLoggedIn = await AuthManager.isLoggedIn();
    if (isLoggedIn) {
      context.read<UserStoriesCubit>().fetchMyLastDayStories();
    }
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      backgroundColor: Colors.white,
      // appBar: normalAppBar("stories".tr(), context),
      body: Column(
        children: [
          TripStartAppBar(
            title: 'stories'.tr(),
            showimage: false,
            onBack: () {
              Navigator.pop(context, true);
            },
          ),

          //  TripStartAppBar(title: "stories".tr()),
          // Segmented tabs
          // Container(
          //   padding: EdgeInsets.symmetric(horizontal: 16.w, vertical: 6.h),
          //   decoration: ShapeDecoration(
          //     color: const Color(0xFFF8FAFC) /* Color-Neutrals-50 */,
          //     shape: RoundedRectangleBorder(
          //       borderRadius: BorderRadius.circular(8),
          //     ),
          //   ),
          //   child: Row(
          //     children: [
          //       Expanded(
          //         child: GestureDetector(
          //           onTap: () => setState(() => _selectedTabIndex = 0),
          //           child: Container(
          //             padding: EdgeInsets.symmetric(
          //               horizontal: 16.w,
          //               vertical: 6.h,
          //             ),
          //             decoration: ShapeDecoration(
          //               color: _selectedTabIndex == 0
          //                   ? const Color(0xFFEBE1F2)
          //                   : Colors.white,
          //               shape: RoundedRectangleBorder(
          //                 borderRadius: BorderRadius.circular(6),
          //               ),
          //             ),
          //             child: GenericText(
          //               "mentioned_you".tr(),
          //               color: _selectedTabIndex == 0
          //                   ? const Color(0xFF6A4690)
          //                   : const Color(0xFF697586),
          //               size: 14.sp,
          //               weight: FontWeight.w500,
          //             ),
          //           ),
          //         ),
          //       ),
          //       Expanded(
          //         child: GestureDetector(
          //           onTap: () => setState(() => _selectedTabIndex = 1),
          //           child: Container(
          //             padding: EdgeInsets.symmetric(
          //               horizontal: 16.w,
          //               vertical: 6.h,
          //             ),
          //             decoration: ShapeDecoration(
          //               color: _selectedTabIndex == 1
          //                   ? const Color(0xFFEBE1F2)
          //                   : Colors.white,
          //               shape: RoundedRectangleBorder(
          //                 borderRadius: BorderRadius.circular(6),
          //               ),
          //             ),
          //             child: GenericText(
          //               'my_posts'.tr(),
          //               color: _selectedTabIndex == 1
          //                   ? const Color(0xFF6A4690)
          //                   : const Color(0xFF697586),
          //               size: 14.sp,
          //               weight: FontWeight.w500,
          //             ),
          //           ),
          //         ),
          //       ),
          //     ],
          //   ),
          // ),
          SizedBox(height: 16.h),

          // Content area
          Expanded(
            child: FutureBuilder<bool>(
              future: AuthManager.isLoggedIn(),
              builder: (context, authSnapshot) {
                if (authSnapshot.connectionState == ConnectionState.waiting) {
                  return const Center(child: CircularProgressIndicator());
                }

                final isLoggedIn = authSnapshot.data ?? false;

                if (!isLoggedIn) {
                  return Center(
                    child: Column(
                      mainAxisAlignment: MainAxisAlignment.center,
                      children: [
                        Icon(
                          Icons.lock_outline,
                          size: 64.sp,
                          color: Colors.grey,
                        ),
                        SizedBox(height: 16.h),
                        GenericText(
                          "login_required_to_view_stories".tr(),
                          color: Colors.grey,
                          size: 16,
                        ),
                        SizedBox(height: 16.h),
                        ElevatedButton(
                          onPressed: () {
                            // Navigate to login screen
                            // You can add navigation logic here
                          },
                          child: Text('login'.tr()),
                        ),
                      ],
                    ),
                  );
                }

                return BlocBuilder<UserStoriesCubit, UserStoriesState>(
                  builder: (context, state) {
                    if (state is UserStoriesLoading) {
                      return const Center(child: CircularProgressIndicator());
                    } else if (state is UserStoriesLoaded) {
                      final stories =
                          state.userStoriesResponse.stories?.data ?? [];

                      if (stories.isEmpty) {
                        return Center(
                          child: Column(
                            mainAxisAlignment: MainAxisAlignment.center,
                            children: [
                              Icon(
                                Icons.book_outlined,
                                size: 64.sp,
                                color: Colors.grey,
                              ),
                              SizedBox(height: 16.h),
                              GenericText(
                                "no_stories".tr(),
                                color: Colors.grey,
                                size: 16,
                              ),
                            ],
                          ),
                        );
                      }

                      return GridView.builder(
                        padding: EdgeInsets.symmetric(horizontal: 16.w),
                        gridDelegate: SliverGridDelegateWithFixedCrossAxisCount(
                          crossAxisCount: 2,
                          crossAxisSpacing: 12.w,
                          mainAxisSpacing: 12.h,
                          childAspectRatio: 0.8,
                        ),
                        itemCount: stories.length,
                        itemBuilder: (context, index) {
                          return UserStoryItem(story: stories[index]);
                        },
                      );
                    } else if (state is UserStoriesError) {
                      return Center(
                        child: Column(
                          mainAxisAlignment: MainAxisAlignment.center,
                          children: [
                            Icon(
                              Icons.error_outline,
                              size: 64.sp,
                              color: Colors.red,
                            ),
                            SizedBox(height: 16.h),
                            GenericText(
                              state.message,
                              color: Colors.red,
                              size: 16,
                            ),
                            SizedBox(height: 16.h),
                            ElevatedButton(
                              onPressed: () {
                                context
                                    .read<UserStoriesCubit>()
                                    .fetchMyLastDayStories();
                              },
                              child: Text("retry".tr()),
                            ),
                          ],
                        ),
                      );
                    }

                    return const SizedBox.shrink();
                  },
                );
              },
            ),
          ),
        ],
      ),
      // Add New Story Button at the bottom
      bottomNavigationBar: Container(
        padding: EdgeInsets.symmetric(horizontal: 16.w, vertical: 20.h),
        decoration: BoxDecoration(
          color: Colors.white,
          boxShadow: [
            BoxShadow(
              color: Colors.black.withOpacity(0.1),
              blurRadius: 10,
              offset: const Offset(0, -2),
            ),
          ],
        ),
        child: Column(
          mainAxisSize: MainAxisSize.min,
          children: [
            // Add New Story Button
            SizedBox(
              width: double.infinity,
              height: 50.h,
              child: ElevatedButton(
                onPressed: () async {
                  final result = await showModalBottomSheet<bool>(
                    context: context,
                    isScrollControlled: true,
                    backgroundColor: Colors.transparent,
                    builder: (context) => MultiBlocProvider(
                      providers: [
                        BlocProvider(
                          create: (context) =>
                              AddStoryCubit(getIt<UserStoriesRepository>()),
                        ),
                      ],
                      child: const AddStoryBottomSheet(),
                    ),
                  );

                  // Refresh stories list if story was added successfully
                  if (result == true) {
                    context.read<UserStoriesCubit>().fetchMyLastDayStories();
                  }
                },
                style: ElevatedButton.styleFrom(
                  backgroundColor: AppColors.primary,
                  foregroundColor: Colors.white,
                  shape: RoundedRectangleBorder(
                    borderRadius: BorderRadius.circular(12.r),
                  ),
                  elevation: 0,
                ),
                child: GenericText(
                  "add_story_title".tr(),
                  color: Colors.white,
                  size: 16,
                  weight: FontWeight.w600,
                ),
              ),
            ),
          ],
        ),
      ),
    );
  }
}
