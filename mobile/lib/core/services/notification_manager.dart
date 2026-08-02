// import 'dart:async';
// import 'dart:developer';
// import 'package:flutter/material.dart';
// import 'package:flutter_bloc/flutter_bloc.dart';
// import 'package:hawdaj/core/services/local_notification_service.dart';
// import 'package:hawdaj/core/services/reverb_websocket_service.dart';
// import 'package:hawdaj/features/notifications/data/models/notification_model.dart';
// import 'package:hawdaj/features/notifications/presentation/manager/notifications_cubit/notifications_cubit.dart';

// class NotificationManager {
//   static final NotificationManager _instance = NotificationManager._internal();
//   factory NotificationManager() => _instance;
//   NotificationManager._internal();

//   // Services
//   final LocalNotificationService _localNotificationService =
//       LocalNotificationService();
//   final ReverbWebSocketService _webSocketService = ReverbWebSocketService();

//   // Streams
//   StreamSubscription<NotificationModel>? _notificationSubscription;
//   StreamSubscription<void>? _agoraEndSessionSubscription;

//   bool _isInitialized = false;
//   BuildContext? _context;

//   /// Initialize notification manager
//   Future<void> initialize(BuildContext context) async {
//     if (_isInitialized) return;

//     try {
//       _context = context;

//       // Initialize local notifications
//       await _localNotificationService.initialize();

//       // Request notification permissions
//       final permissionGranted = await _localNotificationService
//           .requestPermissions();
//       if (!permissionGranted) {
//         log('Notification permissions not granted');
//       }

//       _isInitialized = true;
//       log('Notification manager initialized');
//     } catch (e) {
//       log('Failed to initialize notification manager: $e');
//       rethrow;
//     }
//   }

//   /// Connect to WebSocket notifications
//   Future<void> connectToWebSocket() async {
//     try {
//       // Initialize and connect WebSocket service
//       await _webSocketService.initialize();
//       await _webSocketService.connect();

//       // Only subscribe to notification channels, NOT chat channels
//       // Chat channels are handled separately by individual conversation cubits
//       await _webSocketService.subscribeToNotificationChannels();

//       log('WebSocket connected successfully for notifications only');
//     } catch (e) {
//       log('Failed to connect to WebSocket: $e');
//     }
//   }

//   /// Start listening to notifications
//   Future<void> startListening() async {
//     if (!_isInitialized) {
//       throw Exception('Notification manager not initialized');
//     }

//     try {
//       await connectToWebSocket();

//       // Listen to notification events only
//       // Chat message handling is done by individual conversation cubits
//       _notificationSubscription = _webSocketService.notificationStream.listen(
//         _handleNotificationReceived,
//         onError: (error) {
//           log('Error in notification stream: $error');
//         },
//       );

//       // Listen to Agora end session events (global events)
//       _agoraEndSessionSubscription = _webSocketService.agoraEndSessionStream
//           .listen(
//             (_) => _handleAgoraEndSession(),
//             onError: (error) {
//               // Agora end session error logging disabled
//               // log('Error in agora end session stream: $error');
//             },
//           );

//       log('Started listening to notifications (not chat messages)');
//     } catch (e) {
//       log('Failed to start listening to notifications: $e');
//       rethrow;
//     }
//   }

//   /// Stop listening to notifications
//   Future<void> stopListening() async {
//     try {
//       await _notificationSubscription?.cancel();
//       await _agoraEndSessionSubscription?.cancel();

//       _notificationSubscription = null;
//       _agoraEndSessionSubscription = null;

//       log('Stopped listening to notifications');
//     } catch (e) {
//       log('Failed to stop listening to notifications: $e');
//     }
//   }

//   /// Handle received notification
//   void _handleNotificationReceived(NotificationModel notification) async {
//     try {
//       log('Received notification: ${notification.title}');

//       // Show local notification
//       await _localNotificationService.showNotificationFromModel(notification);

//       // Update notifications in the app
//       if (_context != null) {
//         final notificationsCubit = _context!.read<NotificationsCubit>();
//         notificationsCubit.refreshNotifications();
//       }

//       log('Notification handled successfully');
//     } catch (e) {
//       log('Failed to handle notification: $e');
//     }
//   }

//   /// Handle Agora end session event
//   /// Handle Agora end session events
//   Future<void> _handleAgoraEndSession() async {
//     try {
//       // Agora end session logging disabled
//       // log('Received Agora end session event');

//       // Show notification to user about session ending
//       await _localNotificationService.showNotification(
//         title: 'انتهت الجلسة',
//         body: 'تم إنهاء الجلسة من قبل الطبيب',
//         payload: '{"type": "session_ended"}',
//       );

//       // Agora end session success logging disabled
//       // log('Agora end session handled successfully');
//     } catch (e) {
//       // Agora end session error logging disabled
//       // log('Failed to handle agora end session: $e');
//     }
//   }

//   /// Show a test notification
//   Future<void> showTestNotification() async {
//     try {
//       await _localNotificationService.showNotification(
//         title: 'إشعار تجريبي',
//         body: 'هذا إشعار تجريبي للتأكد من عمل النظام',
//       );
//     } catch (e) {
//       log('Failed to show test notification: $e');
//     }
//   }

//   /// Disconnect from all services
//   Future<void> disconnect() async {
//     try {
//       await stopListening();

//       // Disconnect WebSocket when implemented
//       // await _webSocketService?.disconnect();

//       log('Notification manager disconnected');
//     } catch (e) {
//       log('Failed to disconnect notification manager: $e');
//     }
//   }

//   /// Dispose the manager
//   void dispose() {
//     disconnect();
//     _context = null;
//   }

//   // Getters
//   bool get isInitialized => _isInitialized;
//   LocalNotificationService get localNotificationService =>
//       _localNotificationService;
// }
