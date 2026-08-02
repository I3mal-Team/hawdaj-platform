import 'package:easy_localization/easy_localization.dart';
import 'package:flutter/material.dart';
import 'package:flutter_bloc/flutter_bloc.dart';
import 'package:flutter_svg/flutter_svg.dart';
import 'package:flutter_screenutil/flutter_screenutil.dart';
import 'package:hawdaj/core/styles/app_colors.dart';
import 'package:hawdaj/core/utils/app_assets.dart';
import 'package:hawdaj/features/my_properties/presentation/manager/add_property_form_cubit/add_property_form_cubit.dart';

class AddressTypeDropdown extends StatelessWidget {
  const AddressTypeDropdown({super.key});

  @override
  Widget build(BuildContext context) {
    return BlocBuilder<AddPropertyFormCubit, AddPropertyFormState>(
      builder: (context, state) {
        final cubit = context.read<AddPropertyFormCubit>();
        final types = AddPropertyFormCubit.addressTypes;
        final selectedType = state.addressType;

        final bool showClear = selectedType != null && selectedType.isNotEmpty;

        return Container(
          decoration: ShapeDecoration(
            color: AppColors.white,
            shape: RoundedRectangleBorder(
              side: const BorderSide(width: 1, color: Color(0xFFF1F1F1)),
              borderRadius: BorderRadius.circular(16),
            ),
          ),
          padding: const EdgeInsets.symmetric(horizontal: 12, vertical: 4),
          child: DropdownButtonFormField<String>(
            icon: const SizedBox.shrink(),
            value: selectedType != null && selectedType.isNotEmpty
                ? selectedType
                : null,
            isExpanded: true,
            hint: Text(
              "select_address_type".tr(),
              style: TextStyle(color: Colors.grey),
            ),
            dropdownColor: AppColors.white,
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
                child: SvgPicture.asset(
                  AppAssets.locationIcon,
                  width: 18.w,
                  height: 16.h,
                ),
              ),
              suffixIcon: AnimatedSwitcher(
                duration: const Duration(milliseconds: 180),
                child: showClear
                    ? Tooltip(
                        key: const ValueKey('clear'),
                        message: 'clear'.tr(),
                        child: IconButton(
                          onPressed: () {
                            cubit.updateAddressType(null);
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
            items: types
                .map(
                  (type) => DropdownMenuItem<String>(
                    value: type,
                    child: Row(
                      children: [
                        Expanded(
                          child: Text(
                            type == 'link' ? 'link'.tr() : 'map'.tr(),
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
            onChanged: (value) {
              if (value != null) {
                cubit.updateAddressType(value);
              }
            },
          ),
        );
      },
    );
  }
}
