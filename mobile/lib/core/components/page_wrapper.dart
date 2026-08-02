// ignore_for_file: prefer_const_constructors, prefer_const_literals_to_create_immutables

import 'package:flutter/material.dart';

class PageWrapper extends StatelessWidget {
  final String? title;
  final Widget? titleWidget;
  final Widget? leading;
  final Widget? trailing;
  final bool backAlwaysVisible;
  final Widget child;
  final List<Color>? shaderColors;
  final bool allowBack;
  final BlendMode? patternBlendMode;
  final int? bgPatternCount;
  final bool bottomSafeArea;

  const PageWrapper({
    super.key,
    this.title,
    this.titleWidget,
    this.leading,
    this.trailing,
    this.backAlwaysVisible = false,
    required this.child,
    this.allowBack = true,
    this.patternBlendMode,
    this.shaderColors,
    this.bgPatternCount,
    this.bottomSafeArea = true,
  });

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      backgroundColor: Colors.white,
      body: SafeArea(
        bottom: bottomSafeArea,
        child: Column(children: [Expanded(child: child)]),
      ),
    );
  }
}
