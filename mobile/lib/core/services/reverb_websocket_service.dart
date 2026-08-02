// import 'dart:async';
// import 'dart:convert';
// import 'dart:developer';

// import 'package:web_socket_channel/web_socket_channel.dart';
// import 'package:web_socket_channel/status.dart' as status;
// import 'package:hawdaj/core/config/websocket_config.dart';
// import 'package:hawdaj/core/utils/auth_manager.dart';
// import 'package:hawdaj/features/notifications/data/models/notification_model.dart';
// import 'package:dio/dio.dart';

// class ReverbWebSocketService {
//   // Reconnection logic
//   int _reconnectAttempts = 0;
//   DateTime? _lastConnectAttempt;
//   Timer? _reconnectTimer;

//   static final ReverbWebSocketService _instance =
//       ReverbWebSocketService._internal();
//   factory ReverbWebSocketService() => _instance;
//   ReverbWebSocketService._internal();

//   WebSocketChannel? _channel;
//   bool _isConnected = false;
//   bool _isInitialized = false;
//   StreamSubscription? _messageSubscription;

//   // Stream controllers for notifications
//   StreamController<NotificationModel> _notificationController =
//       StreamController<NotificationModel>.broadcast();
//   StreamController<Map<String, dynamic>> _chatMessageController =
//       StreamController<Map<String, dynamic>>.broadcast();
//   StreamController<void> _agoraEndSessionController =
//       StreamController<void>.broadcast();

//   // Public streams
//   Stream<NotificationModel> get notificationStream =>
//       _notificationController.stream;
//   Stream<Map<String, dynamic>> get chatMessageStream =>
//       _chatMessageController.stream;
//   Stream<void> get agoraEndSessionStream => _agoraEndSessionController.stream;

//   // Reverb configuration - using WebSocketConfig
//   static const String _key = WebSocketConfig.reverbKey;
//   static const String _host = WebSocketConfig.reverbHost;

//   // Store socketId after connection established
//   String? _socketId;

//   // Channel subscriptions
//   final Set<String> _subscribedChannels = <String>{};

//   /// Initialize WebSocket connection
//   Future<void> initialize() async {
//     if (_isInitialized) return;

//     try {
//       _isInitialized = true;
//       log('REVERB - Reverb WebSocket service initialized');
//     } catch (e) {
//       log('REVERB - Failed to initialize Reverb WebSocket service: $e');
//       rethrow;
//     }
//   }

//   void _scheduleReconnect() {
//     if (_reconnectTimer != null && _reconnectTimer!.isActive) return;
//     _reconnectAttempts++;
//     final delay = Duration(seconds: (_reconnectAttempts * 2).clamp(2, 30));
//     log(
//       'REVERB - 🔄 Scheduling reconnect in ${delay.inSeconds} seconds (attempt: $_reconnectAttempts)',
//     );
//     _reconnectTimer = Timer(delay, () {
//       connect();
//     });
//   }

//   /// Connect to WebSocket
//   Future<void> connect() async {
//     if (!_isInitialized) {
//       await initialize();
//     }

//     // Prevent rapid reconnect attempts
//     final now = DateTime.now();
//     if (_lastConnectAttempt != null &&
//         now.difference(_lastConnectAttempt!).inSeconds < 3) {
//       log('REVERB - ⏳ Skipping rapid reconnect attempt');
//       return;
//     }
//     _lastConnectAttempt = now;

//     if (_isConnected) return;

//     try {
//       // Ensure stream controllers are available
//       _ensureStreamControllers();

//       // Get authentication token
//       final token = await AuthManager.getToken();
//       // Standard Reverb WebSocket URL format
//       final wsUrl = token != null
//           ? 'wss://$_host/app/$_key?protocol=7&client=js&version=7.0.3&auth_token=$token'
//           : 'wss://$_host/app/$_key?protocol=7&client=js&version=7.0.3';

