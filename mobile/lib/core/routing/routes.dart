import 'package:dio/dio.dart';
import 'package:easy_localization/easy_localization.dart';
import 'package:flutter_bloc/flutter_bloc.dart';
import 'package:hawdaj/core/components/build_page_with_default_transition.dart';
import 'package:hawdaj/core/managers/cities_cubit/cities_cubit.dart';
import 'package:hawdaj/core/repositories/cities_repository.dart';
import 'package:hawdaj/core/routing/main_shell.dart';
import 'package:hawdaj/core/routing/routes_branches.dart';
import 'package:hawdaj/core/routing/routes_keys.dart';
import 'package:go_router/go_router.dart';
import 'package:hawdaj/core/services/service_locator.dart';
import 'package:hawdaj/core/utils/app_environment_manager.dart';
import 'package:hawdaj/core/utils/env_switch_screen.dart';
import 'package:hawdaj/core/views/see_all_ratings_list.dart';
import 'package:hawdaj/features/auth/domain/repositories/auth_repository.dart';
import 'package:hawdaj/features/auth/presentation/manager/cubits/forgot_password_cubit/forgot_password_cubit.dart';
import 'package:hawdaj/features/auth/presentation/views/forgot_password_view.dart';
import 'package:hawdaj/features/auth/presentation/views/login_view.dart';
import 'package:hawdaj/features/auth/presentation/views/otp_verification_view.dart';
import 'package:hawdaj/features/auth/presentation/views/register_view.dart';
import 'package:hawdaj/features/events/data/repo/events_repo.dart';
import 'package:hawdaj/features/events/presentation/manager/events_details_cubit/events_details_cubit.dart';
import 'package:hawdaj/features/events/presentation/view/events_details_view.dart';
import 'package:hawdaj/features/exploration/data/repo/exploration_repo.dart';
import 'package:hawdaj/features/exploration/presentation/manager/get_search_global_cubit/get_search_global_cubit.dart';

import 'package:hawdaj/features/exploration/presentation/manager/search_filter_cubit/search_filter_cubit.dart';

import 'package:hawdaj/features/exploration/presentation/view/search_view.dart';
import 'package:hawdaj/features/force_update/presentation/screen/force_update_intermediate_screen.dart';
import 'package:hawdaj/features/home/data/model/explore_category_model.dart';
import 'package:hawdaj/features/home/data/model/guide_model/guide_model.dart';
import 'package:hawdaj/features/landmarks/presentation/manager/get_landmarks_cubit/get_landmarks_cubit.dart';
import 'package:hawdaj/features/landmarks/presentation/manager/get_my_landmarks_cubit/get_my_landmarks_cubit.dart';
import 'package:hawdaj/features/landmarks/presentation/view/land_mark_view.dart';
import 'package:hawdaj/features/landmarks/presentation/view/my_land_mark_view.dart';
import 'package:hawdaj/features/my_properties/data/repo/properties_repo.dart';
import 'package:hawdaj/features/my_properties/presentation/manager/add_property_cubit/add_property_cubit.dart';
import 'package:hawdaj/features/my_properties/presentation/manager/add_property_form_cubit/add_property_form_cubit.dart';
import 'package:hawdaj/features/my_properties/presentation/manager/my_properties_cubit/my_properties_cubit.dart';
import 'package:hawdaj/features/my_properties/presentation/view/add_places_view.dart';
import 'package:hawdaj/features/my_properties/presentation/view/my_properties_view.dart';
import 'package:hawdaj/features/my_properties/presentation/view/widgets/map_picker_view.dart';
import 'package:hawdaj/features/places/data/repo/places_repo.dart';
import 'package:hawdaj/features/places/presentation/manager/cubit/place_details_cubit.dart';
import 'package:hawdaj/features/places/presentation/view/places_details_items.dart';
import 'package:hawdaj/features/profile/data/model/profile_reponce.dart';
import 'package:hawdaj/features/profile/data/repo/profail_repo.dart';
import 'package:hawdaj/features/profile/presentation/manager/edit_profile_cubit/edit_profile_cubit.dart';
import 'package:hawdaj/features/profile/presentation/manager/get_favorites_cubit/get_favorites_cubit.dart';
import 'package:hawdaj/features/profile/presentation/manager/get_profile_cubit/get_profile_cubit.dart';
import 'package:hawdaj/features/profile/presentation/manager/get_saved_cubit/get_saved_cubit.dart';
import 'package:hawdaj/features/profile/presentation/manager/my_last_day_stories_cubit/my_last_day_stories_cubit.dart';
import 'package:hawdaj/features/profile/presentation/manager/update_password_cubit/update_password_cubit.dart';
import 'package:hawdaj/features/profile/presentation/views/contact_us_view.dart';
import 'package:hawdaj/features/profile/presentation/views/edit_profile_view.dart';
import 'package:hawdaj/features/profile/presentation/views/favorite_view.dart';

