import 'package:flutter/material.dart';
import 'package:flutter_screenutil/flutter_screenutil.dart';
import 'package:hawdaj/features/home/presentation/view/widgets/property_item_card.dart';
import 'package:hawdaj/core/components/spaces.dart';

class PropertiesGrid extends StatelessWidget {
  final List<PlaceProperty> properties;

  const PropertiesGrid({super.key, required this.properties});

  @override
  Widget build(BuildContext context) {
    return SliverPadding(
      padding: EdgeInsets.only(top: 8.h),
      sliver: SliverGrid(
        gridDelegate: SliverGridDelegateWithFixedCrossAxisCount(
          crossAxisCount: 2,
          mainAxisSpacing: 12.h,
          crossAxisSpacing: 12.w,
          childAspectRatio: 2.1,
        ),
        delegate: SliverChildBuilderDelegate((context, index) {
          final item = properties[index];
          return PropertyItemCard(
            icon: item.icon,
            title: item.title,
            value: item.value,
          );
        }, childCount: properties.length),
      ),
    );
  }
}

class PlaceProperty {
  final String title;
  final String value;
  final String icon;

  const PlaceProperty({
    required this.title,
    required this.value,
    required this.icon,
  });
}
