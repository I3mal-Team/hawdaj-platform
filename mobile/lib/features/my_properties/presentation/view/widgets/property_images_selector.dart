import 'dart:io';
import 'package:easy_localization/easy_localization.dart';
import 'package:flutter/material.dart';
import 'package:flutter_bloc/flutter_bloc.dart';
import 'package:flutter_screenutil/flutter_screenutil.dart';
import 'package:hawdaj/core/utils/app_assets.dart';
import 'package:hawdaj/features/my_properties/presentation/manager/add_property_form_cubit/add_property_form_cubit.dart';
import 'package:hawdaj/features/my_properties/presentation/view/widgets/upload_file_tile.dart';
import 'package:hawdaj/features/tour%D9%80guide/presentation/view/widgets/section_field.dart';
import 'package:image_picker/image_picker.dart';
import 'package:dotted_border/dotted_border.dart';

class PropertyImagesSelector extends StatelessWidget {
  const PropertyImagesSelector({super.key});

  Future<void> _showImageSourceDialog(
    BuildContext context,
    List<File> currentFiles,
  ) async {
    showModalBottomSheet(
      context: context,
      shape: const RoundedRectangleBorder(
        borderRadius: BorderRadius.vertical(top: Radius.circular(16)),
      ),
      builder: (_) {
        return SafeArea(
          child: Wrap(
            children: [
              ListTile(
                leading: const Icon(Icons.photo_library_outlined),
                title: Text('choose_from_gallery'.tr()),
                onTap: () async {
                  Navigator.pop(context);
                  final ImagePicker picker = ImagePicker();
                  final List<XFile>? pickedFiles = await picker
                      .pickMultiImage();
                  if (pickedFiles == null || pickedFiles.isEmpty) return;
                  final files = pickedFiles.map((e) => File(e.path)).toList();
                  context.read<AddPropertyFormCubit>().updateImages([
                    ...currentFiles,
                    ...files,
                  ]);
                },
              ),
              ListTile(
                leading: const Icon(Icons.camera_alt_outlined),
                title: Text('take_photo'.tr()),
                onTap: () async {
                  Navigator.pop(context);
                  final ImagePicker picker = ImagePicker();
                  final XFile? photo = await picker.pickImage(
                    source: ImageSource.camera,
                  );
                  if (photo == null) return;
                  context.read<AddPropertyFormCubit>().updateImages([
                    ...currentFiles,
                    File(photo.path),
                  ]);
                },
              ),
            ],
          ),
        );
      },
    );
  }

  @override
  Widget build(BuildContext context) {
    return BlocBuilder<AddPropertyFormCubit, AddPropertyFormState>(
      builder: (context, state) {
        final files = state.images ?? [];
        return SectionField(
          label: 'property_images_label'.tr(),
          child: Column(
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
              GestureDetector(
                onTap: () => _showImageSourceDialog(context, files),
                child: DottedBorder(
                  options: RectDottedBorderOptions(
                    dashPattern: [10, 5],
                    strokeWidth: 1,
                    padding: EdgeInsets.all(16.w),
                    color: const Color(0xFFEEF2F6),
                  ),
                  child: UploadFileTile(),
                ),
              ),
              SizedBox(height: 12.h),
              if (files.isNotEmpty)
                Wrap(
                  spacing: 12,
                  runSpacing: 12,
                  children: files.map((file) {
                    return Stack(
                      clipBehavior: Clip.none,
                      children: [
                        ClipRRect(
                          borderRadius: BorderRadius.circular(8.r),
                          child: Image.file(
                            file,
                            width: 100.w,
                            height: 100.w,
                            fit: BoxFit.cover,
                          ),
                        ),
                        Positioned(
                          top: 0,
                          left: 0,
                          child: GestureDetector(
                            onTap: () {
                              final updated = List<File>.from(files)
                                ..remove(file);
                              context.read<AddPropertyFormCubit>().updateImages(
                                updated,
                              );
                            },
                            child: Container(
                              padding: const EdgeInsets.all(4),
                              child: Image.asset(AppAssets.buttonsRemove),
                            ),
                          ),
                        ),
                      ],
                    );
                  }).toList(),
                )
              else
                const SizedBox.shrink(),
            ],
          ),
        );
      },
    );
  }
}
