// ignore_for_file: invalid_use_of_visible_for_testing_member, depend_on_referenced_packages, implementation_imports, must_be_immutable

import 'dart:io';

import 'package:hawdaj/core/components/global_network_image.dart';
import 'package:hawdaj/core/styles/app_colors.dart';
import 'package:hawdaj/core/utils/functions/image_helper.dart';
import 'package:flutter/material.dart';
import 'package:flutter_svg/svg.dart';
import 'package:image_picker/image_picker.dart';

import '../utils/app_assets.dart';

class UserImage extends StatelessWidget {
  final File? pickedImage;
  String? imageLink;
  final Function(File pickedFile)? onPick;
  final double? radius;
  final String? placeHolderPath;
  final double? pickMaxWidth;
  final double? pickMaxHeight;
  final double? borderRadius;

  UserImage({
    super.key,
    this.pickedImage,
    required this.imageLink,
    this.onPick,
    this.radius,
    this.placeHolderPath,
    this.pickMaxWidth,
    this.pickMaxHeight,
    this.borderRadius,
  }) {
    if (imageLink?.isEmpty == true) {
      imageLink = null;
    }
  }

  void handlePickImage(Function(File pickedFile)? onPick) async {
    var res = await ImagePicker.platform.getImageFromSource(
      source: ImageSource.gallery,
      // options: ImagePickerOptions(
      //   maxHeight: pickMaxHeight ?? 96 * 1.5,
      //   maxWidth: pickMaxWidth ?? 96 * 1.5,
      // ),
    );
    if (res == null) return;
    File file = File(res.path);
    onPick!(file);
  }

  double placeholderWidth(double rad) {
    if (24 > rad) {
      return rad / 2;
    } else {
      return 24;
    }
  }

  @override
  Widget build(BuildContext context) {
    double rad = radius ?? 96;
    return SizedBox(
      width: radius,
      height: radius,
      child: GestureDetector(
        onTap: onPick == null ? null : () => handlePickImage(onPick),
        child: pickedImage != null
            ? ClipRRect(
                borderRadius: BorderRadius.circular(borderRadius ?? 16),
                child: Image.file(
                  pickedImage!,
                  width: double.infinity,
                  height: double.infinity,
                  fit: BoxFit.cover,
                ),
              )
            : imageLink == null || imageLink!.isEmpty
            ? Container(
                width: rad,
                height: rad,
                decoration: BoxDecoration(
                  borderRadius: BorderRadius.circular(16),
                  // border: Border.all(
                  //   width: 1,
                  //   // color: AppColors.mainGrey,
                  // ),
                ),
                child: Column(
                  mainAxisAlignment: MainAxisAlignment.center,
                  children: [
                    placeHolderPath == null
                        ? Image.asset(AppAssets.oldPerson, fit: BoxFit.cover)
                        : Image.asset(
                            placeHolderPath!,
                            width: placeholderWidth(rad),
                            height: placeholderWidth(rad),
                            color: AppColors.mainBlue,
                          ),
                  ],
                ),
              )
            : FilesHelper.isSvg(imageLink ?? '')
            ? ClipRRect(
                borderRadius: BorderRadius.circular(1000),
                child: SvgPicture.network(
                  imageLink ?? '',
                  width: double.infinity,
                  height: double.infinity,
                  fit: BoxFit.cover,
                ),
              )
            : ClipRRect(
                borderRadius: BorderRadius.circular(1000),
                child: GNImage(
                  imageLink!,
                  width: double.infinity,
                  height: double.infinity,
                  fit: BoxFit.cover,
                  cached: true,
                ),
              ),
      ),
    );
  }
}
