import 'package:dio/dio.dart';
import 'package:get_it/get_it.dart';
import 'package:hawdaj/features/auth/data/repositories/auth_repository_impl.dart';
import 'package:hawdaj/features/auth/domain/repositories/auth_repository.dart';
import 'package:hawdaj/features/auth/services/social_auth_service.dart';
import 'package:hawdaj/features/events/data/repo/events_repo.dart';
import 'package:hawdaj/features/events/data/repo/events_repo_imp.dart';
import 'package:hawdaj/features/exploration/data/repo/exploration_repo.dart';
import 'package:hawdaj/features/exploration/data/repo/exploration_repo_imp.dart';
import 'package:hawdaj/features/favorites/data/repo/favorite_repo.dart';
import 'package:hawdaj/features/favorites/data/repo/favorites_repo_imp.dart';
import 'package:hawdaj/features/home/data/repo/home_repo.dart';
import 'package:hawdaj/features/home/data/repo/home_repo_imp.dart';
import 'package:hawdaj/features/my_properties/data/repo/properties_repo.dart';
import 'package:hawdaj/features/my_properties/data/repo/properties_repo_imp.dart';
import 'package:hawdaj/features/places/data/repo/places_repo.dart';
import 'package:hawdaj/features/places/data/repo/places_repo_imp.dart';
import 'package:hawdaj/features/profile/data/repo/profail_repo.dart';
import 'package:hawdaj/features/profile/data/repo/profail_repo_imp.dart';
import 'package:hawdaj/features/rates/data/repo/rate_repo.dart';
import 'package:hawdaj/features/rates/data/repo/rate_repo_imp.dart';
import 'package:hawdaj/features/tasneef/data/repositories/tasneef_repository_impl.dart';
import 'package:hawdaj/features/tasneef/domain/repositories/tasneef_repository.dart';
import 'package:hawdaj/features/tasneef/presentation/cubits/categories/categories_cubit.dart';
import 'package:hawdaj/features/restaurants/data/repo/restaurants_repo.dart';
import 'package:hawdaj/features/restaurants/data/repo/restaurants_repo_imp.dart';
import 'package:hawdaj/features/saved/data/repo/saved_repo.dart';
import 'package:hawdaj/features/saved/data/repo/saved_repo_imp.dart';
import 'package:hawdaj/features/stores/data/repo/stores_repo.dart';
import 'package:hawdaj/features/stores/data/repo/stores_repo_imp.dart';
import 'package:hawdaj/features/stories/data/repo/stories_repo.dart';
import 'package:hawdaj/features/stories/data/repo/stories_repo_imp.dart';
import 'package:hawdaj/features/tour%D9%80guide/data/repo/tour_guide_repo.dart';
import 'package:hawdaj/features/tour%D9%80guide/data/repo/tour_guide_repo_imp.dart';
import 'package:hawdaj/features/trip/data/repo/trip_repo.dart';
import 'package:hawdaj/features/trip/data/repo/trip_repo_imp.dart';
import 'package:hawdaj/core/repositories/regions_repository.dart';
import 'package:hawdaj/core/repositories/regions_repository_impl.dart';
import 'package:hawdaj/core/managers/regions_cubit/regions_cubit.dart';
import 'package:hawdaj/core/repositories/cities_repository.dart';
import 'package:hawdaj/core/repositories/cities_repository_impl.dart';
import 'package:hawdaj/core/repositories/location_repository.dart';
import 'package:hawdaj/core/repositories/location_repository_impl.dart';
import 'package:hawdaj/core/managers/cities_cubit/cities_cubit.dart';
import 'package:hawdaj/features/landmarks/data/repositories/landmarks_repository_impl.dart';
import 'package:hawdaj/features/landmarks/data/repositories/landmarks_repository_repo.dart';
import 'package:hawdaj/features/user_stories/data/repositories/user_stories_repository.dart';
import 'package:hawdaj/features/user_stories/data/repositories/user_stories_repository_impl.dart';
import 'package:hawdaj/features/force_update/data/repo/force_update_repo.dart';
import 'package:hawdaj/features/force_update/data/repo/firebase_force_update_repo_impl.dart';
import '../databases/api/dio_consumer.dart';

final getIt = GetIt.instance;