import 'package:hawdaj/features/profile/presentation/views/terms_and_conditions_view.dart';
import 'package:hawdaj/features/profile/presentation/views/update_password_view.dart';
import 'package:hawdaj/features/profile/presentation/views/widgets/saved_view.dart';
import 'package:hawdaj/features/restaurants/data/repo/restaurants_repo.dart';
import 'package:hawdaj/features/restaurants/presentation/manager/fetch_offers_cubit/fetch_offers_cubit.dart';
import 'package:hawdaj/features/restaurants/presentation/manager/menu_cubit/menu_cubit.dart';
import 'package:hawdaj/features/restaurants/presentation/manager/restaurants_cubit/restaurants_details_cubit.dart';
import 'package:hawdaj/features/restaurants/presentation/view/restaurants_details_items.dart';
import 'package:hawdaj/features/splash/presentation/views/onboarding_view.dart';
import 'package:hawdaj/features/home/presentation/view/home_details_view.dart';

import 'package:hawdaj/features/splash/presentation/views/splash_view.dart';
import 'package:hawdaj/features/stores/data/repo/stores_repo.dart';
import 'package:hawdaj/features/stores/presentation/manager/stores_details_cubit/stores_details_cubit.dart';
import 'package:hawdaj/features/stores/presentation/view/stores_details_items.dart';
import 'package:hawdaj/features/stories/data/repo/stories_repo.dart';
import 'package:hawdaj/features/stories/presentation/view/stories_details_view.dart';
import 'package:hawdaj/features/tasneef/data/models/rating_model.dart';
import 'package:hawdaj/features/tasneef/data/models/unified_place_model.dart';
import 'package:hawdaj/features/tasneef/domain/repositories/tasneef_repository.dart';
import 'package:hawdaj/features/tasneef/presentation/cubits/categories/categories_cubit.dart';
import 'package:hawdaj/features/tasneef/presentation/cubits/categories/sub_categories_cubit.dart';
import 'package:hawdaj/features/tasneef/presentation/views/tasneef_apps_list_view.dart';
import 'package:hawdaj/features/tasneef/presentation/views/tasneef_places_list_view.dart';
import 'package:hawdaj/features/tasneef/presentation/views/tasneef_stories_list_view.dart';
import 'package:hawdaj/features/tasneef/presentation/views/tasneef_tour_guides_list_view.dart';

import 'package:hawdaj/features/tour%D9%80guide/data/repo/tour_guide_repo.dart';
import 'package:hawdaj/features/tour%D9%80guide/presentation/manager/update_guide_photo_cubit/update_guide_photo_cubit.dart';
import 'package:hawdaj/features/tour%D9%80guide/presentation/manager/fetch_all_tour_guide_cubit/fetch_all_tour_guide_cubit.dart';
import 'package:hawdaj/features/tour%D9%80guide/presentation/manager/fetch_languages_cubit/fetch_languages_cubit.dart';
import 'package:hawdaj/features/tour%D9%80guide/presentation/manager/fetch_region_cubit/fetch_region_cubit.dart';
import 'package:hawdaj/features/tour%D9%80guide/presentation/manager/fetch_tour_guide_by_top_rated_cubit/fetch_tour_guide_by_top_rated_cubit.dart';
import 'package:hawdaj/features/tour%D9%80guide/presentation/manager/store_guide_cubit/store_guide_cubit.dart';
import 'package:hawdaj/features/tour%D9%80guide/presentation/manager/tour_guide_details_cubit/tour_guide_details_cubit.dart';
import 'package:hawdaj/features/tour%D9%80guide/presentation/manager/tour_guide_form_cubit/tour_guide_form_cubit.dart';
import 'package:hawdaj/features/tour%D9%80guide/presentation/view/add_tour_guide_view.dart';
import 'package:hawdaj/features/tour%D9%80guide/presentation/view/tour_guide_details_view.dart';
import 'package:hawdaj/features/tour%D9%80guide/presentation/view/tour_guide_view.dart';
import 'package:hawdaj/features/trip/data/model/enhanced_trip_response.dart';
import 'package:hawdaj/features/trip/data/model/trip_model/trip_model.dart';
import 'package:hawdaj/features/trip/data/repo/trip_repo.dart';
import 'package:hawdaj/features/trip/presentation/manager/delete_trip_cubit/delete_trip_cubit.dart';
import 'package:hawdaj/features/trip/presentation/manager/fetch_category_cubit/fetch_category_cubit.dart';
import 'package:hawdaj/features/trip/presentation/manager/fetch_prices_cubit/fetch_prices_cubit.dart';
import 'package:hawdaj/features/trip/presentation/manager/finish_trip_details_cubit/finish_trip_details_cubit.dart';
import 'package:hawdaj/features/trip/presentation/manager/my_trip_cubit/my_trip_cubit.dart';
import 'package:hawdaj/features/trip/presentation/manager/new_my_trip_cubit/new_my_trip_cubit.dart';
import 'package:hawdaj/features/trip/presentation/manager/new_view_trip_cubit/new_view_trip_cubit.dart';
import 'package:hawdaj/features/trip/presentation/manager/prepare_trip_cubit/prepare_trip_cubit.dart';
import 'package:hawdaj/features/trip/presentation/manager/prepare_trip_show_cubit/prepare_trip_show_cubit.dart';
import 'package:hawdaj/features/trip/presentation/manager/prepare_trip_wizard/prepare_trip_wizard_cubit.dart';
import 'package:hawdaj/features/trip/presentation/manager/reprepare_trip_cubit/reprepare_trip_cubit.dart';
import 'package:hawdaj/features/trip/presentation/manager/save_trip_cubit/save_trip_cubit.dart';
import 'package:hawdaj/features/trip/presentation/manager/save_trip_to_email_cubit/save_trip_to_email_cubit.dart';
import 'package:hawdaj/features/trip/presentation/manager/save_trip_v2_cubit/save_trip_v2_cubit.dart';
import 'package:hawdaj/features/trip/presentation/manager/view_trip_cubit/view_trip_cubit.dart';
import 'package:hawdaj/features/trip/presentation/view/my_trip.dart';
import 'package:hawdaj/features/trip/presentation/view/new_trip_plan.dart';
import 'package:hawdaj/features/trip/presentation/view/show_my_trip.dart';

