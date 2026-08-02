import 'package:hawdaj/core/styles/app_text_styles.dart';
import 'package:hawdaj/core/utils/app_assets.dart';
import 'package:flutter/material.dart';

class NoInternetView extends StatelessWidget {
  const NoInternetView({super.key});

  @override
  Widget build(BuildContext context) {
    return PopScope(
      canPop: false,
      child: Scaffold(
        body: Padding(
          padding: const EdgeInsets.all(20),
          child: Column(
            mainAxisAlignment: MainAxisAlignment.center,
            children: [
              Image.asset(AppAssets.gifsNoInternet),
              const SizedBox(height: 16),
              Text(
                'No Internet Connection',
                textAlign: TextAlign.center,
                style: AppTextStyles.font20Regular,
              ),
            ],
          ),
        ),
      ),
    );
  }
}
