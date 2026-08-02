import 'package:flutter/material.dart';

class PrimaryIconButton extends StatelessWidget {
  const PrimaryIconButton({
    super.key,
    required this.icon,
    this.onPressed,
    this.margin,
    this.padding,
    this.width = 44,
    this.height = 44,
    this.borderRadius = 16,
    this.color,
  });

  final Widget icon;
  final void Function()? onPressed;
  final EdgeInsetsGeometry? margin;
  final EdgeInsetsGeometry? padding;
  final double? width;
  final double? height;
  final double? borderRadius;
  final Color? color;

  @override
  Widget build(BuildContext context) {
    return IconButton(
      style: ButtonStyle(
        visualDensity: VisualDensity.compact,
        tapTargetSize: MaterialTapTargetSize.shrinkWrap,
        padding: WidgetStateProperty.all(EdgeInsets.zero),
        overlayColor: WidgetStateProperty.all(Colors.transparent),
      ),
      icon: Container(
        width: width,
        height: height,
        margin: margin,
        padding: padding,
        decoration: BoxDecoration(
          color: color ?? const Color(0xFff7f7f7),
          borderRadius: BorderRadius.circular(borderRadius ?? 16),
          border: Border.all(color: const Color(0xFFf2f2f2), width: 1),
        ),
        child: FittedBox(
          fit: BoxFit.scaleDown,
          child: Center(child: icon),
        ),
      ),
      onPressed: onPressed,
    );
  }
}
