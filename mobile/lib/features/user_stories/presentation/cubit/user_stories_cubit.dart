import 'package:flutter_bloc/flutter_bloc.dart';
import 'package:hawdaj/core/utils/auth_manager.dart';
import 'package:hawdaj/features/user_stories/data/repositories/user_stories_repository.dart';
import 'package:hawdaj/features/user_stories/presentation/cubit/user_stories_state.dart';

class UserStoriesCubit extends Cubit<UserStoriesState> {
  final UserStoriesRepository userStoriesRepository;

  UserStoriesCubit(this.userStoriesRepository) : super(UserStoriesInitial());

  UserStoriesRepository get repository => userStoriesRepository;

  Future<void> fetchMyLastDayStories() async {
    emit(UserStoriesLoading());
    
    // Check if user is logged in before making the request
    final isLoggedIn = await AuthManager.isLoggedIn();
    if (!isLoggedIn) {
      emit(const UserStoriesError('يجب تسجيل الدخول لعرض قصصك'));
      return;
    }
    
    final result = await userStoriesRepository.fetchMyLastDayStories();
    
    result.fold(
      (failure) {
        // Handle 401 errors specifically
        if (failure.errMessage.contains('401') || failure.errMessage.contains('Unauthorized')) {
          emit(const UserStoriesError('انتهت صلاحية الجلسة، يرجى تسجيل الدخول مرة أخرى'));
        } else {
          emit(UserStoriesError(failure.errMessage));
        }
      },
      (userStoriesResponse) => emit(UserStoriesLoaded(userStoriesResponse)),
    );
  }
}
