import 'package:flutter/material.dart';
import 'package:flutter_screenutil/flutter_screenutil.dart';
import 'package:hawdaj/core/components/spaces.dart';

class MenuItemSkeleton extends StatelessWidget {
  const MenuItemSkeleton({super.key});

  @override
  Widget build(BuildContext context) {
    return Container(
      margin: EdgeInsets.only(bottom: 12.h),
      padding: EdgeInsets.all(12.r),
      decoration: BoxDecoration(
        color: Colors.grey.shade200,
        borderRadius: BorderRadius.circular(12.r),
      ),
      height: 120.h,
      width: double.infinity,
      child: Row(
        children: [
          Container(
            width: 100.w,
            height: 100.h,
            decoration: BoxDecoration(
              color: Colors.grey.shade300,
              borderRadius: BorderRadius.circular(8.r),
            ),
          ),
          WidthSpace(12.w),
          Expanded(
            child: Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                Container(
                  width: double.infinity,
                  height: 12.h,
                  color: Colors.grey.shade300,
                ),
                HeightSpace(8.h),
                Container(
                  width: 100.w,
                  height: 10.h,
                  color: Colors.grey.shade300,
                ),
                HeightSpace(8.h),
                Container(
                  width: 80.w,
                  height: 10.h,
                  color: Colors.grey.shade300,
                ),
              ],
            ),
          ),
        ],
      ),
    );
  }
}
