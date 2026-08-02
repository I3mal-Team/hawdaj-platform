import 'package:flutter/material.dart';
import 'package:flutter_screenutil/flutter_screenutil.dart';

class AppBarGradientBackground extends StatelessWidget {
  const AppBarGradientBackground({super.key});

  @override
  Widget build(BuildContext context) {
    return ClipRRect(
      borderRadius: BorderRadius.vertical(bottom: Radius.circular(2.r)),
      child: Opacity(
        opacity: 0.7,
        child: Container(
          decoration: const BoxDecoration(
            gradient: LinearGradient(
              colors: [Color(0xFFA1708F), Color(0xFFFCF7F5)],
              begin: Alignment.topCenter,
              end: Alignment.bottomCenter,
            ),
          ),
        ),
      ),
    );
  }
}
