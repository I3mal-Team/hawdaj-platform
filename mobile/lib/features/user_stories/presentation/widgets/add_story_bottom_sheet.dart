import 'dart:io';
import 'package:easy_localization/easy_localization.dart';
import 'package:flutter/material.dart';
import 'package:flutter_screenutil/flutter_screenutil.dart';
import 'package:flutter_svg/flutter_svg.dart';
import 'package:hawdaj/core/components/custom_failure_toast.dart';
import 'package:hawdaj/core/components/custom_success_toast.dart';
import 'package:hawdaj/core/components/custom_text_field/custom_app_form_text_field.dart';
import 'package:hawdaj/core/utils/app_assets.dart';
import 'package:image_picker/image_picker.dart';
import 'package:hawdaj/core/components/text_components/generic_text.dart';
import 'package:hawdaj/core/styles/app_colors.dart';
import 'package:hawdaj/features/user_stories/presentation/cubit/add_story_cubit.dart';
import 'package:hawdaj/features/user_stories/presentation/cubit/add_story_state.dart';
import 'package:flutter_bloc/flutter_bloc.dart';

class AddStoryBottomSheet extends StatefulWidget {
  const AddStoryBottomSheet({super.key});

  @override
  State<AddStoryBottomSheet> createState() => _AddStoryBottomSheetState();
}

class _AddStoryBottomSheetState extends State<AddStoryBottomSheet> {
  final TextEditingController _titleController = TextEditingController();
  File? _selectedFile;
  String? _selectedFileType;
  final ImagePicker _picker = ImagePicker();

  @override
  void dispose() {
    _titleController.dispose();
    super.dispose();
  }

  Future<void> _pickImage() async {
    final XFile? image = await _picker.pickImage(
      source: ImageSource.gallery,
      maxWidth: 1920,
      maxHeight: 1080,
      imageQuality: 85,
    );

    if (image != null) {
      setState(() {
        _selectedFile = File(image.path);
        _selectedFileType = 'image';
      });
    }
  }

  Future<void> _pickVideo() async {
    final XFile? video = await _picker.pickVideo(
      source: ImageSource.gallery,
      maxDuration: const Duration(minutes: 5),
    );

    if (video != null) {
      setState(() {
        _selectedFile = File(video.path);
        _selectedFileType = 'video';
      });
    }
  }

  void _showMediaPicker() {
    showModalBottomSheet(
      context: context,
      builder: (context) => Container(
        padding: EdgeInsets.all(16.w),
        child: Column(
          mainAxisSize: MainAxisSize.min,
          children: [
            ListTile(
              leading: const Icon(Icons.photo_library),
              title: Text("choose_image".tr()),
              onTap: () {
                Navigator.pop(context);
                _pickImage();
              },
            ),
            // ListTile(
            //   leading: const Icon(Icons.video_library),
            //   title: Text("choose_video".tr()),
            //   onTap: () {
            //     Navigator.pop(context);
            //     _pickVideo();
            //   },
            // ),
          ],
        ),
      ),
    );
  }

  void _removeSelectedFile() {
    setState(() {
      _selectedFile = null;
      _selectedFileType = null;
    });
  }

  void _submitStory() {
    // if (_titleController.text.trim().isEmpty) {
    //   ScaffoldMessenger.of(
    //     context,
    //   ).showSnackBar(const SnackBar(content: Text('يرجى إدخال عنوان القصة')));
    //   return;
    // }

    if (_selectedFile == null) {
      showCustomFailureToast('please_select_media'.tr());

      return;
    }

    // Validate file type
    final fileExtension = _selectedFile!.path.split('.').last.toLowerCase();
    final allowedExtensions = [
      'jpg',
      'jpeg',
      'png',
      'gif',
      'svg',
      'webm',
      'mp4',
      'mov',
      'avi',
    ];

    if (!allowedExtensions.contains(fileExtension)) {
      showCustomFailureToast("unsupported_file_type".tr());

      return;
    }

    // Validate file size (50MB = 50 * 1024 * 1024 bytes)
    final fileSize = _selectedFile!.lengthSync();
    final maxSize = 50 * 1024 * 1024; // 50MB in bytes

    if (fileSize > maxSize) {
      showCustomFailureToast("file_too_large".tr());

      return;
    }

    context.read<AddStoryCubit>().addStory(
      title: _titleController.text.trim(),
      file: _selectedFile!,
      fileType: _selectedFileType!,
    );
  }

