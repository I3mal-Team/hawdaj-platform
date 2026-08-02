import 'package:cached_network_image/cached_network_image.dart';
import 'package:flutter/material.dart';
import 'package:flutter_bloc/flutter_bloc.dart';
import 'package:flutter_screenutil/flutter_screenutil.dart';
import 'package:flutter_svg/svg.dart';
import 'package:go_router/go_router.dart';
import 'package:hawdaj/core/components/custom_failure_toast.dart';
import 'package:hawdaj/core/components/custom_success_toast.dart';
import 'package:hawdaj/core/databases/api/end_points.dart';
import 'package:hawdaj/core/routing/route_utils.dart';
import 'package:hawdaj/core/routing/routes_keys.dart';
import 'package:hawdaj/core/utils/app_assets.dart';
import 'package:hawdaj/core/utils/auth_manager.dart';
import 'package:hawdaj/core/utils/should_execute.dart';
import 'package:hawdaj/features/auth/presentation/manager/cubits/logout_cubit/logout_cubit.dart';

class AppBarTopRow extends StatelessWidget {
  const AppBarTopRow({super.key});

  @override
  Widget build(BuildContext context) {
    return BlocListener<LogoutCubit, LogoutState>(
      listener: (context, state) {
        if (state is LogoutSuccess) {
          showCustomSuccessToast(state.message);

          // Navigate to login after successful logout
          context.go(RoutesKeys.kLoginView);
        } else if (state is LogoutError) {
          showCustomFailureToast(state.message);
        }
      },
      child: Row(
        mainAxisAlignment: MainAxisAlignment.spaceBetween,
        children: [
          StreamBuilder<bool>(
            stream: AuthManager.authStream,
            initialData: true,
            builder: (context, authSnapshot) {
              return FutureBuilder(
                future: AuthManager.getUser(),
                builder: (context, snapshot) {
                  if (snapshot.connectionState == ConnectionState.waiting) {
                    return SizedBox(
                      width: 40.w,
                      height: 40.w,
                      child: const CircularProgressIndicator(strokeWidth: 2),
                    );
                  }

                  if (snapshot.hasError) {
                    return const Icon(Icons.error, color: Colors.red);
                  }

                  final user = snapshot.data;

                  String? imageUrl;
                  if (user != null &&
                      user.photo != null &&
                      user.photo!.isNotEmpty) {
                    // 👇 check prefix
                    if (user.photo!.startsWith("http")) {
                      imageUrl = user.photo!;
                    } else {
                      imageUrl = "${user.photo!}";
                    }
                  }

                  return GestureDetector(
                    onTap: () {
                      shouldExecute(
                        context: context,
                        callback: () {
                          push(RoutesKeys.kProfileView, context);
                        },
                      );
                    },
                    child: Container(
                      width: 40.w,
                      height: 40.w,
                      decoration: BoxDecoration(shape: BoxShape.circle),
                      clipBehavior: Clip.antiAlias,
                      child: imageUrl != null
                          ? CachedNetworkImage(
                              imageUrl: imageUrl,
                              fit: BoxFit.cover,
                              placeholder: (context, url) => Center(
                                child: SizedBox(
                                  width: 20.w,
                                  height: 20.w,
                                  child: const CircularProgressIndicator(
                                    strokeWidth: 2,
                                  ),
                                ),
                              ),
                              errorWidget: (context, url, error) => Image.asset(
                                AppAssets.user,
                                fit: BoxFit.cover,
                              ),
                            )
                          : Image.asset(AppAssets.user, fit: BoxFit.cover),
                    ),
                  );
                },
              );
            },
          ),

          GestureDetector(
            onTap: () {},
            child: SvgPicture.asset(
              AppAssets.howdaj,
              height: 36.h,
              fit: BoxFit.contain,
            ),
          ),
          //Notification
          SizedBox(width: 40.w, height: 40.w),
        ],
      ),
    );
  }

  void _showLogoutDialog(BuildContext context) {
    showDialog(
      context: context,
      builder: (BuildContext context) {
        return AlertDialog(
          title: Text('تسجيل الخروج'),
          content: Text('هل أنت متأكد من أنك تريد تسجيل الخروج؟'),
          actions: [
            TextButton(
              onPressed: () {
                Navigator.of(context).pop();
              },
              child: Text('إلغاء'),
            ),

            BlocBuilder<LogoutCubit, LogoutState>(
              builder: (context, state) {
                return TextButton(
                  onPressed: state is LogoutLoading
                      ? null
                      : () {
                          Navigator.of(context).pop();
                          context.read<LogoutCubit>().logout();
                        },
                  child: state is LogoutLoading
                      ? SizedBox(
                          width: 20,
                          height: 20,
                          child: CircularProgressIndicator(strokeWidth: 2),
                        )
                      : Text('تسجيل الخروج'),
                );
              },
            ),
          ],
        );
      },
    );
  }
}
