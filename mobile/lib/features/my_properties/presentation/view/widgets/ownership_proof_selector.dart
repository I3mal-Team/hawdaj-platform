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

class OwnershipProofSelector extends StatelessWidget {
  const OwnershipProofSelector({super.key});

  @override
  Widget build(BuildContext context) {
    return BlocBuilder<AddPropertyFormCubit, AddPropertyFormState>(
      builder: (context, state) {
        final File? file = state.ownershipProofFile;

        return SectionField(
          label: 'ownership_proof_label'.tr(),
          child: Column(
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
              GestureDetector(
                onTap: () async {
                  final ImagePicker picker = ImagePicker();
                  final XFile? picked = await picker.pickImage(
                    source: ImageSource.gallery,
                  );
                  if (picked == null) return;
                  final File selectedFile = File(picked.path);
                  context.read<AddPropertyFormCubit>().updateOwnershipProof(
                    selectedFile,
                  );
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
              if (file != null && file.path.isNotEmpty)
                Stack(
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
                          context
                              .read<AddPropertyFormCubit>()
                              .updateOwnershipProof(File(''));
                        },
                        child: Container(
                          padding: const EdgeInsets.all(4),
                          child: Image.asset(AppAssets.buttonsRemove),
                        ),
                      ),
                    ),
                  ],
                ),
            ],
          ),
        );
      },
    );
  }
}

// class OwnershipProofSelector extends StatefulWidget {
//   const OwnershipProofSelector({super.key});

//   @override
//   State<OwnershipProofSelector> createState() => _OwnershipProofSelectorState();
// }

// class _OwnershipProofSelectorState extends State<OwnershipProofSelector> {
//   File? _file;

//   final ImagePicker _picker = ImagePicker();

//   Future<void> _pickFile() async {
//     final XFile? picked = await _picker.pickImage(source: ImageSource.gallery);
//     if (picked == null) return;

//     final selectedFile = File(picked.path);
//     setState(() {
//       _file = selectedFile;
//     });

//     context.read<AddPropertyFormCubit>().updateOwnershipProof(selectedFile);
//   }

//   void _removeFile() {
//     setState(() {
//       _file = null;
//     });

//     context.read<AddPropertyFormCubit>().updateOwnershipProof(File(''));
//   }

//   @override
//   Widget build(BuildContext context) {
//     return SectionField(
//       label: 'ownership_proof_label'.tr(),
//       child: Column(
//         crossAxisAlignment: CrossAxisAlignment.start,
//         children: [
//           GestureDetector(
//             onTap: _pickFile,
//             child: DottedBorder(
//               options: RectDottedBorderOptions(
//                 dashPattern: [10, 5],
//                 strokeWidth: 1,
//                 padding: EdgeInsets.all(16.w),
//                 color: const Color(0xFFEEF2F6),
//               ),
//               child: UploadFileTile(),
//             ),
//           ),

//           SizedBox(height: 12.h),
//           if (_file != null)
//             Stack(
//               children: [
//                 ClipRRect(
//                   borderRadius: BorderRadius.circular(8.r),
//                   child: Image.file(
//                     _file!,
//                     width: 100.w,
//                     height: 100.w,
//                     fit: BoxFit.cover,
//                   ),
//                 ),
//                 Positioned(
//                   top: 0,
//                   left: 0,
//                   child: GestureDetector(
//                     onTap: _removeFile,
//                     child: Container(
//                       padding: const EdgeInsets.all(4),
//                       child: Image.asset(AppAssets.buttonsRemove),
//                     ),
//                   ),
//                 ),
//               ],
//             )
//           else
//             const Center(child: SizedBox()),
//         ],
//       ),
//     );
//   }
// }
