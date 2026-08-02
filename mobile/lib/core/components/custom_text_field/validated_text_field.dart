import 'package:flutter/material.dart';
import 'package:flutter/services.dart';
import 'package:flutter_screenutil/flutter_screenutil.dart';
import 'package:hawdaj/core/components/custom_text_field/custom_app_form_text_field.dart';
import 'package:hawdaj/core/utils/app_fonts.dart';

class ValidatedTextField extends FormField<String> {
  final TextEditingController? controller;
  final String? hint;
  final bool password;
  final Widget? leading;
  final Widget? trailing;
  final TextStyle? style;
  final TextStyle? hintStyle;
  final bool allowUpperHint;
  final String? leadingIconPath;
  final String? trailingIconPath;
  @override
  final bool enabled;
  final int? maxLines;
  final int? minLines;
  final List<TextInputFormatter>? inputFormatters;
  final TextInputType? inputType;
  final TextInputAction? inputAction;
  final bool isPhone;
  final Function(String v)? onChange;
  final int? maxLength;
  final double? height;
  final EdgeInsetsGeometry? padding;

  ValidatedTextField({
    super.key,
    this.controller,
    this.hint,
    this.password = false,
    this.leading,
    this.trailing,
    this.style,
    this.hintStyle,
    this.allowUpperHint = true,
    this.leadingIconPath,
    this.enabled = true,
    this.trailingIconPath,
    this.maxLines,
    this.minLines,
    this.inputFormatters,
    this.inputType,
    this.inputAction,
    this.isPhone = false,
    this.onChange,
    this.maxLength,
    this.height,
    this.padding,
    super.validator,
    super.initialValue,
    super.autovalidateMode,
  }) : super(
         builder: (FormFieldState<String> state) {
           return Column(
             crossAxisAlignment: CrossAxisAlignment.start,
             children: [
               CustomTextField(
                 controller: controller,
                 hint: hint,
                 password: password,
                 leading: leading,
                 trailing: trailing,
                 style: style,
                 hintStyle: hintStyle,
                 allowUpperHint: allowUpperHint,
                 leadingIconPath: leadingIconPath,
                 enabled: enabled,
                 trailingIconPath: trailingIconPath,
                 maxLines: maxLines,
                 minLines: minLines,
                 inputFormatters: inputFormatters,
                 inputType: inputType,
                 inputAction: inputAction,
                 isPhone: isPhone,
                 onChange: (value) {
                   state.didChange(value);
                   onChange?.call(value);
                 },
                 maxLength: maxLength,
                 height: height,
                 padding: padding,
               ),
               if (state.hasError) ...[
                 SizedBox(height: 4.h),
                 Padding(
                   padding: EdgeInsets.only(right: 8.w),
                   child: Text(
                     state.errorText!,
                     style: TextStyle(
                       color: Colors.red,
                       fontSize: 12.sp,
                       fontFamily: AppFonts.brandoArabic,
                       fontWeight: FontWeight.w400,
                     ),
                   ),
                 ),
               ],
             ],
           );
         },
       );
}
