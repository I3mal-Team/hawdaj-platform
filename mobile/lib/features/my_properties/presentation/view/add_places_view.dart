import 'package:easy_localization/easy_localization.dart';
import 'package:flutter/material.dart';
import 'package:flutter_bloc/flutter_bloc.dart';
import 'package:flutter_screenutil/flutter_screenutil.dart';
import 'package:hawdaj/core/components/custom_failure_toast.dart';
import 'package:hawdaj/core/components/custom_success_toast.dart';
import 'package:hawdaj/core/styles/app_colors.dart';
import 'package:hawdaj/core/styles/app_text_styles.dart';
import 'package:hawdaj/features/my_properties/presentation/manager/add_property_cubit/add_property_cubit.dart';
import 'package:hawdaj/features/my_properties/presentation/manager/add_property_form_cubit/add_property_form_cubit.dart';
import 'package:hawdaj/features/my_properties/presentation/view/widgets/add_place_form_body.dart';

class AddPlacesView extends StatelessWidget {
  const AddPlacesView({super.key});

  @override
  Widget build(BuildContext context) {
    return BlocConsumer<AddPropertyCubit, AddPropertyState>(
      listener: (context, state) {
        if (state is AddPropertySuccess) {
          showCustomSuccessToast('save_success'.tr());
          Navigator.pop(context, true);
        } else if (state is AddPropertyError) {
          showCustomFailureToast('${state.message}');
        }
      },
      builder: (context, state) {
        final isLoading = state is AddPropertyLoading;

        return Scaffold(
          body: Stack(
            children: [
              const AddPlaceFormBody(),

              // ✅ حالة التحميل فوق الشاشة
              if (isLoading)
                Container(
                  color: Colors.black.withOpacity(0.2),
                  child: Center(
                    child: CircularProgressIndicator(color: AppColors.primary),
                  ),
                ),
            ],
          ),
          bottomNavigationBar: Padding(
            padding: EdgeInsets.all(16.0.w),
            child: Row(
              children: [
                Expanded(
                  child: ElevatedButton(
                    onPressed: isLoading
                        ? null
                        : () {
                            context.read<AddPropertyFormCubit>().submit();
                            final request = context
                                .read<AddPropertyFormCubit>()
                                .toRequest();
                            context.read<AddPropertyCubit>().addProperty(
                              request,
                            );
                          },
                    style: ElevatedButton.styleFrom(
                      backgroundColor: AppColors.primary,
                      padding: EdgeInsets.symmetric(vertical: 16.h),
                      shape: RoundedRectangleBorder(
                        borderRadius: BorderRadius.circular(8.r),
                      ),
                    ),
                    child: isLoading
                        ? SizedBox(
                            width: 20.w,
                            height: 20.w,
                            child: const CircularProgressIndicator(
                              color: Colors.white,
                              strokeWidth: 2.5,
                            ),
                          )
                        : Text(
                            'save'.tr(),
                            style: AppTextStyles.font16Bold.copyWith(
                              color: Colors.white,
                            ),
                          ),
                  ),
                ),
                SizedBox(width: 16.w),
                Expanded(
                  child: ElevatedButton(
                    onPressed: isLoading ? null : () => Navigator.pop(context),
                    style: ElevatedButton.styleFrom(
                      backgroundColor: const Color(0xFFF5F7F8),
                      padding: EdgeInsets.symmetric(vertical: 16.h),
                      shape: RoundedRectangleBorder(
                        borderRadius: BorderRadius.circular(8.r),
                      ),
                    ),
                    child: Text(
                      'cancel'.tr(),
                      style: AppTextStyles.font16Bold.copyWith(
                        color: Colors.black,
                      ),
                    ),
                  ),
                ),
              ],
            ),
          ),
        );
      },
    );
  }
}
