import 'package:flutter/material.dart';
import 'package:hawdaj/core/utils/auth_manager.dart';
import 'package:hawdaj/features/splash/presentation/views/splash_view.dart';
import 'package:hawdaj/features/home/presentation/view/home_view.dart';

class AuthWrapper extends StatelessWidget {
  const AuthWrapper({super.key});

  @override
  Widget build(BuildContext context) {
    return StreamBuilder<bool>(
      stream: AuthManager.authStream,
      builder: (context, snapshot) {
        // Show loading while checking auth state
        if (snapshot.connectionState == ConnectionState.waiting) {
          return const SplashView();
        }

        return FutureBuilder<bool>(
          future: AuthManager.isLoggedIn(),
          builder: (context, authSnapshot) {
            // Show loading while checking storage
            if (authSnapshot.connectionState == ConnectionState.waiting) {
              return const SplashView();
            }

            // Get the final auth state
            bool isAuthenticated = authSnapshot.data ?? false;

            // Navigate based on auth state
            if (isAuthenticated) {
              return const HomeView();
            } else {
              return const SplashView();
            }
          },
        );
      },
    );
  }
}
