import 'package:hawdaj/core/components/global_network_image.dart';
import 'package:flutter/material.dart';

void showFullscreenImage(BuildContext context, String imageUrl) {
  showDialog(
    context: context,
    builder: (_) => Dialog(
      backgroundColor: Colors.transparent,
      insetPadding: const EdgeInsets.symmetric(horizontal: 16, vertical: 32),
      child: Dismissible(
        direction: DismissDirection.down,
        key: UniqueKey(),
        onDismissed: (direction) => Navigator.of(context).pop(),
        child: ClipRRect(
          borderRadius: const BorderRadius.all(Radius.circular(24)),
          child: InteractiveViewer(
            child: GNImage(imageUrl, fit: BoxFit.contain, cached: true),
          ),
        ),
      ),
    ),
  );
}
