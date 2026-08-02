import 'package:flutter/material.dart';
import 'package:flutter_bloc/flutter_bloc.dart';
import 'package:flutter_screenutil/flutter_screenutil.dart';
import 'package:flutter_svg/svg.dart';
import 'package:hawdaj/core/routing/route_utils.dart';
import 'package:hawdaj/core/routing/routes_keys.dart';
import 'package:hawdaj/core/utils/app_assets.dart';
import 'package:hawdaj/features/force_update/presentation/cubit/force_update_cubit.dart';
import 'package:hawdaj/features/force_update/presentation/widgets/force_update_dialog.dart';
import 'package:shared_preferences/shared_preferences.dart';

class SplashView extends StatefulWidget {
  const SplashView({super.key});

  @override
  State<SplashView> createState() => _SplashViewState();
}

class _SplashViewState extends State<SplashView> {
  @override
  void initState() {
    super.initState();
    _navigateToNextScreen();
  }

  Future<void> _navigateToNextScreen() async {
    await Future.delayed(const Duration(seconds: 2));
    if (!mounted) return;

    final prefs = await SharedPreferences.getInstance();
    final seenOnboarding = prefs.getBool("seenOnboarding") ?? false;

    if (seenOnboarding) {
      pushReplacement(RoutesKeys.kHomeView, context);
    } else {
      pushReplacement(RoutesKeys.kOnboardingView, context);
    }
  }

  @override
  Widget build(BuildContext context) {
    return SplashViewBody();
  }
}

class SplashViewBody extends StatelessWidget {
  const SplashViewBody({super.key});

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      body: Stack(
        children: [
          Image.asset(
            AppAssets.splashBG,
            fit: BoxFit.cover,
            width: double.infinity,
            height: double.infinity,
          ),
          Center(child: SvgPicture.asset(AppAssets.splashLogo, height: 70.h)),
        ],
      ),
    );
  }
}
