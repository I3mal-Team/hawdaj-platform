import 'dart:io';
import 'package:flutter/foundation.dart';
import 'package:flutter/material.dart';
import 'package:hawdaj/core/components/bottom_sheet/base_bottom_sheet.dart';
import 'package:hawdaj/core/components/bottom_sheet/camera_permission_bottom_sheet.dart';
import 'package:hawdaj/core/components/bottom_sheet/permission_settings_bottom_sheet.dart';
import 'package:image_picker/image_picker.dart';
import 'package:permission_handler/permission_handler.dart';

class CameraHelper {
  static final ImagePicker _picker = ImagePicker();

  /// Initialize camera permission (call this once to ensure permission appears in settings)
  static Future<void> initializeCameraPermission() async {
    try {
      // This ensures the permission is registered with the system
      // and will appear in device settings even if denied
      await Permission.camera.request();
    } catch (e) {
      if (kDebugMode) {
        print('Failed to initialize camera permission: $e');
      }
    }
  }

  /// Take photo with camera with proper permission handling
  static Future<File?> takePhotoWithCamera(BuildContext context) async {
    try {
      // First attempt: Try image_picker directly (this will request permission if needed)
      // This approach ensures the permission request is properly registered with the system
      final XFile? image = await _picker.pickImage(
        source: ImageSource.camera,
        maxWidth: 1024,
        maxHeight: 1024,
        imageQuality: 85,
        preferredCameraDevice: CameraDevice.front,
      );

      if (image != null) {
        return File(image.path);
      } else {
        // User cancelled or permission denied, check permission status
        final status = await Permission.camera.status;

        if (status.isPermanentlyDenied) {
          // Show settings bottom sheet if permanently denied
          await _showPermissionSettingsBottomSheet(context);
          throw CameraException('Camera permission permanently denied');
        } else if (status.isDenied) {
          // Show permission explanation and try again
          final shouldRetry = await _showCameraPermissionBottomSheet(context);
          if (shouldRetry) {
            // Request permission explicitly
            final newStatus = await Permission.camera.request();
            if (newStatus.isGranted) {
              // Try camera again
              return await _captureImage();
            } else if (newStatus.isPermanentlyDenied) {
              await _showPermissionSettingsBottomSheet(context);
              throw CameraException('Camera permission permanently denied');
            }
          }
          throw CameraException('Camera permission denied by user');
        } else {
          // User just cancelled
          return null;
        }
      }
    } catch (e) {
      if (kDebugMode) {
        print('Camera error: $e');
      }
      rethrow;
    }
  }

  /// Capture image from camera
  static Future<File?> _captureImage() async {
    final XFile? image = await _picker.pickImage(
      source: ImageSource.camera,
      maxWidth: 1024,
      maxHeight: 1024,
      imageQuality: 85,
      preferredCameraDevice: CameraDevice.front,
    );
    return image != null ? File(image.path) : null;
  }

  /// Show camera permission request bottom sheet
  static Future<bool> _showCameraPermissionBottomSheet(
    BuildContext context,
  ) async {
    final result = await baseBottomSheet<bool>(
      context: context,
      title: 'إذن الكاميرا',
      hideNavBar: true,
      child: CameraPermissionBottomSheet(
        onAllowPressed: () {
          Navigator.of(context).pop(true);
        },
        onCancelPressed: () {
          Navigator.of(context).pop(false);
        },
      ),
    );
    return result ?? false;
  }

  /// Show settings bottom sheet for permanently denied permissions
  static Future<void> _showPermissionSettingsBottomSheet(
    BuildContext context,
  ) async {
    await baseBottomSheet(
      context: context,
      title: 'إعدادات الأذونات',
      hideNavBar: true,
      child: PermissionSettingsBottomSheet(
        onOpenSettingsPressed: () {
          Navigator.of(context).pop();
          openAppSettings();
        },
        onCancelPressed: () {
          Navigator.of(context).pop();
        },
      ),
    );
  }

  /// Pick photo from gallery - image_picker handles permissions automatically
  static Future<File?> pickPhotoFromGallery() async {
    try {
      final XFile? image = await _picker.pickImage(
        source: ImageSource.gallery,
        maxWidth: 1024,
        maxHeight: 1024,
        imageQuality: 85,
      );

      return image != null ? File(image.path) : null;
    } catch (e) {
      if (kDebugMode) {
        print('Gallery error: $e');
      }
      rethrow;
    }
  }
}

/// Custom exception for camera related errors
class CameraException implements Exception {
  final String message;
  CameraException(this.message);

  @override
  String toString() => 'CameraException: $message';
}
