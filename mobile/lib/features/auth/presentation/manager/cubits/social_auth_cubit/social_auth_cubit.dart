import 'package:flutter_bloc/flutter_bloc.dart';
import 'package:hawdaj/features/auth/data/models/user_model.dart';
import 'package:hawdaj/features/auth/domain/repositories/auth_repository.dart';
import 'package:hawdaj/features/auth/services/social_auth_service.dart';
import 'package:hawdaj/core/utils/auth_manager.dart';
import 'package:equatable/equatable.dart';

part 'social_auth_state.dart';

class SocialAuthCubit extends Cubit<SocialAuthState> {
  final AuthRepository repository;
  final SocialAuthService socialAuthService;

  SocialAuthCubit(this.repository, this.socialAuthService)
    : super(SocialAuthInitial());

  /// Sign in with Google
  Future<void> signInWithGoogle() async {
    emit(SocialAuthLoading());

    try {
      // Get Google authentication
      final googleAuth = await socialAuthService.signInWithGoogle();

      if (googleAuth == null) {
        // User cancelled the sign-in
        emit(SocialAuthInitial());
        return;
      }

      // Create social auth request
      final socialAuthRequest = socialAuthService.createGoogleAuthRequest(
        googleAuth,
      );

      // Call backend API
      final authResponse = await repository.googleLogin(socialAuthRequest);

      // Check if login was successful
      if (authResponse.code == 200 && authResponse.data != null) {
        // Save user and token to secure storage
        await AuthManager.saveUser(
          authResponse.data!.user,
          authResponse.data!.token,
        );

        emit(
          SocialAuthSuccess(
            user: authResponse.data!.user,
            message: authResponse.message,
          ),
        );
      } else {
        emit(SocialAuthError(authResponse.message));
      }
    } catch (e) {
      // Sign out from Google on error
      await socialAuthService.signOutGoogle();

      // Provide more specific error messages
      String errorMessage;
      if (e.toString().contains('ID token')) {
        errorMessage =
            'فشل في الحصول على معلومات التحقق من Google. يرجى المحاولة مرة أخرى.';
      } else if (e.toString().contains('provider ID')) {
        errorMessage =
            'فشل في التحقق من هوية المستخدم. يرجى المحاولة مرة أخرى.';
      } else if (e.toString().contains('422')) {
        errorMessage =
            'بيانات تسجيل الدخول غير مكتملة. يرجى المحاولة مرة أخرى.';
      } else {
        errorMessage = 'فشل تسجيل الدخول بواسطة Google: ${e.toString()}';
      }

      emit(SocialAuthError(errorMessage));
    }
  }

  /// Sign in with Apple
  Future<void> signInWithApple() async {
    emit(SocialAuthLoading());

    try {
      // Get Apple authentication
      final appleAuth = await socialAuthService.signInWithApple();

      if (appleAuth == null) {
        // User cancelled the sign-in
        emit(SocialAuthInitial());
        return;
      }

      // Create social auth request
      final socialAuthRequest = socialAuthService.createAppleAuthRequest(
        appleAuth,
      );

      // Call backend API
      final authResponse = await repository.appleLogin(socialAuthRequest);

      // Check if login was successful
      if (authResponse.code == 200 && authResponse.data != null) {
        // Save user and token to secure storage
        await AuthManager.saveUser(
          authResponse.data!.user,
          authResponse.data!.token,
        );

        emit(
          SocialAuthSuccess(
            user: authResponse.data!.user,
            message: authResponse.message,
          ),
        );
      } else {
        emit(SocialAuthError(authResponse.message));
      }
    } catch (e) {
      // Sign out from Apple on error
      await socialAuthService.signOutApple();
      emit(SocialAuthError('فشل تسجيل الدخول بواسطة Apple: ${e.toString()}'));
    }
  }

  /// Sign in with Twitter
  Future<void> signInWithTwitter() async {
    emit(SocialAuthLoading());

    try {
      // Get Twitter authentication
      final twitterAuth = await socialAuthService.signInWithTwitter();

      if (twitterAuth == null) {
        // User cancelled the sign-in
        emit(SocialAuthInitial());
        return;
      }

      // Create social auth request
      final socialAuthRequest = socialAuthService.createTwitterAuthRequest(
        twitterAuth,
      );

      // Call backend API
      final authResponse = await repository.twitterLogin(socialAuthRequest);

      // Check if login was successful
      if (authResponse.code == 200 && authResponse.data != null) {
        // Save user and token to secure storage
        await AuthManager.saveUser(
          authResponse.data!.user,
          authResponse.data!.token,
        );

        emit(
          SocialAuthSuccess(
            user: authResponse.data!.user,
            message: authResponse.message,
          ),
        );
      } else {
        emit(SocialAuthError(authResponse.message));
      }
    } catch (e) {
      if (e is UnimplementedError) {
        emit(SocialAuthError('تسجيل الدخول بواسطة Twitter غير متاح حالياً'));
      } else {
        emit(
          SocialAuthError('فشل تسجيل الدخول بواسطة Twitter: ${e.toString()}'),
        );
      }
    }
  }

  /// Reset state to initial
  void resetState() {
    emit(SocialAuthInitial());
  }
}
