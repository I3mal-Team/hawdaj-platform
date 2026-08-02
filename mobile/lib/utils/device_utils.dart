//** this is just form Amr hassan to use
//** don't modify this in any way
import 'dart:io';

import 'package:device_info_plus/device_info_plus.dart';
import 'package:flutter/material.dart';

String? _deviceId;
List<String> _amrDevices = [
  'RSR1.201211.001',
  'TKQ1.221013.002',
  "TE1A.240213.009",
  'BP22.250325.006'
];
bool get amrDevice {
  if (_amrDevices.contains(_deviceId)) {
    return true;
  } else {
    return false;
  }
}

void amrDeviceCallBack(VoidCallback callback) {
  if (amrDevice) {
    callback();
  }
}

void initDeviceId() async {
  _deviceId = await _getId();
}

Future<String?> _getId() async {
  var deviceInfo = DeviceInfoPlugin();
  if (Platform.isIOS) {
    // import 'dart:io'
    var iosDeviceInfo = await deviceInfo.iosInfo;
    return iosDeviceInfo.identifierForVendor; // unique ID on iOS
  } else if (Platform.isAndroid) {
    var androidDeviceInfo = await deviceInfo.androidInfo;
    return androidDeviceInfo.id; // unique ID on Android
  }
  return null;
}