//       _channel = WebSocketChannel.connect(Uri.parse(wsUrl));
//       _messageSubscription = _channel!.stream.listen(
//         _handleMessage,
//         onError: (error) {
//           log('REVERB - WebSocket error: $error');
//           _isConnected = false;
//           _scheduleReconnect();
//         },
//         onDone: () {
//           log('REVERB - WebSocket connection closed');
//           _isConnected = false;
//           _scheduleReconnect();
//         },
//       );
//       _isConnected = true;
//       _reconnectAttempts = 0;
//       log('REVERB - ✅ Connected to $wsUrl');
//     } catch (e) {
//       log('REVERB - ❌ Failed to connect Reverb WebSocket: $e');
//       if (e.toString().contains('Failed host lookup')) {
//         log('REVERB - 🔍 DNS resolution failed. Check if $_host is reachable.');
//       }
//       _scheduleReconnect();
//       rethrow;
//     }
//   }

//   /// Subscribe to notification channels based on user role and ID
//   Future<void> subscribeToNotificationChannels() async {
//     if (!_isConnected) {
//       await connect();
//     }

//     try {
//       final user = await AuthManager.getUser();
//       if (user == null) {
//         throw Exception('No user found');
//       }

//       final userId = user.id.toString();

//       // Subscribe to user-specific notification channels
//       await _subscribeToChannel('notification.patient.$userId');
//       await _subscribeToChannel('notification.parentinfo.$userId');

//       log('REVERB - Subscribed to notification channels for user: $userId');
//     } catch (e) {
//       log('REVERB - Failed to subscribe to notification channels: $e');
//       rethrow;
//     }
//   }

//   /// Subscribe to a specific chat channel for real-time messages
//   Future<void> subscribeToChatChannelWithName(String channelName) async {
//     if (!_isConnected) {
//       await connect();
//     }
//     final fullChannelName = 'private-conversation.$channelName';

//     try {
//       // Subscribe to the specific chat channel
//       await _subscribeToChannel(fullChannelName);
//       log('REVERB - Subscribed to specific chat channel: $fullChannelName');
//     } catch (e) {
//       log('REVERB - Failed to subscribe to chat channel $fullChannelName: $e');
//       rethrow;
//     }
//   }

//   /// Subscribe to a specific channel
//   Future<void> _subscribeToChannel(String channelName) async {
//     try {
//       if (!_isConnected) {
//         await connect();
//       }
//       if (_subscribedChannels.contains(channelName)) {
//         log('REVERB - Already subscribed to channel: $channelName');
//         return;
//       }

//       // Ensure stream controllers are ready
//       _ensureStreamControllers();

//       await _authorizeChannel(channelName);
//       _subscribedChannels.add(channelName);
//     } catch (e) {
//       log('REVERB - Failed to subscribe to channel $channelName: $e');
//     }
//   }

//   Future<void> _authorizeChannel(String channelName) async {
//     try {
//       final socketId = await _getSocketId();
//       final token = await AuthManager.getToken();
//       var data = {'channel_name': channelName, 'socket_id': socketId};
//       var form = FormData.fromMap(data);
//       final response = await Dio().post(
//         'https://${WebSocketConfig.reverbHost}/api/broadcasting/auth',
//         options: Options(
//           headers: {
//             'Content-Type': 'application/json',
//             'Authorization': 'Bearer $token',
//           },
//         ),
//         data: form,
//       );
//       if (response.statusCode == 200) {
//         final authData = response.data is String
//             ? jsonDecode(response.data)
//             : response.data;
//         final authMessage = {
//           'event': 'pusher:subscribe',
//           'data': {
//             'channel': channelName,
//             'auth': authData['auth'],
//             if (authData['channel_data'] != null)
//               'channel_data': authData['channel_data'],
//           },
//         };
//         _channel?.sink.add(jsonEncode(authMessage));
//         log('REVERB - Authorized and subscribed to $channelName');
//       } else {
//         log(
//           'REVERB - ❌ Auth failed for $channelName: ${response.statusCode} ${response.data}',
//         );
//         throw Exception('Auth failed');
//       }
//     } catch (e) {
//       log('REVERB - ❌ Failed to authorize channel $channelName: $e');
//       rethrow;
//     }
//   }

