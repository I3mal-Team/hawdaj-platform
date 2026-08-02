import 'package:flutter/material.dart';
import 'package:flutter_bloc/flutter_bloc.dart';
import 'package:flutter_screenutil/flutter_screenutil.dart';
import 'package:hawdaj/core/routing/route_utils.dart';
import 'package:hawdaj/features/force_update/presentation/cubit/force_update_cubit.dart';
import 'package:hawdaj/features/force_update/presentation/widgets/force_update_dialog.dart';

class ForceUpdateIntermediateScreen extends StatefulWidget {
  final String nextPage;
  final Widget? child;
  const ForceUpdateIntermediateScreen({
    super.key,
    required this.nextPage,
    this.child,
  });

  @override
  State<ForceUpdateIntermediateScreen> createState() =>
      _ForceUpdateIntermediateScreenState();
}

class _ForceUpdateIntermediateScreenState
    extends State<ForceUpdateIntermediateScreen> {
  @override
  void initState() {
    super.initState();
    // Trigger the force update check
    context.read<ForceUpdateCubit>().checkForUpdate();
  }

  Future<void> _navigateToNextScreen() async {
    pushReplacement(widget.nextPage, context);
  }

  void _showUpdateDialog(ForceUpdateState state, bool isMandatory) {
    if (state is ForceUpdateRequired) {
      showDialog(
        context: context,
        barrierDismissible: false,
        builder: (_) => ForceUpdateDialog(
          currentVersion: state.currentVersion,
          latestVersion: state.latestVersion.version,
          isMandatory: true,
        ),
      );
    } else if (state is ForceUpdateAvailable) {
      showDialog(
        context: context,
        barrierDismissible: true,
        builder: (_) => ForceUpdateDialog(
          currentVersion: state.currentVersion,
          latestVersion: state.latestVersion.version,
          isMandatory: false,
          onRememberChoice: () {
            context.read<ForceUpdateCubit>().ignoreVersion(
              state.latestVersion.version,
            );
          },
        ),
      ).then((_) => _navigateToNextScreen());
    }
  }

  @override
  Widget build(BuildContext context) {
    return BlocListener<ForceUpdateCubit, ForceUpdateState>(
      listener: (context, state) {
        if (state is ForceUpdateRequired) {
          _showUpdateDialog(state, true);
        } else if (state is ForceUpdateAvailable) {
          _showUpdateDialog(state, false);
        } else if (state is ForceUpdateNotRequired ||
            state is ForceUpdateError) {
          _navigateToNextScreen();
        }
      },
      child:
          widget.child ??
          Scaffold(
            body: Center(
              child: CircularProgressIndicator(
                color: Theme.of(context).primaryColor,
                strokeWidth: 3.w,
              ),
            ),
          ),
    );
  }
}
