import 'package:cached_network_image/cached_network_image.dart';
import 'package:easy_localization/easy_localization.dart';
import 'package:flutter/material.dart';
import 'package:flutter_bloc/flutter_bloc.dart';
import 'package:flutter_screenutil/flutter_screenutil.dart';
import 'package:hawdaj/core/databases/api/end_points.dart';
import 'package:hawdaj/core/utils/app_assets.dart';
import 'package:hawdaj/features/home/presentation/view/widgets/menu_item_skeleton.dart';
import 'package:hawdaj/features/restaurants/data/model/menu_item_model.dart';
import 'package:hawdaj/features/restaurants/presentation/manager/menu_cubit/menu_cubit.dart';
import 'package:infinite_scroll_pagination/infinite_scroll_pagination.dart';

class MenuListSection extends StatelessWidget {
  const MenuListSection({super.key});

  @override
  Widget build(BuildContext context) {
    return PagedListView<int, MenuItemModel>.separated(
      shrinkWrap: true,
      physics: const NeverScrollableScrollPhysics(),
      pagingController: context.read<MenuCubit>().pagingController,
      padding: EdgeInsets.symmetric(horizontal: 16.w),
      builderDelegate: PagedChildBuilderDelegate<MenuItemModel>(
        itemBuilder: (context, item, index) {
          return CachedNetworkImage(
            imageUrl: "${item.image}",
            placeholder: (context, url) => const MenuItemSkeleton(),
            errorWidget: (context, url, error) => Image.asset(AppAssets.test3),
          );
        },
        noItemsFoundIndicatorBuilder: (context) =>
            Center(child: Text("no_zads".tr())),
        firstPageProgressIndicatorBuilder: (context) =>
            Center(child: CircularProgressIndicator()),
        newPageProgressIndicatorBuilder: (context) =>
            Center(child: CircularProgressIndicator()),
        firstPageErrorIndicatorBuilder: (context) =>
            Center(child: Text("error_label".tr())),
        newPageErrorIndicatorBuilder: (context) =>
            Center(child: Text("load_more_error".tr())),
      ),
      separatorBuilder: (_, __) => Divider(),
    );
  }
}