import 'package:hawdaj/features/trip/presentation/view/add_trip_view.dart';
import 'package:hawdaj/features/landmarks/presentation/view/add_landmark_view.dart';
import 'package:hawdaj/features/landmarks/presentation/manager/add_landmark_cubit/add_landmark_cubit.dart';
import 'package:hawdaj/features/landmarks/data/repositories/landmarks_repository_repo.dart';
import 'package:hawdaj/features/trip/presentation/view/trip_plan.dart';
import 'package:hawdaj/features/trip/presentation/view/finish_trip.dart';
import 'package:hawdaj/features/trip/presentation/view/widgets/map_trip_view.dart';
import 'package:hawdaj/features/trip/presentation/view/widgets/my_trip_details_view.dart';
import 'package:hawdaj/features/trip/presentation/view/widgets/new_my_trip_view.dart';
import 'package:hawdaj/features/user_stories/presentation/remove/remove_story_cubit.dart';

import '../../features/stories/presentation/manager/stories_details/stories_details_cubit.dart';
import 'package:hawdaj/features/user_stories/presentation/views/user_stories_view.dart';
import 'package:hawdaj/features/user_stories/presentation/cubit/user_stories_cubit.dart';
import 'package:hawdaj/features/user_stories/data/repositories/user_stories_repository.dart';

import 'app_router.dart';