//   Future<String> _getSocketId() async {
//     if (_socketId != null) return _socketId!;
//     // Wait for pusher:connection_established event
//     final completer = Completer<String>();
//     Timer.periodic(const Duration(milliseconds: 50), (timer) {
//       if (_socketId != null) {
//         completer.complete(_socketId!);
//         timer.cancel();
//       }
//     });

//     return completer.future;
//   }

//   /// Handle incoming WebSocket messages
//   void _handleMessage(dynamic message) {
//     try {
//       final data = jsonDecode(message.toString());
//       final event = data['event'];
//       final channel = data['channel'];
//       final eventData = data['data'];
//       log('REVERB - Received message on channel $channel: event=$event');
//       if (event == 'pusher:connection_established') {
//         final payload = jsonDecode(eventData);
//         _socketId = payload['socket_id'];
//         log('REVERB - WebSocket connection established, socket_id=$_socketId');
//       }
//       // Handle different event types
//       switch (event) {
//         case 'notification.event':
//           _handleNotificationEvent(eventData);
//           break;
//         case 'ConversationMessageSent':
//         case 'App\\Events\\ConversationMessageSent':
//           _handleChatMessageEvent(eventData);
//           break;
//         case 'AgoraEndSession':
//           _handleAgoraEndSessionEvent(eventData);
//           break;
//         case 'pusher:subscription_succeeded':
//           log('REVERB - Successfully subscribed to channel: $channel');
//           break;
//         default:
//           log('REVERB - Unhandled event: $event');
//       }
//     } catch (e) {
//       log('REVERB - Failed to handle message: $e');
//     }
//   }

//   /// Unsubscribe from a channel
//   Future<void> unsubscribeFromChannel(String channelName) async {
//     try {
//       // Remove from subscribed channels
//       _subscribedChannels.remove(channelName);

//       // Send unsubscribe message
//       final unsubscribeMessage = {
//         'event': 'pusher:unsubscribe',
//         'data': {'channel': channelName},
//       };

//       _channel?.sink.add(jsonEncode(unsubscribeMessage));
//       log('REVERB - Unsubscribed from channel: $channelName');
//     } catch (e) {
//       log('REVERB - Failed to unsubscribe from channel $channelName: $e');
//     }
//   }

//   /// Disconnect WebSocket
//   Future<void> disconnect() async {
//     try {
//       await _messageSubscription?.cancel();
//       await _channel?.sink.close(status.goingAway);
//       _channel = null;
//       _messageSubscription = null;
//       _subscribedChannels.clear();
//       _isConnected = false;
//       _socketId = null;

//       // Reset stream controllers to allow new subscriptions
//       _resetStreamControllers();

//       log('REVERB - Reverb WebSocket disconnected');
//     } catch (e) {
//       log('REVERB - Failed to disconnect Reverb WebSocket: $e');
//     }
//   }

//   /// Ensure stream controllers are available and not closed
//   void _ensureStreamControllers() {
//     if (_notificationController.isClosed) {
//       _notificationController = StreamController<NotificationModel>.broadcast();
//     }
//     if (_chatMessageController.isClosed) {
//       _chatMessageController =
//           StreamController<Map<String, dynamic>>.broadcast();
//     }
//     if (_agoraEndSessionController.isClosed) {
//       _agoraEndSessionController = StreamController<void>.broadcast();
//     }
//   }

