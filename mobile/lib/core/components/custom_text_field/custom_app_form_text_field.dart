// ignore_for_file: prefer_const_constructors, prefer_const_literals_to_create_immutables, library_private_types_in_public_api

import 'package:hawdaj/core/components/custom_text_field/custom_text_field_actual_field.dart';
import 'package:hawdaj/core/components/custom_text_field/custom_text_field_container.dart';
import 'package:hawdaj/core/components/custom_text_field/custom_text_field_leading.dart';
import 'package:hawdaj/core/components/custom_text_field/custom_text_field_trailing.dart';
import 'package:hawdaj/core/components/custom_text_field/phone_number_input_formatter.dart';
import 'package:hawdaj/core/components/spaces.dart';
import 'package:hawdaj/core/utils/app_fonts.dart';
import 'package:flutter/material.dart';
import 'package:flutter/scheduler.dart';
import 'package:flutter/services.dart';
import 'package:flutter_screenutil/flutter_screenutil.dart';

class CustomTextField extends StatefulWidget {
  final String? hint;
  final bool password;
  final TextEditingController? controller;
  final String? initialValue; // ✅ جديد
  final Widget? leading;
  final Widget? trailing;
  final TextStyle? style;
  final TextStyle? hintStyle;
  final bool allowUpperHint;
  final String? leadingIconPath;
  final String? trailingIconPath;
  final Color? svgColor;
  final bool enabled;
  final int? maxLines;
  final int? minLines;
  final List<TextInputFormatter>? inputFormatters;
  final TextInputType? inputType;
  final TextInputAction? inputAction;
  final bool isPhone;
  final Function(String v)? onChange;
  final Function()? onTap;
  final int? maxLength;
  final double? height;
  final EdgeInsetsGeometry? padding;
  final String? label;

  const CustomTextField({
    this.label,
    super.key,
    this.hint,
    this.password = false,
    this.svgColor,
    this.controller,
    this.initialValue, // ✅ جديد
    this.leading,
    this.trailing,
    this.style,
    this.hintStyle,
    this.allowUpperHint = false,
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
    this.onTap,
  });

  @override
  _CustomTextFieldState createState() => _CustomTextFieldState();
}

class _CustomTextFieldState extends State<CustomTextField> {
  final FocusNode _focusNode = FocusNode();
  final _hintKey = GlobalKey();
  final _containerKey = GlobalKey();

  // ✅ دعم initialValue مع/بدون controller خارجي
  late final bool _usingExternalController;
  late final TextEditingController _internalController;
  TextEditingController get _controller =>
      _usingExternalController ? widget.controller! : _internalController;

  double hintHeight = 0;
  double containerHeight = 0;
  String content = '';
  bool passwordShown = false;
  bool hintDown = true;
  double topSpace = 0;

  void togglePasswordShown() {
    setState(() {
      passwordShown = !passwordShown;
    });
  }

  void _initSize() {
    SchedulerBinding.instance.addPostFrameCallback((_) {
      hintHeight = _hintKey.currentContext?.size?.height ?? 0;
      containerHeight = _containerKey.currentContext?.size?.height ?? 0;
      topSpace = (containerHeight - hintHeight) / 2;
      hintDown = true;
      if (mounted) setState(() {});
    });
  }

  void _attachContentListener() {
    _controller.addListener(() {
      content = _controller.text;
      _onFocusChange();
    });
  }

  @override
  void initState() {
    super.initState();

    _usingExternalController = (widget.controller != null);

    // ✅ تهيئة الـ controller حسب الحالة
    if (_usingExternalController) {
      // لو في initialValue ومفيش نصّ في الكنترولر الخارجي نحطّه
      if (widget.initialValue != null && widget.controller!.text.isEmpty) {
        widget.controller!.text = widget.initialValue!;
      }
    } else {
      _internalController = TextEditingController(
        text: widget.initialValue ?? '',
      );
    }

    content = _controller.text;
    _attachContentListener();

    _onFocusChange();
    _focusNode.addListener(_onFocusChange);
    _initSize();
  }

