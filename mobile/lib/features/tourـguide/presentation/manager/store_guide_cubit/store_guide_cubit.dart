import 'package:bloc/bloc.dart';
import 'package:equatable/equatable.dart';
import 'package:hawdaj/core/errors/failure.dart';
import 'package:hawdaj/features/home/data/model/guide_model/guide_model.dart';
import 'package:hawdaj/features/tour%D9%80guide/data/repo/tour_guide_repo.dart';
import 'package:hawdaj/features/tour%D9%80guide/presentation/manager/tour_guide_form_cubit/tour_guide_form_cubit.dart';

part 'store_guide_state.dart';

class StoreGuideCubit extends Cubit<StoreGuideState> {
  StoreGuideCubit(this._repo) : super(StoreGuideInitial());

  final TourGuideRepo _repo;

  // تطبيع الروابط + نص فاضي لو مفيش
  String _norm(String? url) {
    if (url == null) return '';
    final u = url.trim();
    if (u.isEmpty) return '';
    if (u.startsWith('http://') || u.startsWith('https://')) return u;
    return 'https://$u';
  }

  String _pick(Map<String, String> links, String key) => _norm(links[key]);

  Future<void> storeFromForm(TourGuideFormState s) async {
    emit(StoreGuideLoading());
    final res = await _repo.storeGuide(
      s.name.trim(),
      s.nickName.trim(),
      s.description.trim(),
      s.experience.trim(),
      _pick(s.socialLinks, 'Facebook'),
      _pick(s.socialLinks, 'Instagram'),
      _pick(s.socialLinks, 'Twitter'),
      _pick(s.socialLinks, 'LinkedIn'),
      List<int>.from(s.selectedRegionIds),
      List<int>.from(s.selectedLanguageIds),
      _pick(s.socialLinks, 'Personal Site'),

      _pick(s.socialLinks, 'youtube'),
      _pick(s.socialLinks, 'TikTok'),
    );

    res.fold(
      (Failure f) => emit(StoreGuideError(f.errMessage)),
      (GuideModel g) => emit(StoreGuideSuccess(g)),
    );
  }
}
