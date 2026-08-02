import 'package:flutter/material.dart';
import 'package:go_router/go_router.dart';

/// A reusable invisible debug button for switching API environments
/// Positioned in the top-right corner of the screen
/// Requires 5 clicks within 1 second to activate
/// Completely invisible - no visual feedback until activation
class EnvironmentSwitcherFloatingButton extends StatefulWidget {
  /// The route to navigate to the environment switcher screen
  final String environmentSwitcherRoute;

  /// Custom position for the button (optional)
  final Positioned? customPosition;

  /// Whether to show the button (default: true in debug mode, false in release)
  final bool? showButton;

  const EnvironmentSwitcherFloatingButton({
    super.key,
    required this.environmentSwitcherRoute,
    this.customPosition,
    this.showButton,
  });

  @override
  State<EnvironmentSwitcherFloatingButton> createState() =>
      _EnvironmentSwitcherFloatingButtonState();
}

class _EnvironmentSwitcherFloatingButtonState
    extends State<EnvironmentSwitcherFloatingButton> {
  int _tapCount = 0;
  DateTime? _lastTapTime;
  static const int requiredTaps = 5; // Changed from 4 to 5
  static const int tapTimeoutSeconds = 1;

  bool get _shouldShowButton {
    if (widget.showButton != null) return widget.showButton!;
    // Show in debug mode by default
    return true; // You can change this to kDebugMode if needed
  }

  void _onTap() {
    final now = DateTime.now();

    // Reset tap count if timeout exceeded
    if (_lastTapTime != null &&
        now.difference(_lastTapTime!).inSeconds > tapTimeoutSeconds) {
      _tapCount = 0;
    }

    _tapCount++;
    _lastTapTime = now;

    // Don't show any visual feedback until all 5 clicks are completed
    // Only navigate when we reach the required taps
    if (_tapCount >= requiredTaps) {
      _tapCount = 0;
      _navigateToEnvironmentSwitcher();
    }
  }

  void _navigateToEnvironmentSwitcher() async {
    await context.push(widget.environmentSwitcherRoute);
    // Refresh the button state when returning from environment switcher
    if (mounted) {
      setState(() {});
    }
  }

  @override
  Widget build(BuildContext context) {
    if (!_shouldShowButton) return const SizedBox.shrink();

    final button = GestureDetector(
      onTap: _onTap,
      child: Container(
        width: 100,
        height: 100,
        decoration: BoxDecoration(
          color: Colors.transparent, // Always invisible
          borderRadius: BorderRadius.circular(50),
        ),
        child: Center(
          child: Icon(
            Icons.touch_app,
            color: Colors.transparent, // Always invisible
            size: 24,
          ),
        ),
      ),
    );

    if (widget.customPosition != null) {
      return Positioned(
        top: widget.customPosition!.top,
        left: widget.customPosition!.left,
        right: widget.customPosition!.right,
        bottom: widget.customPosition!.bottom,
        child: button,
      );
    }

    return Positioned(
      top: 0, // Move to top-right corner
      right: 0,
      child: button,
    );
  }
}
