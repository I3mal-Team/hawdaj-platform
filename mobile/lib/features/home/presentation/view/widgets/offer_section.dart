import 'dart:convert';

import 'package:easy_localization/easy_localization.dart';
import 'package:flutter/material.dart';
import 'package:flutter_bloc/flutter_bloc.dart';
import 'package:flutter_screenutil/flutter_screenutil.dart';
import 'package:hawdaj/features/home/presentation/view/widgets/offers_available_items.dart';
import 'package:hawdaj/features/restaurants/presentation/manager/fetch_offers_cubit/fetch_offers_cubit.dart';
import 'package:infinite_scroll_pagination/infinite_scroll_pagination.dart';
import 'package:hawdaj/features/restaurants/data/model/offer_item_model.dart';

class OfferSection extends StatelessWidget {
  const OfferSection({
    super.key,
    required this.destLat,
    required this.destLong,
  });
  final double destLat;
  final double destLong;

  @override
  Widget build(BuildContext context) {
    final cubit = context.read<FetchOffersCubit>();

    return SliverToBoxAdapter(
      child: SizedBox(
        height: 130.h,
        child: Padding(
          padding: EdgeInsets.symmetric(horizontal: 16.w),
          child: PagedListView<int, OfferItemModel>.separated(
            pagingController: cubit.pagingController,
            scrollDirection: Axis.horizontal,
            builderDelegate: PagedChildBuilderDelegate<OfferItemModel>(
              itemBuilder: (context, item, index) {
                return SizedBox(
                  width: 289.w,
                  child: OffersAvailableItems(
                    offer: item,
                    destLat: destLat,
                    destLong: destLong,
                  ),
                );
              },
              firstPageErrorIndicatorBuilder: (context) =>
                  Center(child: Text("load_error".tr())),
              noItemsFoundIndicatorBuilder: (context) =>
                  Center(child: Text("empty".tr())),
              newPageProgressIndicatorBuilder: (context) =>
                  const Center(child: CircularProgressIndicator()),
              firstPageProgressIndicatorBuilder: (context) =>
                  const Center(child: CircularProgressIndicator()),
            ),
            separatorBuilder: (context, index) => SizedBox(width: 12.w),
          ),
        ),
      ),
    );
  }
}