  @override
  Widget build(BuildContext context) {
    return Container(
      height: MediaQuery.of(context).size.height * 0.60,
      decoration: const BoxDecoration(
        color: Colors.white,
        borderRadius: BorderRadius.vertical(top: Radius.circular(20)),
      ),
      padding: EdgeInsets.symmetric(horizontal: 16.r),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          // Drag indicator
          Container(
            margin: EdgeInsets.only(top: 12.h),
            width: 80.w,
            height: 4.h,
            decoration: BoxDecoration(
              color: const Color(0xFFF5F7F8),
              borderRadius: BorderRadius.circular(2.r),
            ),
          ),

          // Header
          Row(
            children: [
              Center(
                child: GenericText(
                  'add_story_title'.tr(),
                  color: AppColors.primary,
                  size: 20,
                  weight: FontWeight.bold,
                ),
              ),
            ],
          ),

          Expanded(
            child: SingleChildScrollView(
              padding: EdgeInsets.symmetric(horizontal: 16.w),
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  // Story Title Input
                  GenericText(
                    "story_title_label".tr(),
                    color: Colors.black87,
                    size: 16,
                    weight: FontWeight.w600,
                  ),
                  SizedBox(height: 8.h),
                  CustomTextField(
                    controller: _titleController,
                    hint: "story_title_label".tr(),
                    leadingIconPath: AppAssets.text,
                    maxLines: 1,
                  ),

                  SizedBox(height: 24.h),

                  // Media Upload Section
                  Container(
                    padding: EdgeInsets.all(16.r),
                    decoration: BoxDecoration(
                      color: Colors.white,
                      borderRadius: BorderRadius.circular(12.r),
                      border: Border.all(
                        color: Colors.grey[300]!,
                        style: BorderStyle.solid,
                        width: 2.w,
                      ),
                    ),
                    child: Column(
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
                                    "choose_media_label".tr(),
                                    textAlign: TextAlign.start,
                                    color: Colors.black87,
                                    size: 16,
                                    weight: FontWeight.w600,
                                  ),
                                  SizedBox(height: 4.h),
                                  GenericText(
                                    "choose_media_sub".tr(),
                                    textAlign: TextAlign.start,
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
                                onTap: _showMediaPicker,
                                child: Container(
                                  padding: const EdgeInsets.symmetric(
                                    horizontal: 16,
                                    vertical: 8,
                                  ),
                                  decoration: ShapeDecoration(
                                    shape: RoundedRectangleBorder(
                                      side: BorderSide(
                                        width: 1,
                                        color: const Color(
                                          0xFFEEF2F6,
                                        ) /* Color-Neutrals-100 */,
                                      ),
                                      borderRadius: BorderRadius.circular(8),
                                    ),
                                  ),
                                  child: Row(
                                    mainAxisSize: MainAxisSize.min,
                                    mainAxisAlignment: MainAxisAlignment.center,
                                    crossAxisAlignment:
                                        CrossAxisAlignment.center,
                                    spacing: 4,
                                    children: [
                                      Text(
                                        "browse_file".tr(),
                                        style: TextStyle(
                                          color: Colors
                                              .black /* Color-Neutrals-Black */,
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
                    ),
                  ),

                  SizedBox(height: 16.h),

                  // Media Preview
                  if (_selectedFile != null) ...[
                    Container(
                      width: 120.w,
                      height: 120.w,
                      decoration: BoxDecoration(
                        borderRadius: BorderRadius.circular(12.r),
                        border: Border.all(color: Colors.grey[300]!),
                      ),
                      child: Stack(
                        children: [
                          ClipRRect(
                            borderRadius: BorderRadius.circular(12.r),
                            child: _selectedFileType == 'video'
                                ? Container(
                                    color: Colors.black,
                                    child: const Center(
                                      child: Icon(
                                        Icons.play_circle_outline,
                                        color: Colors.white,
                                        size: 40,
                                      ),
                                    ),
                                  )
                                : Image.file(
                                    _selectedFile!,
                                    width: double.infinity,
                                    height: double.infinity,
                                    fit: BoxFit.cover,
                                  ),
                          ),
                          Positioned(
                            top: 8.h,
                            left: 8.w,
                            child: GestureDetector(
                              onTap: _removeSelectedFile,
                              child: Container(
                                padding: const EdgeInsets.all(4),
                                decoration: ShapeDecoration(
                                  color: const Color(
                                    0xFFFEE4E2,
                                  ) /* Color-Error-100 */,
                                  shape: RoundedRectangleBorder(
                                    borderRadius: BorderRadius.circular(4),
                                  ),
                                ),
                                child: SvgPicture.asset(AppAssets.trash),
                              ),
                            ),
                          ),
                        ],
                      ),
                    ),
                  ],

                  SizedBox(height: 32.h),
                ],
              ),
            ),
          ),

          // Action Buttons
          Padding(
            padding: EdgeInsets.all(16.w),
            child: Row(
              children: [
                Expanded(
                  child: BlocConsumer<AddStoryCubit, AddStoryState>(
                    listener: (context, state) {
                      if (state is AddStorySuccess) {
                        Navigator.pop(
                          context,
                          true,
                        ); // Return true to indicate success

                        showCustomSuccessToast('story_added_success'.tr());
                      } else if (state is AddStoryError) {
                        showCustomFailureToast(state.message);
                      }
                    },
                    builder: (context, state) {
                      return ElevatedButton(
                        onPressed: state is AddStoryLoading
                            ? null
                            : _submitStory,
                        style: ElevatedButton.styleFrom(
                          backgroundColor: AppColors.primary,
                          foregroundColor: Colors.white,
                          shape: RoundedRectangleBorder(
                            borderRadius: BorderRadius.circular(12.r),
                          ),
                          padding: EdgeInsets.symmetric(vertical: 16.h),
                        ),
                        child: state is AddStoryLoading
                            ? SizedBox(
                                width: 20.w,
                                height: 20.w,
                                child: const CircularProgressIndicator(
                                  strokeWidth: 2,
                                  valueColor: AlwaysStoppedAnimation<Color>(
                                    Colors.white,
                                  ),
                                ),
                              )
                            : Text('add_button'.tr()),
                      );
                    },
                  ),
                ),
                SizedBox(width: 12.w),

                Expanded(
                  child: ElevatedButton(
                    onPressed: () => Navigator.pop(context),
                    style: ElevatedButton.styleFrom(
                      backgroundColor: Colors.grey[200],
                      foregroundColor: Colors.black87,
                      shape: RoundedRectangleBorder(
                        borderRadius: BorderRadius.circular(12.r),
                      ),
                      padding: EdgeInsets.symmetric(vertical: 16.h),
                    ),
                    child: Text('cancel'.tr()),
                  ),
                ),
              ],
            ),
          ),
        ],
      ),
    );
  }
}
