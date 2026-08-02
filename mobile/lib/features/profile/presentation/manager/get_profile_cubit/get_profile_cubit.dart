import 'package:bloc/bloc.dart';
import 'package:cached_network_image/cached_network_image.dart';
import 'package:equatable/equatable.dart';
import 'package:hawdaj/features/profile/data/model/profile_reponce.dart';
import 'package:hawdaj/features/profile/data/repo/profail_repo.dart';

part 'get_profile_state.dart';

class GetProfileCubit extends Cubit<GetProfileState> {
  GetProfileCubit(this.profileRepo) : super(GetProfileInitial());
  final ProfileRepo profileRepo;

  Future<void> getProfile() async {
    emit(GetProfileLoading());
    final result = await profileRepo.getProfile();
    result.fold((l) => emit(GetProfileError(errMessage: l.errMessage)), (
      r,
    ) async {
      // مسح الـ cache للصورة القديمة قبل عرض الجديدة
      final photoUrl = r.data.personalData.photo;
      if (photoUrl != null && photoUrl.isNotEmpty) {
        try {
          await CachedNetworkImage.evictFromCache(photoUrl);
        } catch (e) {
          print('⚠️ Failed to clear cache for: $photoUrl');
        }
      }
      print("📥 GetProfileCubit Success. Guide Data: ${r.data.userGuideData}");
      emit(GetProfileSuccess(r));
    });
  }
}
