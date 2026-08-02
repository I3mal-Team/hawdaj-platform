// import 'dart:convert';
// import 'dart:developer';
// import 'package:flutter_local_notifications/flutter_local_notifications.dart';
// import 'package:hawdaj/features/notifications/data/models/notification_model.dart';

// class LocalNotificationService {
//   static final LocalNotificationService _instance =
//       LocalNotificationService._internal();
//   factory LocalNotificationService() => _instance;
//   LocalNotificationService._internal();

//   final FlutterLocalNotificationsPlugin _flutterLocalNotificationsPlugin =
//       FlutterLocalNotificationsPlugin();

//   bool _isInitialized = false;

//   /// Initialize local notifications
//   Future<void> initialize() async {
//     if (_isInitialized) return;

//     try {
//       const AndroidInitializationSettings initializationSettingsAndroid =
//           AndroidInitializationSettings('@mipmap/ic_launcher');

//       const DarwinInitializationSettings initializationSettingsIOS =
//           DarwinInitializationSettings(
//             requestAlertPermission: true,
//             requestBadgePermission: true,
//             requestSoundPermission: true,
//             onDidReceiveLocalNotification: null,
//           );

//       const InitializationSettings initializationSettings =
//           InitializationSettings(
//             android: initializationSettingsAndroid,
//             iOS: initializationSettingsIOS,
//           );

//       await _flutterLocalNotificationsPlugin.initialize(
//         initializationSettings,
//         onDidReceiveNotificationResponse: _onDidReceiveNotificationResponse,
//       );

//       _isInitialized = true;
//       log('Local notification service initialized');
//     } catch (e) {
//       log('Failed to initialize local notification service: $e');
//       rethrow;
//     }
//   }

//   /// Request notification permissions
//   Future<bool> requestPermissions() async {
//     if (!_isInitialized) {
//       await initialize();
//     }

//     try {
//       // Request permissions for Android 13+
//       final AndroidFlutterLocalNotificationsPlugin? androidImplementation =
//           _flutterLocalNotificationsPlugin
//               .resolvePlatformSpecificImplementation<
//                 AndroidFlutterLocalNotificationsPlugin
//               >();

//       if (androidImplementation != null) {
//         final bool? grantedNotificationPermission = await androidImplementation
//             .requestNotificationsPermission();
//         if (grantedNotificationPermission != null &&
//             !grantedNotificationPermission) {
//           return false;
//         }
//       }

//       // Request permissions for iOS
//       final IOSFlutterLocalNotificationsPlugin? iosImplementation =
//           _flutterLocalNotificationsPlugin
//               .resolvePlatformSpecificImplementation<
//                 IOSFlutterLocalNotificationsPlugin
//               >();

//       if (iosImplementation != null) {
//         final bool? grantedPermission = await iosImplementation
//             .requestPermissions(alert: true, badge: true, sound: true);
//         if (grantedPermission != null && !grantedPermission) {
//           return false;
//         }
//       }

//       return true;
//     } catch (e) {
//       log('Failed to request notification permissions: $e');
//       return false;
//     }
//   }

//   /// Show a local notification
//   Future<void> showNotification({
//     required String title,
//     required String body,
//     String? payload,
//     int? id,
//   }) async {
//     if (!_isInitialized) {
//       await initialize();
//     }

//     try {
//       const AndroidNotificationDetails androidNotificationDetails =
//           AndroidNotificationDetails(
//             'avc_notifications',
//             'AVC Notifications',
//             channelDescription: 'Notifications for AVC app',
//             importance: Importance.high,
//             priority: Priority.high,
//             icon: '@mipmap/ic_launcher',
//             enableVibration: true,
//             playSound: true,
//           );

//       const DarwinNotificationDetails iosNotificationDetails =
//           DarwinNotificationDetails(
//             presentAlert: true,
//             presentBadge: true,
//             presentSound: true,
//           );

//       const NotificationDetails notificationDetails = NotificationDetails(
//         android: androidNotificationDetails,
//         iOS: iosNotificationDetails,
//       );

//       await _flutterLocalNotificationsPlugin.show(
//         id ?? DateTime.now().millisecondsSinceEpoch.remainder(100000),
//         title,
//         body,
//         notificationDetails,
//         payload: payload,
//       );

//       log('Local notification shown: $title');
//     } catch (e) {
//       log('Failed to show local notification: $e');
//     }
//   }

//   /// Show notification from NotificationModel
//   Future<void> showNotificationFromModel(NotificationModel notification) async {
//     final payload = jsonEncode({
//       'id': notification.id,
//       'type': notification.type,
//       'url': notification.url,
//     });

//     await showNotification(
//       title: notification.title ?? 'إشعار جديد',
//       body: notification.message ?? '',
//       payload: payload,
//       id: notification.id != null ? int.tryParse(notification.id!) : null,
//     );
//   }

//   /// Cancel a specific notification
//   Future<void> cancelNotification(int id) async {
//     try {
//       await _flutterLocalNotificationsPlugin.cancel(id);
//       log('Notification cancelled: $id');
//     } catch (e) {
//       log('Failed to cancel notification: $e');
//     }
//   }

//   /// Cancel all notifications
//   Future<void> cancelAllNotifications() async {
//     try {
//       await _flutterLocalNotificationsPlugin.cancelAll();
//       log('All notifications cancelled');
//     } catch (e) {
//       log('Failed to cancel all notifications: $e');
//     }
//   }

//   /// Handle notification tap
//   void _onDidReceiveNotificationResponse(NotificationResponse response) {
//     try {
//       final payload = response.payload;
//       if (payload != null) {
//         final data = jsonDecode(payload);
//         log('Notification tapped: $data');

//         // Handle notification tap based on type
//         _handleNotificationTap(data);
//       }
//     } catch (e) {
//       log('Failed to handle notification response: $e');
//     }
//   }

//   /// Handle notification tap navigation
//   void _handleNotificationTap(Map<String, dynamic> data) {
//     final type = data['type'] as String?;
//     final url = data['url'] as String?;

//     // TODO: Implement navigation based on notification type
//     // This can be customized based on your app's navigation requirements
//     switch (type) {
//       case 'chat':
//         // Navigate to chat screen
//         break;
//       case 'appointment':
//         // Navigate to appointment screen
//         break;
//       case 'general':
//         // Navigate to notifications screen
//         break;
//       default:
//         // Default navigation
//         break;
//     }

//     log('Handling notification tap for type: $type, url: $url');
//   }

//   /// Get pending notifications
//   Future<List<PendingNotificationRequest>> getPendingNotifications() async {
//     try {
//       return await _flutterLocalNotificationsPlugin
//           .pendingNotificationRequests();
//     } catch (e) {
//       log('Failed to get pending notifications: $e');
//       return [];
//     }
//   }

//   /// Check if notifications are enabled
//   Future<bool> areNotificationsEnabled() async {
//     try {
//       final AndroidFlutterLocalNotificationsPlugin? androidImplementation =
//           _flutterLocalNotificationsPlugin
//               .resolvePlatformSpecificImplementation<
//                 AndroidFlutterLocalNotificationsPlugin
//               >();

//       if (androidImplementation != null) {
//         final bool? enabled = await androidImplementation
//             .areNotificationsEnabled();
//         return enabled ?? false;
//       }

//       return true; // iOS doesn't have a direct way to check, assume enabled
//     } catch (e) {
//       log('Failed to check notification status: $e');
//       return false;
//     }
//   }

//   // Getters
//   bool get isInitialized => _isInitialized;
// }
