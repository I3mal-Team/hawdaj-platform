import 'dart:io';
import 'package:easy_localization/easy_localization.dart';
import 'package:flutter/material.dart';
import 'package:flutter_bloc/flutter_bloc.dart';
import 'package:flutter_screenutil/flutter_screenutil.dart';
import 'package:flutter_svg/svg.dart';
import 'package:hawdaj/core/components/custom_failure_toast.dart';
import 'package:hawdaj/core/components/custom_success_toast.dart';
import 'package:hawdaj/core/components/primary_button.dart';
import 'package:hawdaj/core/components/seconday_button.dart';
import 'package:hawdaj/core/components/text_components/generic_text.dart';
import 'package:hawdaj/features/home/presentation/view/widgets/app_bar_gradient_background.dart';
import 'package:image_picker/image_picker.dart';
import 'package:hawdaj/core/components/custom_text_field/custom_app_form_text_field.dart';
import 'package:hawdaj/core/components/spaces.dart';
import 'package:hawdaj/core/styles/app_colors.dart';
import 'package:hawdaj/core/styles/app_text_styles.dart';
import 'package:hawdaj/core/utils/app_assets.dart';
import 'package:hawdaj/features/landmarks/presentation/view/widgets/landmark_type_bottom_sheet.dart';
import 'package:hawdaj/features/landmarks/presentation/manager/add_landmark_cubit/add_landmark_cubit.dart';
import 'dart:ui' as ui;

class AddLandmarkView extends StatefulWidget {
  const AddLandmarkView({super.key});

  @override
  State<AddLandmarkView> createState() => _AddLandmarkViewState();
}

class _AddLandmarkViewState extends State<AddLandmarkView> {
  final ImagePicker _picker = ImagePicker();

  @override
  void initState() {
    super.initState();
    context.read<AddLandmarkCubit>().init();
  }

  Future<void> _pickImages() async {
    final List<XFile> images = await _picker.pickMultiImage();
    if (images.isNotEmpty) {
      final files = images.map((xFile) => File(xFile.path)).toList();
      context.read<AddLandmarkCubit>().addImages(files);
    }
  }

  Future<void> _showCategorySelector() async {
    final cubit = context.read<AddLandmarkCubit>();
    final selectedCategory = await showLandmarkTypeBottomSheet(
      context: context,
      initialSelection: cubit.selectedCategory,
    );

    if (selectedCategory != null) {
      cubit.setSelectedCategory(selectedCategory);
    }
  }

  @override
  Widget build(BuildContext context) {
    final isRtl = Directionality.of(context) == ui.TextDirection.rtl;
    return BlocListener<AddLandmarkCubit, AddLandmarkState>(
      listener: (context, state) {
        if (state is AddLandmarkSuccess) {
          showCustomSuccessToast('landmark_added_success'.tr());

          Navigator.pop(context);
        } else if (state is AddLandmarkError) {
          showCustomFailureToast(state.message);
        }
      },
      child: Scaffold(
        backgroundColor: AppColors.white,
        //  appBar: normalAppBar("add_landmark".tr(), context),
        body: BlocBuilder<AddLandmarkCubit, AddLandmarkState>(
          builder: (context, state) {
            final cubit = context.read<AddLandmarkCubit>();

            return Column(
              children: [
                SizedBox(
                  height: 100.h,
                  child: Stack(
                    alignment: Alignment.center,
                    children: [
                      const AppBarGradientBackground(),

                      Positioned(
                        right: 16.w,
                        //  left: 16.w,
                        child: GestureDetector(
                          onTap: () {
                            Navigator.pop(context);
                          },
                          child: SvgPicture.asset(AppAssets.arrow),
                        ),
                      ),
                      Text(
                        'add_landmark'.tr(),
                        style: AppTextStyles.font24Regular.copyWith(),
                      ),

                      // Center(
                      //   child: SvgPicture.asset(
                      //     AppAssets.howdaj,
                      //     height: 36.h,
                      //     fit: BoxFit.contain,
                      //   ),
                      // ),
                    ],
                  ),
                ),

                Expanded(
                  child: SingleChildScrollView(
                    padding: EdgeInsets.all(16.r),
                    child: Column(
                      crossAxisAlignment: CrossAxisAlignment.start,
                      children: [
                        HeightSpace(24.h),

                        // Teacher Information Section
                        _buildSectionTitle("landmark_info".tr()),
                        HeightSpace(16.h),

                        // Teacher Type Field (Optional)
                        _buildCategoryField(cubit),
                        HeightSpace(16.h),

                        // Teacher Name Field
                        _buildTextField(
                          controller: cubit.nameController,
                          hint: 'landmark_name'.tr(),
                          trailingIconPath: AppAssets.text,
                        ),
                        HeightSpace(16.h),

                        // Teacher Description Field
                        _buildTextField(
                          controller: cubit.descriptionController,
                          hint: 'landmark_description'.tr(),
                          maxLines: 8,
                          trailingIconPath: AppAssets.infoGrey,
                        ),
                        HeightSpace(16.h),

                        // Teacher Address Field
                        _buildTextField(
                          controller: cubit.addressController,
                          hint: 'landmark_address'.tr(),
                          trailingIconPath: AppAssets.locationIcon,
                        ),
                        HeightSpace(24.h),

                        // Teacher Photos Section
                        _buildSectionTitle('landmark_photos'.tr()),
                        HeightSpace(16.h),

                        // File Upload Area
                        _buildFileUploadArea(cubit),
                        HeightSpace(32.h),

                        // Action Buttons
                        BlocBuilder<AddLandmarkCubit, AddLandmarkState>(
                          builder: (context, state) {
                            return Opacity(
                              opacity: state is AddLandmarkReady ? 1 : 0.5,
                              child: _buildActionButtons(cubit),
                            );
                          },
                        ),
                        HeightSpace(32.h), // Add bottom padding
                      ],
                    ),
                  ),
                ),
              ],
            );
          },
        ),
      ),
    );
  }

