// ignore_for_file: prefer_const_constructors

import 'package:flutter_bloc/flutter_bloc.dart';
import 'package:hawdaj/core/init/runtime_variables.dart';
import 'package:hawdaj/core/routing/app_router.dart';
import 'package:hawdaj/core/utils/auth_manager.dart';
import 'package:hawdaj/features/auth/data/models/user_model.dart';
import 'package:hawdaj/features/auth/domain/repositories/auth_repository.dart';
part 'user_info_state.dart';

var userInfoCubit = parentKey.currentContext!.read<UserInfoCubit>();
String? _token;
String? get token => _token;

class UserInfoCubit extends Cubit<UserInfoState> {
  static void setToken(String? t) {
    _token = t;
  }

  UserInfoCubit(this._authRepo) : super(UserInfoState()) {
    _initCubit();
    try {
      // reloadUserAPI();
    } catch (e) {
      logger.e(e);
    }
  }
  final AuthRepository _authRepo;

  String? token;
  UserModel? user;
  Future<void> _initCubit() async {
    var user = await AuthManager.getUser();
    var token = await AuthManager.getToken();
    if (user == null && token == null) return;
    await setUser(user, token);
  }

  Future<void> setUser(UserModel? user, String? token) async {
    emit(state.copyWith(loading: true));
    await AuthManager.saveUser(user, token);

    this.user = user ?? this.user;
    this.token = token ?? this.token;
    _token = token ?? _token;
    emit(
      state.copyWith(
        loading: false,
        user: user ?? this.user,
        token: token ?? this.token,
      ),
    );
  }

  Future<void> logout() async {
    emit(state.copyWith(loading: true));
    await AuthManager.logout();
    user = null;
    token = null;
    _token = null;
    emit(state.copyWith(loading: false, user: null, token: null));
  }

  Future<void> updateUserData(UserModel updatedUser) async {
    emit(state.copyWith(loading: true));
    await AuthManager.saveUser(updatedUser, token);

    user = updatedUser;
    emit(state.copyWith(loading: false, user: updatedUser, token: token));
  }

  // Future<void> reloadUserAPI() async {
  //   emit(state.copyWith(user: state.user, token: state.token, loading: true));
  //   var res = await _authRepo.profile();
  //   res.fold((l) {
  //     emit(
  //       state.copyWith(
  //         errorMsg: l.errMessage,
  //         loading: false,
  //         user: state.user,
  //         token: state.token,
  //       ),
  //     );
  //   }, (r) => setUser(r, state.token));
  // }
}
