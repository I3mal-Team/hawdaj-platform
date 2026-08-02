import 'package:flutter/material.dart';
import 'package:flutter_screenutil/flutter_screenutil.dart';

class GlobalGlossyContainer extends StatelessWidget {
  final double? width;
  final double? height;
  final EdgeInsetsGeometry? padding;
  final Widget? child;

  final VoidCallback? onTap;

  const GlobalGlossyContainer({
    super.key,
    this.width,
    this.height,
    this.padding,
    this.child,
    this.onTap,
  });

  @override
  Widget build(BuildContext context) {
    return GestureDetector(
      onTap: onTap,
      child: Container(
        width: width,
        height: height,
        padding: padding,
        decoration: BoxDecoration(
          color: const Color(0xFFE7D1CF).withOpacity(0.4),
          borderRadius: BorderRadius.circular(8.r),
          border: Border(
            right: BorderSide(color: Colors.white.withOpacity(0.6), width: 1.2),
            left: BorderSide(color: Colors.white.withOpacity(0.6), width: 1.2),
          ),
          boxShadow: [
            BoxShadow(
              color: Colors.black.withOpacity(0.05),
              blurRadius: 8,
              offset: const Offset(0, 2),
            ),
          ],
        ),
        child: child,
      ),
    );
  }
}