//   /// Reset stream controllers to allow new subscriptions
//   void _resetStreamControllers() {
//     try {
//       // Close existing controllers
//       if (!_notificationController.isClosed) {
//         _notificationController.close();
//       }
//       if (!_chatMessageController.isClosed) {
//         _chatMessageController.close();
//       }
//       if (!_agoraEndSessionController.isClosed) {
//         _agoraEndSessionController.close();
//       }

//       // Create new controllers
//       _notificationController = StreamController<NotificationModel>.broadcast();
//       _chatMessageController =
//           StreamController<Map<String, dynamic>>.broadcast();
//       _agoraEndSessionController = StreamController<void>.broadcast();

//       log('REVERB - Stream controllers reset');
//     } catch (e) {
//       log('REVERB - Error resetting stream controllers: $e');
//     }
//   }

//   /// Dispose the service
//   void dispose() {
//     if (!_notificationController.isClosed) {
//       _notificationController.close();
//     }
//     if (!_chatMessageController.isClosed) {
//       _chatMessageController.close();
//     }
//     if (!_agoraEndSessionController.isClosed) {
//       _agoraEndSessionController.close();
//     }
//     disconnect();
//   }

//   /// Test WebSocket connection
//   Future<bool> testConnection() async {
//     try {
//       if (!_isInitialized) {
//         await initialize();
//       }

//       if (!_isConnected) {
//         await connect();
//       }

//       log(
//         'REVERB - Reverb WebSocket connection test: ${_isConnected ? 'SUCCESS' : 'FAILED'}',
//       );
//       return _isConnected;
//     } catch (e) {
//       log('REVERB - Reverb WebSocket connection test failed: $e');
//       return false;
//     }
//   }

//   // Event handlers for specific event types
//   void _handleNotificationEvent(dynamic eventData) {
//     try {
//       // Convert to Map if needed
//       Map<String, dynamic> data = eventData is Map<String, dynamic>
//           ? eventData
//           : jsonDecode(eventData.toString());

//       // Create notification model from event data
//       final notification = NotificationModel.fromJson(data);

//       // Emit notification to stream
//       _notificationController.add(notification);

//       log('REVERB - Notification event processed: ${notification.title}');
//     } catch (e) {
//       log('REVERB - Failed to process notification event: $e');
//     }
//   }

//   /// Send AgoraEndSession event
//   Future<void> sendAgoraEndSession({String? channelName}) async {
//     // try {
//     //   if (!_isConnected) {
//     //     log('REVERB - Cannot send AgoraEndSession: WebSocket not connected');
//     //     return;
//     //   }

//     //   final message = {
//     //     'event': 'AgoraEndSession',
//     //     'data': {
//     //       'timestamp': DateTime.now().toIso8601String(),
//     //       if (channelName != null) 'channel': channelName,
//     //     },
//     //   };

//     //   _channel?.sink.add(jsonEncode(message));
//     //   log('REVERB - AgoraEndSession event sent');
//     // } catch (e) {
//     //   log('REVERB - Failed to send AgoraEndSession event: $e');
//     // }
//   }

//   void _handleChatMessageEvent(dynamic eventData) {
//     try {
//       // Convert to Map if needed
//       Map<String, dynamic> data = eventData is Map<String, dynamic>
//           ? eventData
//           : jsonDecode(eventData.toString());

//       // Emit chat message to stream
//       _chatMessageController.add(data);

//       log('REVERB - Chat message event processed');
//     } catch (e) {
//       log('REVERB - Failed to process chat message event: $e');
//     }
//   }

//   void _handleAgoraEndSessionEvent(dynamic eventData) {
//     try {
//       // Emit agora end session event to stream
//       _agoraEndSessionController.add(null);

//       // Agora end session logging disabled
//       // log('REVERB - Agora end session event processed');
//     } catch (e) {
//       // Agora end session error logging disabled
//       // log('REVERB - Failed to process agora end session event: $e');
//     }
//   }

//   // Getters
//   bool get isConnected => _isConnected;
//   bool get isInitialized => _isInitialized;
// }