void setupServiceLocator() {
  getIt.registerSingleton<Dio>(Dio());
  getIt.registerSingleton<DioConsumer>(DioConsumer(dio: getIt<Dio>()));

  //HomeRepo
  getIt.registerLazySingleton<HomeRepo>(
    () => HomeRepoImp(getIt.get<DioConsumer>()),
  );

  //AuthRepo
  getIt.registerLazySingleton<AuthRepository>(
    () => AuthRepositoryImpl(apiConsumer: getIt.get<DioConsumer>()),
  );

  //SocialAuthService
  getIt.registerLazySingleton<SocialAuthService>(() => SocialAuthService());
  //PlacesRepoImp
  getIt.registerLazySingleton<PlacesRepo>(
    () => PlacesRepoImp(getIt.get<DioConsumer>()),
  );
  //RateRepo
  getIt.registerLazySingleton<RateRepo>(
    () => RateRepoImp(getIt.get<DioConsumer>()),
  );

  //TasneefRepo
  getIt.registerLazySingleton<TasneefRepository>(
    () => TasneefRepositoryImpl(apiConsumer: getIt.get<DioConsumer>()),
  );
  //ExplorationRepo

  //CategoriesCubit
  getIt.registerFactory<CategoriesCubit>(
    () => CategoriesCubit(repository: getIt.get<TasneefRepository>()),
  );
  //StoresRepo
  getIt.registerLazySingleton<StoresRepo>(
    () => StoresRepoImp(getIt.get<DioConsumer>()),
  );
  //RestaurantsRepo
  getIt.registerLazySingleton<RestaurantsRepo>(
    () => RestaurantsRepoImp(getIt.get<DioConsumer>()),
  );
  //EventsRepo
  getIt.registerLazySingleton<EventsRepo>(
    () => EventsRepoImp(getIt.get<DioConsumer>()),
  );
  //StoriesRepo
  getIt.registerLazySingleton<StoriesRepo>(
    () => StoriesRepoImp(getIt.get<DioConsumer>()),
  );
  //FavoriteRepo
  getIt.registerLazySingleton<FavoriteRepo>(
    () => FavoriteRepoImp(getIt.get<DioConsumer>()),
  );
  //SavedRepo
  getIt.registerLazySingleton<SavedRepo>(
    () => SavedRepoImp(getIt.get<DioConsumer>()),
  );
  //TourGuideRepo
  getIt.registerLazySingleton<TourGuideRepo>(
    () => TourGuideRepoImp(getIt.get<DioConsumer>()),
  );
  //TripRepo
  getIt.registerLazySingleton<TripRepo>(
    () => TripRepoImp(getIt.get<DioConsumer>()),
  );

  //RegionsRepo
  getIt.registerLazySingleton<RegionsRepository>(() {
    print('🔍 ServiceLocator: Creating RegionsRepository');
    return RegionsRepositoryImpl(apiConsumer: getIt.get<DioConsumer>());
  });

  //RegionsCubit
  getIt.registerLazySingleton<RegionsCubit>(() {
    print('🔍 ServiceLocator: Creating RegionsCubit');
    return RegionsCubit(regionsRepository: getIt.get<RegionsRepository>());
  });

  //CitiesRepo
  getIt.registerLazySingleton<CitiesRepository>(() {
    print('🔍 ServiceLocator: Creating CitiesRepository');
    return CitiesRepositoryImpl(apiConsumer: getIt.get<DioConsumer>());
  });

  //CitiesCubit
  getIt.registerFactory<CitiesCubit>(() {
    print('🔍 ServiceLocator: Creating CitiesCubit');
    return CitiesCubit(citiesRepository: getIt.get<CitiesRepository>());
  });

  //LocationRepo
  getIt.registerLazySingleton<LocationRepository>(() {
    print('🔍 ServiceLocator: Creating LocationRepository');
    return LocationRepositoryImpl(apiConsumer: getIt.get<DioConsumer>());
  });

  //LandmarksRepo
  getIt.registerLazySingleton<LandmarksRepository>(
    () => LandmarksRepositoryImpl(apiConsumer: getIt.get<DioConsumer>()),
  );
  //ProfileRepo
  getIt.registerLazySingleton<ProfileRepo>(
    () => ProfileRepoImp(getIt.get<DioConsumer>()),
  );
  //ExplorationRepo
  getIt.registerLazySingleton<ExplorationRepo>(
    () => ExplorationRepoImp(getIt.get<DioConsumer>()),
  );
  //PropertiesRepo
  getIt.registerLazySingleton<PropertiesRepo>(
    () => PropertiesRepoImp(getIt.get<DioConsumer>()),
  );
  //UserStoriesRepo
  getIt.registerLazySingleton<UserStoriesRepository>(
    () => UserStoriesRepositoryImpl(getIt.get<DioConsumer>()),
  );

  //ForceUpdateRepo
  getIt.registerLazySingleton<ForceUpdateRepo>(
    () => FirebaseForceUpdateRepoImpl(),
  );
}
