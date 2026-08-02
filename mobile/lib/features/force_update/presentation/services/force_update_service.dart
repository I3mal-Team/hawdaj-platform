// import 'package:flutter/material.dart';
// import 'package:flutter_bloc/flutter_bloc.dart';
// import 'package:hawdaj/core/routing/app_router.dart';
// import 'package:hawdaj/features/force_update/presentation/cubit/force_update_cubit.dart';
// import 'package:hawdaj/features/force_update/presentation/widgets/force_update_dialog.dart';

// class ForceUpdateService {
//   static bool _isCheckingUpdate = false;

//   /// Check for updates and show dialog if needed
//   static Future<void> checkAndShowUpdateDialog(BuildContext context) async {
//     print('🎯 [ForceUpdateService] checkAndShowUpdateDialog called');

//     // Prevent multiple simultaneous checks
//     if (_isCheckingUpdate) {
//       print('⏸️ [ForceUpdateService] Already checking, skipping...');
//       return;
//     }

//     _isCheckingUpdate = true;
//     print('🔓 [ForceUpdateService] Lock acquired, starting check');

//     try {
//       final cubit = context.read<ForceUpdateCubit>();
//       print('📦 [ForceUpdateService] Got ForceUpdateCubit from context');

//       await cubit.checkForUpdate();
//       print('✅ [ForceUpdateService] checkForUpdate completed');

//       if (!context.mounted) {
//         print('⚠️ [ForceUpdateService] Context not mounted, returning');
//         return;
//       }

//       final state = cubit.state;
//       print('📊 [ForceUpdateService] Current state: ${state.runtimeType}');

//       if (state is ForceUpdateRequired) {
//         print('🚨 [ForceUpdateService] Showing MANDATORY update dialog');
//         // Use the navigator key to get a context that has access to Navigator
//         final navigatorContext = parentKey.currentContext;
//         if (navigatorContext != null && navigatorContext.mounted) {
//           print('✅ [ForceUpdateService] Got Navigator context');
//           // Show non-dismissable dialog for mandatory update
//           await showDialog(
//             context: navigatorContext,
//             barrierDismissible: false,
//             builder: (dialogContext) => ForceUpdateDialog(
//               currentVersion: state.currentVersion,
//               latestVersion: state.latestVersion.version,
//               isMandatory: true,
//             ),
//           );
//           print('✅ [ForceUpdateService] Mandatory dialog dismissed');
//         } else {
//           print('❌ [ForceUpdateService] Navigator context not available');
//         }
//       } else if (state is ForceUpdateAvailable) {
//         print('💡 [ForceUpdateService] Showing OPTIONAL update dialog');
//         // Use the navigator key to get a context that has access to Navigator
//         final navigatorContext = parentKey.currentContext;
//         if (navigatorContext != null && navigatorContext.mounted) {
//           print('✅ [ForceUpdateService] Got Navigator context');
//           // Show dismissable dialog for optional update
//           await showDialog(
//             context: navigatorContext,
//             barrierDismissible: true,
//             builder: (dialogContext) => ForceUpdateDialog(
//               currentVersion: state.currentVersion,
//               latestVersion: state.latestVersion.version,
//               isMandatory: false,
//               onRememberChoice: () {
//                 print(
//                   '🙈 [ForceUpdateService] User chose to ignore version: ${state.latestVersion.version}',
//                 );
//                 // Remember user's choice to ignore this version
//                 cubit.ignoreVersion(state.latestVersion.version);
//               },
//             ),
//           );
//           print('✅ [ForceUpdateService] Optional dialog dismissed');
//         } else {
//           print('❌ [ForceUpdateService] Navigator context not available');
//         }
//       } else if (state is ForceUpdateNotRequired) {
//         print('✅ [ForceUpdateService] No update required');
//       } else if (state is ForceUpdateError) {
//         print('❌ [ForceUpdateService] Update check failed: ${state.message}');
//       } else {
//         print('❓ [ForceUpdateService] Unknown state: $state');
//       }
//     } catch (e) {
//       print('❌ [ForceUpdateService] Error checking for update: $e');
//       debugPrint('Error checking for update: $e');
//     } finally {
//       _isCheckingUpdate = false;
//       print('🔓 [ForceUpdateService] Lock released');
//     }
//   }
// }
