import 'package:flutter/material.dart';
import 'package:hawdaj/core/styles/app_colors.dart';
import 'package:hawdaj/core/styles/app_text_styles.dart';
import 'package:hawdaj/core/utils/app_assets.dart';
import 'package:hawdaj/features/restaurants/data/model/offer_item_model.dart';

class OfferHeader extends StatelessWidget {
  const OfferHeader({super.key, required this.offer});
  final OfferItemModel offer;

  @override
  Widget build(BuildContext context) {
    return Row(
      mainAxisAlignment: MainAxisAlignment.spaceBetween,
      children: [
        Text(offer.title, style: AppTextStyles.font14Bold),
        _buildPriceInfo(),
      ],
    );
  }

  Widget _buildPriceInfo() {
    return Row(
      children: [
        _buildStrikethroughPrice(offer.price),
        const SizedBox(width: 4),
        Text(
          offer.priceAfterDiscount.toString(),
          style: AppTextStyles.font12Bold.copyWith(color: AppColors.primary),
        ),
        Image.asset(AppAssets.unicode),
      ],
    );
  }

  Widget _buildStrikethroughPrice(String price) {
    return Stack(
      alignment: Alignment.center,
      children: [
        Text(
          price,
          textAlign: TextAlign.right,
          style: AppTextStyles.font10Bold.copyWith(color: Colors.grey[600]),
        ),
        Align(
          alignment: Alignment.center,
          child: Container(width: 30, height: 0.5, color: Colors.grey[800]),
        ),
      ],
    );
  }
}
