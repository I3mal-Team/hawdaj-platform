import 'package:flutter/material.dart';
import 'package:hawdaj/core/components/bottom_sheet/show_login_required_bottom_sheet.dart';
import 'package:hawdaj/core/utils/auth_manager.dart';

Future<bool> shouldExecute({
  required BuildContext context,
  required VoidCallback callback,
  bool checkLogged = true,
  bool checkSelectedPatient = true,
}) async {
  var user = await AuthManager.getUser();
  if (user == null && checkLogged) {
    showLoginRequiredBottomSheet(context);
    return false;
  }

  callback();
  return true;
}
