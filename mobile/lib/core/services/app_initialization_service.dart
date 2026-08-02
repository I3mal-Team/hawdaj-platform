// import 'dart:developer';
// import 'package:flutter/material.dart';
// import 'package:hawdaj/core/services/service_locator.dart';
// import 'package:hawdaj/core/services/notification_manager.dart';
// import 'package:hawdaj/core/utils/auth_manager.dart';

// class AppInitializationService {
//   static final AppInitializationService _instance =
//       AppInitializationService._internal();
//   factory AppInitializationService() => _instance;
//   AppInitializationService._internal();

//   bool _isInitialized = false;

//   /// Initialize the app
//   Future<void> initialize(BuildContext context) async {
//     if (_isInitialized) return;

//     try {
//       log('Starting app initialization...');

//       // Initialize notification manager
//       final notificationManager = getIt<NotificationManager>();
//       await notificationManager.initialize(context);

//       // Check if user is logged in and start notification listening
//       if (await AuthManager.isLoggedIn()) {
//         // ignore: use_build_context_synchronously
//         await _startNotificationServices(context);
//       }

//       _isInitialized = true;
//       log('App initialization completed');
//     } catch (e) {
//       log('Failed to initialize app: $e');
//       rethrow;
//     }
//   }

//   /// Start notification services for logged-in users
//   Future<void> startNotificationServices(BuildContext context) async {
//     try {
//       await _startNotificationServices(context);
//     } catch (e) {
//       log('Failed to start notification services: $e');
//     }
//   }

//   /// Stop notification services (on logout)
//   Future<void> stopNotificationServices() async {
//     try {
//       final notificationManager = getIt<NotificationManager>();
//       await notificationManager.stopListening();
//       log('Notification services stopped');
//     } catch (e) {
//       log('Failed to stop notification services: $e');
//     }
//   }

//   /// Internal method to start notification services
//   Future<void> _startNotificationServices(BuildContext context) async {
//     try {
//       final notificationManager = getIt<NotificationManager>();

//       // Ensure notification manager is initialized
//       if (!notificationManager.isInitialized) {
//         await notificationManager.initialize(context);
//       }

//       // Start listening to notifications
//       await notificationManager.startListening();

//       log('Notification services started');
//     } catch (e) {
//       log('Failed to start notification services: $e');
//       rethrow;
//     }
//   }

//   /// Show a test notification
//   Future<void> showTestNotification() async {
//     try {
//       final notificationManager = getIt<NotificationManager>();
//       await notificationManager.showTestNotification();
//     } catch (e) {
//       log('Failed to show test notification: $e');
//     }
//   }

//   // Getters
//   bool get isInitialized => _isInitialized;
// }
