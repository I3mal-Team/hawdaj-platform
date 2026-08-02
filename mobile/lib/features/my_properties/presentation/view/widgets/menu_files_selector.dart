import 'dart:io';
import 'package:dotted_border/dotted_border.dart';
import 'package:easy_localization/easy_localization.dart';
import 'package:flutter/material.dart';
import 'package:flutter_bloc/flutter_bloc.dart';
import 'package:flutter_screenutil/flutter_screenutil.dart';
import 'package:hawdaj/core/utils/app_assets.dart';
import 'package:hawdaj/features/my_properties/presentation/manager/add_property_form_cubit/add_property_form_cubit.dart';
import 'package:hawdaj/features/my_properties/presentation/view/widgets/upload_file_tile.dart';
import 'package:hawdaj/features/tour%D9%80guide/presentation/view/widgets/section_field.dart';
import 'package:image_picker/image_picker.dart';

class MenuFilesSelector extends StatelessWidget {
  const MenuFilesSelector({super.key});

  @override
  Widget build(BuildContext context) {
    return BlocBuilder<AddPropertyFormCubit, AddPropertyFormState>(
      builder: (context, state) {
        final List<File> files = state.menuFile ?? [];

        return SectionField(
          label: 'menu_files_label'.tr(),
          child: Column(
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
              GestureDetector(
                onTap: () async {
                  final ImagePicker picker = ImagePicker();
                  final List<XFile>? pickedFiles = await picker
                      .pickMultiImage();
                  if (pickedFiles == null || pickedFiles.isEmpty) return;
                  final newFiles = pickedFiles
                      .map((e) => File(e.path))
                      .toList();
                  context.read<AddPropertyFormCubit>().updateMenuFiles([
                    ...files,
                    ...newFiles,
                  ]);
                },
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
                              final updatedFiles = List<File>.from(files)
                                ..remove(file);
                              context
                                  .read<AddPropertyFormCubit>()
                                  .updateMenuFiles(updatedFiles);
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
                ),
            ],
          ),
        );
      },
    );
  }
}
