import 'package:flutter/material.dart';
import 'package:flutter_svg/svg.dart';
import 'package:hawdaj/core/utils/app_assets.dart';
import 'package:flutter_svg/flutter_svg.dart';

class AppBarTopRowDetails extends StatelessWidget {
  final VoidCallback? onBackTap;
  final VoidCallback? onFavoriteTap;
  final bool isFavorite;
  final String? backIconPath;
  final String? favoriteIconPath;
  final bool showFavoriteButton;

  const AppBarTopRowDetails({
    super.key,
    this.onBackTap,
    this.onFavoriteTap,
    this.isFavorite = false,
    this.showFavoriteButton = true,
    this.backIconPath,
    this.favoriteIconPath,
  });

  @override
  Widget build(BuildContext context) {
    final isRtl = Directionality.of(context) == TextDirection.rtl;
    return Row(
      mainAxisAlignment: MainAxisAlignment.spaceBetween,
      children: [
        GestureDetector(
          onTap: onBackTap ?? () => Navigator.of(context).pop(),
          child: Transform(
            alignment: Alignment.center,
            transform: Matrix4.rotationY(isRtl ? 0 : 3.1416),
            child: SvgPicture.asset(backIconPath ?? AppAssets.arrow),
          ),
        ),
        if (showFavoriteButton)
          GestureDetector(
            onTap: onFavoriteTap,
            child: SvgPicture.asset(
              isFavorite ? AppAssets.activeFavorite : AppAssets.hart,
            ),
          ),
      ],
    );
  }
}
