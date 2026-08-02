import 'package:hawdaj/core/routing/routes_keys.dart';

import 'package:flutter_bloc/flutter_bloc.dart';
import 'package:go_router/go_router.dart';
import 'package:hawdaj/features/exploration/data/repo/exploration_repo.dart';
import 'package:hawdaj/features/exploration/presentation/manager/get_search_global_cubit/get_search_global_cubit.dart';
import 'package:hawdaj/features/exploration/presentation/manager/map_cubit/map_cubit.dart';
import 'package:hawdaj/features/exploration/presentation/view/map_view.dart';
import 'package:hawdaj/features/home/presentation/view/home_view.dart';
import 'package:hawdaj/features/profile/data/repo/profail_repo.dart';
import 'package:hawdaj/features/profile/presentation/manager/get_profile_cubit/get_profile_cubit.dart';
import 'package:hawdaj/features/profile/presentation/views/profile_view.dart';
import 'package:hawdaj/features/tasneef/presentation/views/tasneef_view.dart';
import 'package:hawdaj/features/trip/data/repo/trip_repo.dart' show TripRepo;
import 'package:hawdaj/features/trip/presentation/manager/fetch_category_cubit/fetch_category_cubit.dart';
import 'package:hawdaj/features/trip/presentation/manager/fetch_prices_cubit/fetch_prices_cubit.dart';
import 'package:hawdaj/features/trip/presentation/view/start_trip_view.dart';

import '../services/service_locator.dart';

List<StatefulShellBranch> routesBranches = [
  StatefulShellBranch(
    routes: <RouteBase>[
      GoRoute(
        path: RoutesKeys.kHomeView,
        builder: (context, state) => const HomeView(),
      ),
    ],
  ),
  StatefulShellBranch(
    routes: <RouteBase>[
      GoRoute(
        path: RoutesKeys.kHawdajTasneef,
        builder: (context, state) => const TasneefView(),
      ),
    ],
  ),
  StatefulShellBranch(
    routes: <RouteBase>[
      GoRoute(
        path: RoutesKeys.kStartTripView,
        builder: (context, state) => MultiBlocProvider(
          providers: [
            BlocProvider(
              create: (context) =>
                  FetchCategoryCubit(getIt<TripRepo>())..fetchCategory(),
            ),
            BlocProvider(
              create: (context) =>
                  FetchPricesCubit(getIt<TripRepo>())..fetchPrices(),
            ),
          ],
          child: const StartTripView(),
        ),
      ),
    ],
  ),
  StatefulShellBranch(
    routes: <RouteBase>[
      GoRoute(
        path: RoutesKeys.kMapPage,
        builder: (context, state) {
          final getSearchGlobalCubit = GetSearchGlobalCubit(
            explorationRepo: getIt<ExplorationRepo>(),
          );
          return MultiBlocProvider(
            providers: [
              BlocProvider<GetSearchGlobalCubit>(
                create: (context) => getSearchGlobalCubit,
              ),
              BlocProvider<MapCubit>(
                create: (context) =>
                    MapCubit(searchCubit: getSearchGlobalCubit),
              ),
            ],
            child: const MapView(),
          );
        },
      ),
    ],
  ),
  StatefulShellBranch(
    routes: <RouteBase>[
      GoRoute(
        path: RoutesKeys.kProfileView,
        builder: (context, state) => BlocProvider(
          create: (context) =>
              GetProfileCubit(getIt<ProfileRepo>())..getProfile(),
          child: ProfileView(),
        ),
      ),
    ],
  ),
];
