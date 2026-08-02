import 'package:flutter/material.dart';
import 'package:flutter_screenutil/flutter_screenutil.dart';

class TinyIconButton extends StatelessWidget {
  final String icon;
  final VoidCallback onPressed;
  final String? tooltip;

  const TinyIconButton({
    required this.icon,
    required this.onPressed,
    this.tooltip,
  });

  @override
  Widget build(BuildContext context) {
    return Tooltip(
      message: tooltip ?? '',
      child: IconButton(
        onPressed: onPressed,
        icon: Image.asset(icon, width: 36.w, height: 36.h),
        padding: EdgeInsets.zero,
        visualDensity: VisualDensity.compact,
        constraints: const BoxConstraints.tightFor(width: 36, height: 36),
        splashRadius: 18,
      ),
    );
  }
}