  @override
  void didUpdateWidget(covariant CustomTextField oldWidget) {
    super.didUpdateWidget(oldWidget);
    if (widget.initialValue != oldWidget.initialValue &&
        widget.initialValue != null) {
      if (_controller.text != widget.initialValue) {
        _controller.text = widget.initialValue!;
        _onFocusChange();
      }
    }
  }

  void _onFocusChange() {
    Future.delayed(Duration.zero).then((_) {
      if (!mounted) return;
      if (_focusNode.hasFocus || content.isNotEmpty) {
        topSpace = 7.h;
        hintDown = false;
        setState(() {});
      } else if (content.isEmpty || !_focusNode.hasFocus) {
        _initSize();
      }
    });
  }

  @override
  void dispose() {
    _focusNode.removeListener(_onFocusChange);
    _focusNode.dispose();
    // ✅ متتهنش الـ external controller
    if (!_usingExternalController) {
      _internalController.dispose();
    }
    super.dispose();
  }

  TextInputType? get inputType {
    if (widget.inputType != null) return widget.inputType;
    if (widget.isPhone) return TextInputType.phone;
    if (widget.password && passwordShown) return TextInputType.visiblePassword;
    return null;
  }

  List<TextInputFormatter>? get inputFormatters {
    final formatters = <TextInputFormatter>[
      ...(widget.inputFormatters ?? const []),
    ];
    if (widget.isPhone) {
      formatters.add(PhoneNumberInputFormatter());
    }
    return formatters;
  }

  TextStyle get defaultHintStyle => TextStyle(
    color: const Color(0xFF9AA3B2),
    fontSize: 14.sp,
    fontFamily: AppFonts.brandoArabic,
    fontWeight: FontWeight.w500,
  );

  @override
  Widget build(BuildContext context) {
    final animationDuration = const Duration(milliseconds: 100);
    return Column(
      children: [
        if (widget.label != null)
          Row(
            children: [
              Text(
                widget.label!,
                style: TextStyle(
                  color: Colors.black,
                  fontSize: 14.sp,
                  fontFamily: AppFonts.brandoArabic,
                  fontWeight: FontWeight.w400,
                ),
              ),
            ],
          ),
        if (widget.label != null) HeightSpace(8.h),

        AbsorbPointer(
          absorbing: !widget.enabled,
          child: GestureDetector(
            onTap: () {
              if (_focusNode.hasFocus) {
                _focusNode.nextFocus();
              } else {
                _focusNode.requestFocus();
              }
              widget.onTap?.call();
            },
            child: CustomTextFieldContainer(
              maxLines: widget.maxLines,
              minLines: widget.minLines,
              animationDuration: animationDuration,
              containerKey: _containerKey,
              focusNode: _focusNode,
              height: widget.height,
              padding: widget.padding,
              child: Row(
                children: [
                  CustomTextFieldLeading(
                    leading: widget.leading,
                    leadingIconPath: widget.leadingIconPath,
                  ),
                  Expanded(
                    child: CustomTextFieldActualField(
                      inputType: inputType,
                      inputAction: widget.inputAction,
                      inputFormatters: inputFormatters,
                      maxLines: widget.maxLines,
                      minLines: widget.minLines,
                      containerHeight: containerHeight,
                      allowUpperHint: widget.allowUpperHint,
                      enabled: widget.enabled,
                      focusNode: _focusNode,
                      password: widget.password,
                      passwordShown: passwordShown,
                      animationDuration: animationDuration,
                      topSpace: topSpace,
                      hintDown: hintDown,
                      hintKey: _hintKey,
                      onChanged: (v) {
                        setState(() => content = v);
                        widget.onChange?.call(v);
                      },
                      style: widget.style,
                      hintStyle: widget.hintStyle ?? defaultHintStyle,
                      hint: widget.hint,
                      controller: _controller, // ✅ أهم تغيير
                      maxLength: widget.maxLength,
                    ),
                  ),
                  CustomTextFieldTrailing(
                    password: widget.password,
                    trailingIconPath: widget.trailingIconPath,
                    trailing: widget.trailing,
                    passwordShown: passwordShown,
                    togglePasswordShown: togglePasswordShown,
                  ),
                ],
              ),
            ),
          ),
        ),
      ],
    );
  }
}
