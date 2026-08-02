import 'package:easy_localization/easy_localization.dart';
import 'package:flutter/material.dart';
import 'package:flutter_bloc/flutter_bloc.dart';
import 'package:flutter_screenutil/flutter_screenutil.dart';
import 'package:flutter_svg/flutter_svg.dart';
import 'package:hawdaj/core/components/spaces.dart';
import 'package:hawdaj/core/managers/regions_cubit/regions_cubit.dart';
import 'package:hawdaj/core/styles/app_colors.dart';
import 'package:hawdaj/core/utils/app_assets.dart';

class RegionDropdown extends StatelessWidget {
  final int? selectedRegionId;
  final ValueChanged<int?> onChanged;
  final String hint;
  final bool enableClear;
  final VoidCallback? onClear;
  final String icon;

  const RegionDropdown({
    super.key,
    this.selectedRegionId,
    required this.onChanged,
    this.hint = "choose_regions_hint",
    this.enableClear = true,
    this.onClear,
    this.icon = AppAssets.watch,
  });

  @override
  Widget build(BuildContext context) {
    return BlocBuilder<RegionsCubit, RegionsState>(
      builder: (context, state) {
        return Container(
          decoration: ShapeDecoration(
            color: AppColors.white,
            shape: RoundedRectangleBorder(
              side: const BorderSide(width: 1, color: Color(0xFFF1F1F1)),
              borderRadius: BorderRadius.circular(16),
            ),
          ),
          padding: const EdgeInsets.symmetric(horizontal: 12, vertical: 4),
          child: _buildField(state),
        );
      },
    );
  }

  Widget _buildField(RegionsState state) {
    const double fieldHeight = 48;

    if (state is RegionsLoading) {
      return const SizedBox(
        height: fieldHeight,
        child: Align(
          alignment: Alignment.centerLeft,
          child: SizedBox(
            width: 20,
            height: 20,
            child: CircularProgressIndicator(strokeWidth: 2),
          ),
        ),
      );
    }

    if (state is RegionsError) {
      return Padding(
        padding: const EdgeInsets.symmetric(vertical: 12),
        child: Text(' ${state.errMessage}'),
      );
    }

    if (state is! RegionsSuccess) {
      // Initial أو أي حالة تانية: عنصر مقفول بشكل أنيق
      return SizedBox(
        height: fieldHeight,
        child: DropdownButtonFormField<int>(
          value: null,
          isExpanded: true,
          hint: Text(hint.tr(), style: const TextStyle(color: Colors.grey)),
          decoration: InputDecoration(
            border: InputBorder.none,
            isDense: true,
            contentPadding: const EdgeInsets.symmetric(vertical: 12),
            suffixIconConstraints: const BoxConstraints(
              minWidth: 40,
              minHeight: 40,
            ),
            prefix: Padding(
              padding: const EdgeInsets.symmetric(horizontal: 6),
              child: SvgPicture.asset(icon, width: 18.w, height: 18.h),
            ),
            suffixIcon: const IgnorePointer(
              child: Padding(
                padding: EdgeInsetsDirectional.only(end: 6),
                child: Icon(Icons.keyboard_arrow_down_rounded),
              ),
            ),
          ),
          icon: const SizedBox.shrink(), // اخفاء الأيقونة الافتراضية
          items: const [],
          onChanged: null,
        ),
      );
    }

    // Success
    final regions = state.regions;
    final hasData = regions.isNotEmpty;

    final int? validSelectedId =
        (hasData && regions.any((r) => r.id == selectedRegionId))
        ? selectedRegionId
        : null;

    final bool showClear = enableClear && hasData && validSelectedId != null;

    return SizedBox(
      height: fieldHeight,
      child: DropdownButtonFormField<int>(
        value: validSelectedId,
        isExpanded: true,
        hint: Text(hint, style: const TextStyle(color: Colors.grey)),
        dropdownColor: AppColors.white,

        icon: const SizedBox.shrink(),
        menuMaxHeight: 350,
        decoration: InputDecoration(
          border: InputBorder.none,
          isDense: true,

          contentPadding: const EdgeInsets.symmetric(vertical: 12),
          suffixIconConstraints: const BoxConstraints(
            minWidth: 40,
            minHeight: 40,
          ),
          prefix: Padding(
            padding: const EdgeInsets.symmetric(horizontal: 6),
            child: SvgPicture.asset(icon, width: 18.w, height: 18.h),
          ),
          suffixIcon: AnimatedSwitcher(
            duration: const Duration(milliseconds: 180),
            child: showClear
                ? Tooltip(
                    key: const ValueKey('clear'),
                    message: 'clear_region'.tr(),
                    child: IconButton(
                      onPressed: () {
                        onClear?.call();
                        onChanged(null);
                      },
                      icon: SvgPicture.asset(AppAssets.closeCircle),
                      splashRadius: 18,
                    ),
                  )
                : const IgnorePointer(
                    key: ValueKey('arrow'),
                    child: Padding(
                      padding: EdgeInsetsDirectional.only(end: 6),
                      child: Icon(Icons.keyboard_arrow_down_rounded),
                    ),
                  ),
          ),
        ),
        items: regions
            .map(
              (region) => DropdownMenuItem<int>(
                value: region.id,
                child: Row(
                  children: [
                    // SvgPicture.asset(icon, width: 18.w, height: 18.h),
                    // WidthSpace(8.w),
                    Expanded(
                      child: Text(
                        region.name,
                        maxLines: 1,
                        overflow: TextOverflow.ellipsis,
                        style: TextStyle(fontSize: 14.sp),
                      ),
                    ),
                  ],
                ),
              ),
            )
            .toList(),

        onChanged: hasData ? onChanged : null,
      ),
    );
  }
}