  Widget _buildSectionTitle(String title) {
    return Padding(
      padding: EdgeInsets.symmetric(horizontal: 16.w),
      child: Text(
        title,
        style: AppTextStyles.font16Bold.copyWith(
          color: AppColors.obsidianBlack,
        ),
      ),
    );
  }

  Widget _buildCategoryField(AddLandmarkCubit cubit) {
    return GestureDetector(
      child: _buildTextField(
        onTap: () => _showCategorySelector(),
        controller: cubit.categoryController,

        hint: 'landmark_type'.tr(),
        trailingIconPath: AppAssets.hashtag,
        leadingIconPath: AppAssets.arrowDown,
      ),
    );
  }

  Widget _buildTextField({
    required TextEditingController controller,
    required String hint,
    String? trailingIconPath,
    String? leadingIconPath,
    int? maxLines,
    Function()? onTap,
  }) {
    return Column(
      crossAxisAlignment: CrossAxisAlignment.start,
      children: [
        Text(
          hint,
          style: AppTextStyles.font14Medium.copyWith(
            color: AppColors.obsidianBlack,
          ),
        ),
        HeightSpace(8.h),
        GestureDetector(
          onTap: onTap,
          child: CustomTextField(
            controller: controller,
            hint: hint.tr(context: context),
            leadingIconPath: trailingIconPath,

            trailingIconPath: leadingIconPath,

            maxLines: maxLines ?? 1,
            //    onTap: onTap,
            height: maxLines != null ? null : 56.h,
          ),
        ),
      ],
    );
  }

  Widget _buildFileUploadArea(AddLandmarkCubit cubit) {
    return Padding(
      padding: EdgeInsets.symmetric(horizontal: 16.w),
      child: Column(
        children: [
          HeightSpace(16.h),

          // Upload Area
          BlocBuilder<AddLandmarkCubit, AddLandmarkState>(
            builder: (context, state) {
              final imageCount = state is AddLandmarkReady
                  ? state.imageCount
                  : 0;
              print(
                'BlocBuilder rebuild - State image count: $imageCount, Cubit count: ${cubit.selectedImages.length}',
              );
              return Container(
                width: double.infinity,
                height: imageCount > 0 ? 300.h : 120.h,
                padding: EdgeInsets.all(12.w),
                alignment: Alignment.center,
                decoration: BoxDecoration(
                  // color: AppColors.inactive3,
                  borderRadius: BorderRadius.circular(12.r),
                  border: Border.all(color: AppColors.inactive2, width: 1),
                ),
                child: imageCount > 0
                    ? _buildSelectedImagesGrid(cubit)
                    : _buildEmptyUploadArea(),
              );
            },
          ),
        ],
      ),
    );
  }

  Widget _buildActionButtons(AddLandmarkCubit cubit) {
    return Padding(
      padding: EdgeInsets.symmetric(horizontal: 16.w),
      child: Row(
        children: [
          // Save Button
          // SizedBox(
          //   width: double.infinity,
          //   child: Material(
          //     color: Colors.transparent,
          //     child: InkWell(
          //       onTap: () => cubit.createLandmark(),
          //       borderRadius: BorderRadius.circular(16.r),
          //       child: Container(
          //         padding: EdgeInsets.symmetric(vertical: 13.h),
          //         decoration: BoxDecoration(
          //           color: AppColors.primary,
          //           borderRadius: BorderRadius.circular(16.r),
          //         ),
          //         child: Center(
          //           child: Text(
          //             'save'.tr(),
          //             style: AppTextStyles.font14Medium.copyWith(
          //               color: Colors.white,
          //               fontWeight: FontWeight.w500,
          //             ),
          //           ),
          //         ),
          //       ),
          //     ),
          //   ),
          // ),
          HeightSpace(16.h),

          // Cancel Button
          // SizedBox(
          //   width: double.infinity,
          //   child: Material(
          //     color: Colors.transparent,
          //     child: InkWell(
          //       onTap: () => Navigator.pop(context),
          //       borderRadius: BorderRadius.circular(16.r),
          //       child: Container(
          //         padding: EdgeInsets.symmetric(vertical: 13.h),
          //         decoration: BoxDecoration(
          //           color: Colors.transparent,
          //           borderRadius: BorderRadius.circular(16.r),
          //           border: Border.all(color: AppColors.inactive2, width: 1),
          //         ),
          //         child: Center(
          //           child: Text(
          //             'cancel'.tr(),
          //             style: AppTextStyles.font14Medium.copyWith(
          //               color: AppColors.uiBlack,
          //               fontWeight: FontWeight.w500,
          //             ),
          //           ),
          //         ),
          //       ),
          //     ),
          //   ),
          // ),
          Expanded(
            child: PrimaryButton(
              title: 'save'.tr(),
              onTap: () => cubit.createLandmark(),
            ),
          ),
          WidthSpace(16.w),
          Expanded(
            child: SecondaryButton(
              title: 'cancel'.tr(),
              onTap: () => Navigator.pop(context),
            ),
          ),
        ],
      ),
    );
  }

