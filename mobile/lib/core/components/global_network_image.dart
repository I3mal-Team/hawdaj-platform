import 'package:hawdaj/core/utils/app_assets.dart';
import 'package:hawdaj/core/utils/functions/image_helper.dart';
import 'package:cached_network_image/cached_network_image.dart';
import 'package:flutter/material.dart';
import 'package:flutter_svg/svg.dart';

// global network image
class GNImage extends StatelessWidget {
  final String? link;
  final double? width;
  final double? height;
  final BoxFit? fit;
  final bool cached;
  final ImageWidgetBuilder? imageBuilder;
  const GNImage(
    this.link, {
    super.key,
    this.width,
    this.height,
    this.fit,
    this.cached = false,
    this.imageBuilder,
  });

  @override
  Widget build(BuildContext context) {
    if (cached) {
      return CachedNetworkImage(
        imageBuilder: imageBuilder,
        placeholder: (context, url) {
          return _NonImageBuilder(fit: fit, width: width, height: height);
        },
        imageUrl: link ?? '',
        width: width,
        height: height,
        fit: fit,
        errorWidget: (context, error, stackTrace) {
          return _NonImageBuilder(fit: fit, width: width, height: height);
        },
      );
    }
    return FilesHelper.isSvg(link ?? '') == false
        ? Image.network(
            link ?? '',
            width: width,
            height: height,
            fit: fit,
            loadingBuilder: (context, child, loadingProgress) {
              bool loaded =
                  loadingProgress?.cumulativeBytesLoaded ==
                  loadingProgress?.expectedTotalBytes;
              if (loaded) {
                return child;
              }
              return _NonImageBuilder(fit: fit, width: width, height: height);
            },
            errorBuilder: (context, error, stackTrace) {
              return _NonImageBuilder(fit: fit, width: width, height: height);
            },
          )
        : SvgPicture.network(
            link ?? '',
            width: width,
            height: height,
            fit: fit ?? BoxFit.cover,
            placeholderBuilder: (context) =>
                _NonImageBuilder(fit: fit, width: width, height: height),
          );
  }
}

class _NonImageBuilder extends StatelessWidget {
  const _NonImageBuilder({
    required this.width,
    required this.height,
    required this.fit,
  });

  final double? width;
  final double? height;
  final BoxFit? fit;

  @override
  Widget build(BuildContext context) {
    return Image.asset(
      AppAssets.logoRound,
      width: width,
      height: height,
      fit: fit,
    );
  }
}
