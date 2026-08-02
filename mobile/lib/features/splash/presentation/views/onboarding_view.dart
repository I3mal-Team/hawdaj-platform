import 'package:easy_localization/easy_localization.dart';
import 'package:flutter/material.dart';
import 'package:flutter_screenutil/flutter_screenutil.dart';
import 'package:hawdaj/core/components/spaces.dart';
import 'package:hawdaj/core/components/white_button.dart';
import 'package:hawdaj/core/routing/route_utils.dart';
import 'package:hawdaj/core/routing/routes_keys.dart';
import 'package:hawdaj/core/utils/app_fonts.dart';
import 'package:hawdaj/features/splash/data/models/onboarding_model.dart';
import 'package:shared_preferences/shared_preferences.dart';

class OnboardingView extends StatefulWidget {
  const OnboardingView({super.key});

  @override
  State<OnboardingView> createState() => _OnboardingViewState();
}

class _OnboardingViewState extends State<OnboardingView> {
  final PageController _pageController = PageController();
  int activeIndex = 0;

  @override
  void dispose() {
    _pageController.dispose();
    super.dispose();
  }

  Future<void> _nextPage() async {
    if (activeIndex < OnboardingModel.onboardingList.length - 1) {
      if (!mounted) return; // حماية
      setState(() {
        activeIndex++;
      });

      if (!mounted) return; // حماية
      _pageController.nextPage(
        duration: Duration(milliseconds: 300),
        curve: Curves.easeInOut,
      );
    } else {
      final prefs = await SharedPreferences.getInstance();

      if (!mounted) return; // حماية بعد await

      await prefs.setBool("seenOnboarding", true);

      if (!mounted) return; // حماية قبل استخدام context

      pushReplacement(RoutesKeys.kHomeView, context);
    }
  }

  // Future<void> _nextPage() async {
  //   if (activeIndex < OnboardingModel.onboardingList.length - 1) {
  //     setState(() {
  //       activeIndex++;
  //     });
  //     _pageController.nextPage(
  //       duration: Duration(milliseconds: 300),
  //       curve: Curves.easeInOut,
  //     );
  //   } else {
  //     final prefs = await SharedPreferences.getInstance();
  //     await prefs.setBool("seenOnboarding", true);
  //     pushReplacement(RoutesKeys.kHomeView, context);
  //   }
  // }

  @override
  Widget build(BuildContext context) {
    final currentOnboardingItem = OnboardingModel.onboardingList[activeIndex];

    return Scaffold(
      body: Stack(
        children: [
          // Full screen PageView with background images
          Positioned.fill(
            child: PageView.builder(
              controller: _pageController,
              itemCount: OnboardingModel.onboardingList.length,
              onPageChanged: (index) {
                setState(() {
                  activeIndex = index;
                });
              },
              itemBuilder: (context, index) {
                final onboardingItem = OnboardingModel.onboardingList[index];
                return Image.asset(
                  onboardingItem.image,
                  width: double.infinity,
                  height: double.infinity,
                  fit: BoxFit.cover,
                );
              },
            ),
          ),
          // Full screen gradient overlay
          Positioned.fill(
            child: Container(
              decoration: BoxDecoration(
                gradient: LinearGradient(
                  begin: Alignment.topCenter,
                  end: Alignment.bottomCenter,
                  stops: [0.4, 1.0],
                  colors: [
                    Colors.black.withOpacity(0),
                    Color(0xff2C2C2C).withOpacity(0.8),
                  ],
                ),
              ),
            ),
          ),
          // Bottom content overlay
          Positioned(
            left: 0,
            right: 0,
            bottom: 0,
            child: Container(
              padding: EdgeInsets.symmetric(horizontal: 16.w, vertical: 24.h),
              child: Column(
                mainAxisSize: MainAxisSize.min,
                children: [
                  // Fixed texts
                  Text(
                    currentOnboardingItem.title,
                    style: TextStyle(
                      color: Colors.white,
                      fontSize: 40.sp,
                      fontWeight: FontWeight.w900,
                      fontFamily: AppFonts.theYearOfTheCamel,
                    ),
                    textAlign: TextAlign.center,
                  ),
                  HeightSpace(16),
                  Text(
                    currentOnboardingItem.description,
                    style: TextStyle(
                      color: Colors.white,
                      fontSize: 14.sp,
                      fontWeight: FontWeight.w500,
                    ),
                    textAlign: TextAlign.center,
                  ),
                  HeightSpace(16),
                  // Page Indicators
                  Row(
                    mainAxisAlignment: MainAxisAlignment.center,
                    children: List.generate(
                      OnboardingModel.onboardingList.length,
                      (index) {
                        return Container(
                          margin: EdgeInsets.symmetric(horizontal: 4.w),
                          width: 32.w,
                          height: 4.h,
                          decoration: BoxDecoration(
                            color: activeIndex == index
                                ? Colors.white
                                : Color(0xffE9EAF0).withOpacity(0.5),
                            borderRadius: BorderRadius.circular(12.r),
                          ),
                        );
                      },
                    ),
                  ),
                  HeightSpace(24),
                  // Next/Explore Button
                  WhiteButton(
                    title:
                        activeIndex < OnboardingModel.onboardingList.length - 1
                        ? 'next'.tr()
                        : 'استكشف',
                    onPressed: _nextPage,
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