  Widget _buildEmptyUploadArea() {
    return Column(
      mainAxisSize: MainAxisSize.min,
      children: [
        Row(
          children: [
            Icon(
              Icons.cloud_upload_outlined,
              // color: AppColors.primary,
              size: 24.sp,
            ),

            SizedBox(width: 8.w),
            Expanded(
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  GenericText(
                    "choose_file".tr(),
                    color: Colors.black87,
                    size: 16,
                    weight: FontWeight.w600,
                  ),
                  SizedBox(height: 4.h),
                  GenericText(
                    "upload_limit".tr(),
                    color: Colors.grey[600],
                    size: 12.sp,
                  ),
                ],
              ),
            ),
            SizedBox(width: 8.w),

            SizedBox(
              // width: double.infinity,
              child: GestureDetector(
                onTap: _pickImages,
                child: Container(
                  padding: const EdgeInsets.symmetric(
                    horizontal: 16,
                    vertical: 8,
                  ),
                  decoration: ShapeDecoration(
                    shape: RoundedRectangleBorder(
                      side: BorderSide(
                        width: 1,
                        color: const Color(0xFFEEF2F6) /* Color-Neutrals-100 */,
                      ),
                      borderRadius: BorderRadius.circular(8),
                    ),
                  ),
                  child: Row(
                    mainAxisSize: MainAxisSize.min,
                    mainAxisAlignment: MainAxisAlignment.center,
                    crossAxisAlignment: CrossAxisAlignment.center,
                    spacing: 4,
                    children: [
                      Text(
                        'browse_file'.tr(),
                        style: TextStyle(
                          color: Colors.black /* Color-Neutrals-Black */,
                          fontSize: 14.sp,
                          fontWeight: FontWeight.w400,
                        ),
                      ),
                    ],
                  ),
                ),
              ),
            ),
          ],
        ),
      ],
    );
  }

  Widget _buildSelectedImagesGrid(AddLandmarkCubit cubit) {
    return BlocBuilder<AddLandmarkCubit, AddLandmarkState>(
      builder: (context, state) {
        final imageCount = state is AddLandmarkReady ? state.imageCount : 0;
        return Padding(
          padding: EdgeInsets.all(16.w),
          child: Column(
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
              _buildEmptyUploadArea(),
              HeightSpace(16.h),
              Row(
                mainAxisAlignment: MainAxisAlignment.spaceBetween,
                children: [
                  Text(
                    "selected_images".tr() + '($imageCount)',
                    style: AppTextStyles.font14Medium.copyWith(
                      color: AppColors.obsidianBlack,
                    ),
                  ),
                  GestureDetector(
                    onTap: cubit.clearImages,
                    child: Text(
                      'clear_all'.tr(),
                      style: AppTextStyles.font12Regular.copyWith(
                        color: AppColors.primary,
                      ),
                    ),
                  ),
                ],
              ),
              HeightSpace(12.h),
              Expanded(
                child: GridView.builder(
                  gridDelegate: SliverGridDelegateWithFixedCrossAxisCount(
                    crossAxisCount: 3,
                    crossAxisSpacing: 8.w,
                    mainAxisSpacing: 8.h,
                  ),
                  itemCount: cubit.selectedImages.length,
                  itemBuilder: (context, index) {
                    return _buildImageItem(cubit, index);
                  },
                ),
              ),
            ],
          ),
        );
      },
    );
  }

  Widget _buildImageItem(AddLandmarkCubit cubit, int index) {
    return Stack(
      children: [
        Container(
          decoration: BoxDecoration(
            borderRadius: BorderRadius.circular(8.r),
            border: Border.all(color: AppColors.inactive2, width: 1),
          ),
          child: ClipRRect(
            borderRadius: BorderRadius.circular(8.r),
            child: Image.file(
              cubit.selectedImages[index],
              width: double.infinity,
              height: double.infinity,
              fit: BoxFit.cover,
            ),
          ),
        ),
        Positioned(
          top: 4.h,
          right: 4.w,
          child: GestureDetector(
            onTap: () => cubit.removeImage(index),
            child: Container(
              width: 20.w,
              height: 20.h,
              decoration: BoxDecoration(
                color: Colors.red,
                shape: BoxShape.circle,
              ),
              child: Icon(Icons.close, size: 14.sp, color: Colors.white),
            ),
          ),
        ),
      ],
    );
  }
}