List<RouteBase> appRoutes = [
  StatefulShellRoute.indexedStack(
    builder: (context, state, navigationShell) {
      return MainShell(navigationShell: navigationShell);
    },
    branches: routesBranches,
  ),

  GoRoute(
    parentNavigatorKey: parentKey,
    path: RoutesKeys.kSplashView,
    pageBuilder: (context, state) => buildPageWithDefaultTransition<void>(
      context: context,
      state: state,
      child: const SplashView(),
    ),
  ),
  GoRoute(
    parentNavigatorKey: parentKey,
    path: RoutesKeys.kOnboardingView,
    pageBuilder: (context, state) => buildPageWithDefaultTransition<void>(
      context: context,
      state: state,
      child: const OnboardingView(),
    ),
  ),
  GoRoute(
    parentNavigatorKey: parentKey,
    path: RoutesKeys.kRegisterView,
    pageBuilder: (context, state) => buildPageWithDefaultTransition<void>(
      context: context,
      state: state,
      child: const RegisterView(),
    ),
  ),
  GoRoute(
    parentNavigatorKey: parentKey,
    path: RoutesKeys.kEnvironmentSwitcher,
    pageBuilder: (context, state) => buildPageWithDefaultTransition<void>(
      context: context,
      state: state,
      child: EnvSwitchScreen(),
    ),
  ),

  GoRoute(
    parentNavigatorKey: parentKey,
    path: RoutesKeys.kEditProfileView,
    pageBuilder: (context, state) => buildPageWithDefaultTransition<void>(
      context: context,
      state: state,
      child: MultiBlocProvider(
        providers: [
          BlocProvider(
            create: (context) => EditProfileCubit(getIt<AuthRepository>()),
          ),
          BlocProvider(
            create: (context) => GetProfileCubit(getIt<ProfileRepo>()),
          ),
        ],
        child: EditProfileView(
          profilePageResponse: state.extra as ProfilePageResponse,
        ),
      ),
    ),
  ),
  GoRoute(
    parentNavigatorKey: parentKey,
    path: RoutesKeys.kLoginView,
    pageBuilder: (context, state) => buildPageWithDefaultTransition<void>(
      context: context,
      state: state,
      child: const LoginView(),
    ),
  ),
  GoRoute(
    parentNavigatorKey: parentKey,
    path: RoutesKeys.kForgotPasswordView,
    pageBuilder: (context, state) => buildPageWithDefaultTransition<void>(
      context: context,
      state: state,
      child: BlocProvider(
        create: (context) => ForgotPasswordCubit(getIt<AuthRepository>()),
        child: const ForgotPasswordView(),
      ),
    ),
  ),
  GoRoute(
    parentNavigatorKey: parentKey,
    path: RoutesKeys.kOtpVerificationView,
    pageBuilder: (context, state) => buildPageWithDefaultTransition<void>(
      context: context,
      state: state,
      child: OtpVerificationView(email: state.uri.queryParameters['email']),
    ),
  ),
  //HomeDetailsView
  GoRoute(
    parentNavigatorKey: parentKey,
    path: RoutesKeys.kHomeDetailsView,
    pageBuilder: (context, state) => buildPageWithDefaultTransition<void>(
      context: context,
      state: state,
      child: const HomePlacesDetailsView(),
    ),
  ),

  GoRoute(
    parentNavigatorKey: parentKey,
    path: RoutesKeys.kTasneefPlacesListView,
    pageBuilder: (context, state) => buildPageWithDefaultTransition<void>(
      context: context,
      state: state,
      child: TasneefPlacesListView(
        type: state.uri.queryParameters['type'] ?? 'places',
        title: state.uri.queryParameters['title']?.tr(),
        manor: state.uri.queryParameters['manor'] == 'true',
        categoryId: int.tryParse(state.uri.queryParameters['categoryId'] ?? ''),
        topFeatured: state.uri.queryParameters['topFeatured'] == 'true'
            ? true
            : null,
      ),
    ),
  ),
  GoRoute(
    parentNavigatorKey: parentKey,
    path: RoutesKeys.kTasneefStoresListView,
    pageBuilder: (context, state) => buildPageWithDefaultTransition<void>(
      context: context,
      state: state,
      child: TasneefPlacesListView(
        type: 'stores',
        title: "filter_stores".tr(),
        manor: true,
      ),
    ),
  ),
  GoRoute(
    parentNavigatorKey: parentKey,
    path: RoutesKeys.kTasneefZadsListView,
    pageBuilder: (context, state) => buildPageWithDefaultTransition<void>(
      context: context,
      state: state,
      child: TasneefPlacesListView(
        type: 'zads',
        title: 'filter_zads'.tr(),
        manor: false,
      ),
    ),
  ),
  GoRoute(
    parentNavigatorKey: parentKey,
    path: RoutesKeys.kTasneefStoriesListView,
    pageBuilder: (context, state) => buildPageWithDefaultTransition<void>(
      context: context,
      state: state,
      child: const TasneefStoriesListView(),
    ),
  ),
  GoRoute(
    parentNavigatorKey: parentKey,
    path: RoutesKeys.kTasneefAppsListView,
    pageBuilder: (context, state) => buildPageWithDefaultTransition<void>(
      context: context,
      state: state,
      child: const TasneefAppsListView(),
    ),
  ),
  GoRoute(
    parentNavigatorKey: parentKey,
    path: RoutesKeys.kTasneefTourGuidesListView,
    pageBuilder: (context, state) => buildPageWithDefaultTransition<void>(
      context: context,
      state: state,
      child: const TasneefTourGuidesListView(),
    ),
  ),
  //PlacesDetailsItems
  GoRoute(
    parentNavigatorKey: parentKey,
    path: RoutesKeys.kPlacesDetailsItems,
    pageBuilder: (context, state) => buildPageWithDefaultTransition<void>(
      context: context,
      state: state,
      child: MultiBlocProvider(
        providers: [
          BlocProvider(
            create: (context) =>
                PlaceDetailsCubit(getIt<PlacesRepo>(), state.extra as String)
                  ..getPlaceInfo(),
          ),
        ],
        child: const PlacesDetailsItems(),
      ),
    ),
  ),
  //StoresDetailsItems
  GoRoute(
    parentNavigatorKey: parentKey,
    path: RoutesKeys.kStoresDetailsItems,
    pageBuilder: (context, state) => buildPageWithDefaultTransition<void>(
      context: context,
      state: state,
      child: MultiBlocProvider(
        providers: [
          BlocProvider(
            create: (context) =>
                StoresDetailsCubit(getIt<StoresRepo>(), state.extra as String)
                  ..getStoresInfo(),
          ),
        ],
        child: const StoresDetailsItems(),
      ),
    ),
  ),
  //restaurants
  GoRoute(
    parentNavigatorKey: parentKey,
    path: RoutesKeys.kRestaurantsDetailsItems,
    pageBuilder: (context, state) {
      final extra = state.extra as Map<String, dynamic>;
      final slug = extra['slug'] as String;
      final id = extra['id'] as int;

      return buildPageWithDefaultTransition<void>(
        context: context,
        state: state,
        child: MultiBlocProvider(
          providers: [
            BlocProvider(
              create: (context) =>
                  RestaurantsDetailsCubit(slug, getIt<RestaurantsRepo>())
                    ..getRestaurantsInfo(),
            ),
            BlocProvider(
              create: (context) =>
                  MenuCubit(restaurants: getIt<RestaurantsRepo>(), id: id),
            ),
            BlocProvider(
              create: (context) => FetchOffersCubit(
                restaurants: getIt<RestaurantsRepo>(),
                id: id,
              ),
            ),
          ],
          child: const RestaurantsDetailsItems(),
        ),
      );
    },
  ),

  //EventsDetailsView
  GoRoute(
    parentNavigatorKey: parentKey,
    path: RoutesKeys.kEventsDetailsView,
    pageBuilder: (context, state) => buildPageWithDefaultTransition<void>(
      context: context,
      state: state,
      child: MultiBlocProvider(
        providers: [
          BlocProvider(
            create: (context) =>
                EventsDetailsCubit(state.extra as String, getIt<EventsRepo>())
                  ..getEventsInfo(),
          ),
        ],
        child: const EventsDetailsView(),
      ),
    ),
  ),
  //StoriesDetailsView
  GoRoute(
    parentNavigatorKey: parentKey,
    path: RoutesKeys.kStoriesDetailsView,
    pageBuilder: (context, state) => buildPageWithDefaultTransition<void>(
      context: context,
      state: state,

      child: MultiBlocProvider(
        providers: [
          BlocProvider(
            create: (context) =>
                StoriesDetailsCubit(state.extra as String, getIt<StoriesRepo>())
                  ..getStoriesInfo(),
          ),
        ],
        child: const StoriesDetailsView(),
      ),
    ),
  ),
  //TourGuideDetailsView
  GoRoute(
    parentNavigatorKey: parentKey,
    path: RoutesKeys.kTourGuideDetailsView,
    pageBuilder: (context, state) => buildPageWithDefaultTransition<void>(
      context: context,
      state: state,
      child: MultiBlocProvider(
        providers: [
          BlocProvider(
            create: (context) => TourGuideDetailsCubit(
              state.extra as int,
              getIt<TourGuideRepo>(),
            )..getTourGuideInfo(),
          ),
          BlocProvider(
            create: (context) =>
                FetchTourGuideByTopRatedCubit(repo: getIt<TourGuideRepo>())
                  ..fetch(),
          ),
        ],
        child: const TourGuideDetailsView(),
      ),
    ),
  ),
  //StartTripView

  //kAddTripView
  GoRoute(
    parentNavigatorKey: parentKey,
    path: RoutesKeys.kAddTripView,
    pageBuilder: (context, state) => buildPageWithDefaultTransition<void>(
      context: context,
      state: state,
      child: MultiBlocProvider(
        providers: [
          BlocProvider(
            create: (context) =>
                FetchCategoryCubit(getIt<TripRepo>())..fetchCategory(),
          ),
          BlocProvider(
            create: (context) =>
                FetchPricesCubit(getIt<TripRepo>())..fetchPrices(),
          ),
          // /PrepareTripWizardCubit
          BlocProvider(
            create: (context) => PrepareTripCubit(getIt<TripRepo>()),
          ),
          //  /PrepareTripWizardCubit
          BlocProvider(
            create: (context) =>
                PrepareTripWizardCubit()..resetWizardSelections(persist: true),
          ),
        ],
        child: const AddTripView(),
      ),
    ),
  ),
  //kAddLandmarkView
  GoRoute(
    parentNavigatorKey: parentKey,
    path: RoutesKeys.kAddLandmarkView,
    pageBuilder: (context, state) {
      final selectedCategory = state.extra as ExploreCategoryModel?;
      print("🚀 Selected Category from Route: ${selectedCategory}");

      return buildPageWithDefaultTransition<void>(
        context: context,
        state: state,
        child: BlocProvider(
          create: (context) => AddLandmarkCubit(
            repository: getIt<LandmarksRepository>(),
            selectedCategory: selectedCategory,
          ),
          child: const AddLandmarkView(),
        ),
      );
    },
  ),

  //TripPlan
  GoRoute(
    parentNavigatorKey: parentKey,
    path: RoutesKeys.kTripPlan,
    pageBuilder: (context, state) {
      final trip = state.extra as TripModel;

      return buildPageWithDefaultTransition<void>(
        context: context,
        state: state,
        child: MultiBlocProvider(
          providers: [
            BlocProvider(
              create: (context) =>
                  FinishTripDetailsCubit(getIt<TripRepo>(), trip.token ?? '')
                    ..finishTripDetails(),
            ),
            BlocProvider(
              create: (context) => SaveTripToEmailCubit(getIt<TripRepo>()),
            ),
            //SaveTripCubit
            BlocProvider(create: (context) => SaveTripCubit(getIt<TripRepo>())),
          ],
          child: TripPlan(tripModel: trip),
        ),
      );
    },
  ),
  //FinishTrip
  GoRoute(
    parentNavigatorKey: parentKey,
    path: RoutesKeys.kFinishTrip,
    pageBuilder: (context, state) {
      final extra = state.extra;

      return buildPageWithDefaultTransition<void>(
        context: context,
        state: state,
        child: FinishTrip(
          tripModel: extra is TripModel ? extra : null,
          tripResponse: extra is EnhancedTripResponse ? extra : null,
        ),
      );
    },
  ),

  //MyTripView
  GoRoute(
    parentNavigatorKey: parentKey,
    path: RoutesKeys.kMyTripView,
    pageBuilder: (context, state) => buildPageWithDefaultTransition<void>(
      context: context,
      state: state,
      child: MultiBlocProvider(
        providers: [
          BlocProvider(
            create: (context) => SaveTripToEmailCubit(getIt<TripRepo>()),
          ),
          BlocProvider(
            create: (context) => MyTripCubit(tripRepo: getIt<TripRepo>()),
          ),
          BlocProvider(create: (context) => DeleteTripCubit(getIt<TripRepo>())),
          //SaveTripToEmailCubit
          BlocProvider(
            create: (context) => SaveTripToEmailCubit(getIt<TripRepo>()),
          ),

          //ViewTripCubit
          BlocProvider(
            create: (context) =>
                ViewTripCubit(getIt<TripRepo>(), state.extra as String)
                  ..viewTrip(),
          ),
        ],
        child: const MyTripView(),
      ),
    ),
  ),
  //kNewMyTripView
  GoRoute(
    parentNavigatorKey: parentKey,
    path: RoutesKeys.kNewMyTripView,
    pageBuilder: (context, state) => buildPageWithDefaultTransition<void>(
      context: context,
      state: state,
      child: MultiBlocProvider(
        providers: [
          BlocProvider(
            create: (context) => NewMyTripCubit(tripRepo: getIt<TripRepo>()),
          ),
        ],
        child: const NewMyTripView(),
      ),
    ),
  ),

  //MyTripDetailsView
  GoRoute(
    parentNavigatorKey: parentKey,
    path: RoutesKeys.kMyTripDetailsView,
    pageBuilder: (context, state) => buildPageWithDefaultTransition<void>(
      context: context,
      state: state,
      child: MultiBlocProvider(
        providers: [
          BlocProvider(
            create: (context) =>
                ViewTripCubit(getIt<TripRepo>(), state.extra as String)
                  ..viewTrip(),
          ),
          BlocProvider(
            create: (context) => MyTripCubit(tripRepo: getIt<TripRepo>()),
          ),
          BlocProvider(create: (context) => DeleteTripCubit(getIt<TripRepo>())),
        ],
        child: MyTripDetailsView(token: state.extra as String),
      ),
    ),
  ),
  //AddTourGuideDetailsView
  GoRoute(
    parentNavigatorKey: parentKey,
    path: RoutesKeys.kAddTourGuideDetailsView,
    pageBuilder: (context, state) {
      final GuideModel? guide = (state.extra is GuideModel)
          ? state.extra as GuideModel
          : null;
      final bool isEdit = guide != null;

      return buildPageWithDefaultTransition<void>(
        context: context,
        state: state,
        child: MultiBlocProvider(
          providers: [
            BlocProvider(
              create: (context) =>
                  FetchLanguagesCubit(getIt<TourGuideRepo>())..fetchLanguages(),
            ),
            BlocProvider(
              create: (context) =>
                  FetchRegionCubit(getIt<TourGuideRepo>())..fetchRegion(),
            ),
            BlocProvider(
              create: (context) {
                final cubit = TourGuideFormCubit();
                if (isEdit) {
                  // تهيئة بيانات الفورم من الموديل
                  cubit.initForEditFromModel(guide);
                }
                return cubit;
              },
            ),
            BlocProvider(
              create: (context) => StoreGuideCubit(getIt<TourGuideRepo>()),
            ),
            //UpdateGuidePhotoCubit
            BlocProvider(
              create: (context) =>
                  UpdateGuidePhotoCubit(getIt<TourGuideRepo>()),
            ),
          ],
          child: AddTourGuideDetailsView(
            isEdit: isEdit,
            guideModel: guide, // null في حالة الإضافة
          ),
        ),
      );
    },
  ),

  //kTourGuideView
  GoRoute(
    parentNavigatorKey: parentKey,
    path: RoutesKeys.kTourGuideView,
    pageBuilder: (context, state) => buildPageWithDefaultTransition<void>(
      context: context,
      state: state,
      child: MultiBlocProvider(
        providers: [
          // /FetchAllTourGuideCubit
          BlocProvider(
            create: (context) =>
                FetchAllTourGuideCubit(getIt<TourGuideRepo>())
                  ..fetchAllTourGuide(),
          ),
        ],
        child: const TourGuideView(),
      ),
    ),
  ),
  //UpdatePasswordView
  GoRoute(
    parentNavigatorKey: parentKey,
    path: RoutesKeys.kUpdatePasswordView,
    pageBuilder: (context, state) => buildPageWithDefaultTransition<void>(
      context: context,
      state: state,
      child: BlocProvider(
        create: (context) => UpdatePasswordCubit(getIt<ProfileRepo>()),
        child: const UpdatePasswordView(),
      ),
    ),
  ),
  //ContactUsView
  GoRoute(
    parentNavigatorKey: parentKey,
    path: RoutesKeys.kContactUsView,
    pageBuilder: (context, state) => buildPageWithDefaultTransition<void>(
      context: context,
      state: state,
      child: const ContactUsView(),
    ),
  ),
  //TermsAndConditionsView
  GoRoute(
    parentNavigatorKey: parentKey,
    path: RoutesKeys.kTermsAndConditionsView,
    pageBuilder: (context, state) => buildPageWithDefaultTransition<void>(
      context: context,
      state: state,
      child: const TermsAndConditionsView(),
    ),
  ),
  //MapPage

  //kUserStoriesView
  GoRoute(
    parentNavigatorKey: parentKey,
    path: RoutesKeys.kUserStoriesView,
    pageBuilder: (context, state) => buildPageWithDefaultTransition<void>(
      context: context,
      state: state,
      child: MultiBlocProvider(
        providers: [
          BlocProvider(
            create: (context) =>
                UserStoriesCubit(getIt<UserStoriesRepository>()),
          ),
          //RemoveStoryCubit
          BlocProvider(
            create: (context) =>
                RemoveStoryCubit(getIt<UserStoriesRepository>()),
          ),
          //MyLastDayStoriesCubit
          BlocProvider(
            create: (context) =>
                MyLastDayStoriesCubit(getIt<ProfileRepo>())
                  ..getMyLastDayStories(),
          ),
        ],
        child: const UserStoriesView(),
      ),
    ),
  ),
  //SearchView
  GoRoute(
    parentNavigatorKey: parentKey,
    path: RoutesKeys.kSearchView,
    pageBuilder: (context, state) {
      return buildPageWithDefaultTransition<void>(
        context: context,
        state: state,
        child: MultiBlocProvider(
          providers: [
            BlocProvider<CitiesCubit>(
              create: (context) =>
                  CitiesCubit(citiesRepository: getIt<CitiesRepository>()),
            ),
            BlocProvider<GetSearchGlobalCubit>(
              create: (context) => GetSearchGlobalCubit(
                explorationRepo: getIt<ExplorationRepo>(),
              ),
            ),
            BlocProvider<SearchFilterCubit>(
              create: (context) => SearchFilterCubit(
                citiesCubit: context.read<CitiesCubit>(),
                searchCubit: context.read<GetSearchGlobalCubit>(),
              ),
            ),
          ],
          child: const SearchView(),
        ),
      );
    },
  ),
  //kSeeAllRatingsList
  GoRoute(
    parentNavigatorKey: parentKey,
    path: RoutesKeys.kSeeAllRatingsList,
    pageBuilder: (context, state) {
      final args = state.extra as SeeAllRatingsArgs;

      return buildPageWithDefaultTransition<void>(
        context: context,
        state: state,
        child: SeeAllRatingsList(
          ratings: args.ratings,
          type: args.type,
          id: args.id,
        ),
      );
    },
  ),

  //MapTripView
  GoRoute(
    parentNavigatorKey: parentKey,
    path: RoutesKeys.kMapTripView,
    pageBuilder: (context, state) => buildPageWithDefaultTransition<void>(
      context: context,
      state: state,
      child: MapTripView(
        dailyGroups: state.extra as List<List<UnifiedPlaceModel>>?,
      ),
    ),
  ),
  GoRoute(
    parentNavigatorKey: parentKey,
    path: RoutesKeys.kForceUpdateIntermediateScreen,
    pageBuilder: (context, state) => buildPageWithDefaultTransition<void>(
      context: context,
      state: state,
      child: ForceUpdateIntermediateScreen(
        nextPage: RoutesKeys.kSplashView,
        child: SplashViewBody(),
      ),
    ),
  ),
  GoRoute(
    parentNavigatorKey: parentKey,
    path: RoutesKeys.kFavoriteView,
    pageBuilder: (context, state) => buildPageWithDefaultTransition<void>(
      context: context,
      state: state,
      child: MultiBlocProvider(
        providers: [
          BlocProvider(
            create: (context) =>
                GetFavoritesCubit(getIt<ProfileRepo>())..getFavorites(),
          ),
          //  BlocProvider(create: (context) => FavoriteCubit()),
        ],
        child: FavoriteView(),
      ),
    ),
  ),

  //kFavoriteView

  //kSavedView
  GoRoute(
    parentNavigatorKey: parentKey,
    path: RoutesKeys.kSavedView,
    pageBuilder: (context, state) => buildPageWithDefaultTransition<void>(
      context: context,
      state: state,
      child: BlocProvider(
        create: (context) => GetSavedCubit(getIt<ProfileRepo>())..getSaved(),
        child: SavedView(),
      ),
    ),
  ),
  //kMyPropertiesView
  GoRoute(
    parentNavigatorKey: parentKey,
    path: RoutesKeys.kMyPropertiesView,
    pageBuilder: (context, state) => buildPageWithDefaultTransition<void>(
      context: context,
      state: state,
      child: BlocProvider(
        create: (context) => MyPropertiesCubit(getIt<PropertiesRepo>()),
        child: const MyPropertiesView(),
      ),
    ),
  ),
  //kAddPlacesView
  GoRoute(
    parentNavigatorKey: parentKey,
    path: RoutesKeys.kAddPlacesView,
    pageBuilder: (context, state) => buildPageWithDefaultTransition<void>(
      context: context,
      state: state,
      child: MultiBlocProvider(
        providers: [
          //AddPropertyCubit
          BlocProvider(
            create: (context) => AddPropertyCubit(getIt<PropertiesRepo>()),
          ),
          BlocProvider(create: (context) => AddPropertyFormCubit()),
          BlocProvider(
            create: (context) =>
                FetchPricesCubit(getIt<TripRepo>())..fetchPrices(),
          ),
          BlocProvider(
            create: (context) =>
                getIt<CategoriesCubit>()..loadMainCategories('places'),
          ),
          BlocProvider(
            create: (context) =>
                SubCategoriesCubit(repository: getIt<TasneefRepository>())
                  ..loadZadFoodCategories(),
          ),
        ],
        child: const AddPlacesView(),
      ),
    ),
  ),
  // /LandMarkView
  GoRoute(
    parentNavigatorKey: parentKey,
    path: RoutesKeys.kLandMarkView,
    pageBuilder: (context, state) => buildPageWithDefaultTransition<void>(
      context: context,
      state: state,
      child: BlocProvider(
        create: (context) => GetLandmarksCubit(getIt<LandmarksRepository>()),
        child: const LandMarkView(),
      ),
    ),
  ),
  //kMyLandMarkView
  GoRoute(
    parentNavigatorKey: parentKey,
    path: RoutesKeys.kMyLandMarkView,
    pageBuilder: (context, state) => buildPageWithDefaultTransition<void>(
      context: context,
      state: state,
      child: BlocProvider(
        create: (context) => GetMyLandmarksCubit(getIt<LandmarksRepository>()),
        child: const MyLandMarkView(),
      ),
    ),
  ),
  //kNewTripPlan
  GoRoute(
    parentNavigatorKey: parentKey,
    path: RoutesKeys.kNewTripPlan,
    pageBuilder: (context, state) => buildPageWithDefaultTransition<void>(
      context: context,
      state: state,
      child: MultiBlocProvider(
        providers: [
          BlocProvider(
            create: (context) => PrepareTripShowCubit(
              getIt<TripRepo>(),
              //token
              state.extra as String,
            ),
          ),

          //SaveTripToEmailCubit
          BlocProvider(
            create: (context) => SaveTripToEmailCubit(getIt<TripRepo>()),
          ),
          //SaveTripV2Cubit
          BlocProvider(create: (context) => SaveTripV2Cubit(getIt<TripRepo>())),
          //ReprepareTripCubit
          BlocProvider(
            create: (context) => ReprepareTripCubit(getIt<TripRepo>()),
          ),
        ],
        child: NewTripPlan(),
      ),
    ),
  ),
  //NewShowMyTrip
  GoRoute(
    parentNavigatorKey: parentKey,
    path: RoutesKeys.kNewShowMyTrip,
    pageBuilder: (context, state) => buildPageWithDefaultTransition<void>(
      context: context,
      state: state,
      child: MultiBlocProvider(
        providers: [
          // BlocProvider(
          //   create: (context) => PrepareTripShowCubit(
          //     getIt<TripRepo>(),
          //     //token
          //     state.extra as String,
          //   ),
          // ),

          //SaveTripToEmailCubit
          BlocProvider(
            create: (context) => SaveTripToEmailCubit(getIt<TripRepo>()),
          ),
          //SaveTripV2Cubit
          BlocProvider(create: (context) => SaveTripV2Cubit(getIt<TripRepo>())),
          //ReprepareTripCubit
          BlocProvider(
            create: (context) => ReprepareTripCubit(getIt<TripRepo>()),
          ),
          // NewViewTripCubit
          BlocProvider(
            create: (context) =>
                NewViewTripCubit(getIt<TripRepo>(), state.extra as String)
                  ..newViewTrip(),
          ),
          //DeleteTripCubit
          BlocProvider(create: (context) => DeleteTripCubit(getIt<TripRepo>())),
          //NewMyTripCubit
          BlocProvider(
            create: (context) => NewMyTripCubit(tripRepo: getIt<TripRepo>()),
          ),
          //SaveTripToEmailCubit
        ],
        child: ShowMyTrip(),
      ),
    ),
  ),

  //MapPickerView
  GoRoute(
    parentNavigatorKey: parentKey,
    path: RoutesKeys.kMapPickerView,
    pageBuilder: (context, state) => buildPageWithDefaultTransition<void>(
      context: context,
      state: state,
      child: MapPickerView(),
    ),
  ),
];

class SeeAllRatingsArgs {
  final List<RatingModel> ratings;
  final String type;
  final String id;

  SeeAllRatingsArgs({
    required this.ratings,
    required this.type,
    required this.id,
  });
}
