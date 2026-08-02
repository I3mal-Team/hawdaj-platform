// ignore_for_file: prefer_const_constructors, prefer_const_literals_to_create_immutables

import 'package:flutter/material.dart';
import 'package:flutter_svg/svg.dart';

class AssetIcon extends StatelessWidget {
  final String path;
  final double? width;
  final double? height;
  final Color? color;

  const AssetIcon(this.path, {super.key, this.width, this.height, this.color});
  bool get isSVG {
    return path.endsWith('.svg');
  }

  @override
  Widget build(BuildContext context) {
    if (isSVG) {
      return SvgPicture.asset(
        path,
        width: width,
        height: height,
        colorFilter: color != null
            ? ColorFilter.mode(color!, BlendMode.srcIn)
            : null,
      );
    } else {
      return Image.asset(path, width: width, height: height, color: color);
    }
  }
}
