import 'package:easy_localization/easy_localization.dart';
import 'package:firebase_core/firebase_core.dart';
import 'package:flutter_dotenv/flutter_dotenv.dart';
import 'package:geolocator/geolocator.dart';
import 'package:hawdaj/core/locale/locale_cubit.dart';
import 'package:hawdaj/core/managers/cities_cubit/cities_cubit.dart';
import 'package:hawdaj/core/managers/connectivity_cubit/connectivity_cubit.dart';
import 'package:hawdaj/core/managers/location_cubit/location_cubit.dart';
import 'package:hawdaj/core/managers/nav_bar_cubit/nav_bar_cubit.dart';
import 'package:hawdaj/core/managers/user_info_cubit/user_info_cubit.dart';
import 'package:hawdaj/core/repositories/cities_repository.dart';
import 'package:hawdaj/core/repositories/location_repository.dart';
import 'package:hawdaj/core/routing/app_router.dart';
import 'package:hawdaj/core/routing/routes_keys.dart';
import 'package:hawdaj/core/services/service_locator.dart';
import 'package:hawdaj/core/styles/app_colors.dart';
import 'package:hawdaj/core/utils/app_environment_manager.dart';
import 'package:hawdaj/core/utils/app_fonts.dart';
import 'package:hawdaj/core/utils/storage_service.dart';
import 'package:hawdaj/features/auth/domain/repositories/auth_repository.dart';
import 'package:hawdaj/features/auth/presentation/manager/cubit/register/register_cubit.dart';
import 'package:hawdaj/features/auth/presentation/manager/cubits/delete_profile_cubit/delete_profile_cubit.dart';
import 'package:hawdaj/features/auth/presentation/manager/cubits/login_cubit/login_cubit.dart';
import 'package:hawdaj/features/auth/presentation/manager/cubits/logout_cubit/logout_cubit.dart';
import 'package:hawdaj/features/auth/presentation/manager/cubits/social_auth_cubit/social_auth_cubit.dart';
import 'package:hawdaj/features/auth/services/social_auth_service.dart';
import 'package:hawdaj/features/favorites/data/repo/favorite_repo.dart';
import 'package:hawdaj/features/favorites/presentation/manager/favorite_cubit/favorite_cubit.dart';
import 'package:hawdaj/features/home/data/repo/home_repo.dart';
import 'package:hawdaj/features/home/presentation/manager/home_cubit/home_cubit.dart';
import 'package:hawdaj/features/home/presentation/manager/sliders_cubit/sliders_cubit.dart';
import 'package:hawdaj/features/rates/data/repo/rate_repo.dart';
import 'package:hawdaj/features/rates/presentation/manager/rate_cubit/rate_cubit.dart';
import 'package:hawdaj/features/saved/data/repo/saved_repo.dart';
import 'package:hawdaj/features/saved/presentation/manager/saved_cubit.dart';
import 'package:hawdaj/core/managers/regions_cubit/regions_cubit.dart';
import 'package:hawdaj/features/force_update/data/repo/force_update_repo.dart';
import 'package:hawdaj/features/force_update/presentation/cubit/force_update_cubit.dart';
import 'package:hawdaj/firebase_options.dart';
import 'package:hawdaj/utils/device_utils.dart';
import 'package:flutter/material.dart';
import 'package:flutter_bloc/flutter_bloc.dart';
import 'package:flutter_screenutil/flutter_screenutil.dart';
import 'package:go_router/go_router.dart';
import 'package:oktoast/oktoast.dart';

void main() async {
  WidgetsFlutterBinding.ensureInitialized();
  await EasyLocalization.ensureInitialized(); // ✅ ADD THIS

  // Load environment variables
  await dotenv.load(fileName: ".env");

  await Firebase.initializeApp(options: DefaultFirebaseOptions.currentPlatform);
  // Initialize app environment
  await AppEnvironmentManager().init();
  setupServiceLocator();

  // Initialize social auth service
  getIt<SocialAuthService>().initialize();

  await StorageService.init();
  initDeviceId();
  await GeolocatorPlatform.instance
      .isLocationServiceEnabled(); // optional check

  // ✅ WRAP WITH EasyLocalization
  runApp(
    EasyLocalization(
      supportedLocales: const [
        Locale('ar'),
        Locale('en'),
        Locale('ru'),
        Locale('zh'),
      ],
      path: 'assets/translations',
      fallbackLocale: const Locale('en'),
      startLocale: const Locale('ar'),

      child: const HawdajApp(),
    ),
  );
}

class HawdajApp extends StatefulWidget {
  const HawdajApp({super.key});

  @override
  State<HawdajApp> createState() => _HawdajAppState();
}

class _HawdajAppState extends State<HawdajApp> {
  @override
  Widget build(BuildContext context) {
    return ScreenUtilInit(
      designSize: const Size(393, 852),
      child: GestureDetector(
        onTap: () {
          FocusScopeNode currentFocus = FocusScope.of(context);
          if (!currentFocus.hasPrimaryFocus) {
            currentFocus.unfocus();
            FocusManager.instance.primaryFocus?.unfocus();
          }
        },
        child: MultiBlocProvider(
          providers: [
            // ✅ ADD LocaleCubit FIRST
            BlocProvider(
              create: (context) => LocaleCubit()..loadSavedLanguage(),
            ),
            BlocProvider(
              create: (context) => LocationCubit(
                locationRepository: getIt<LocationRepository>(),
              ),
            ),

            // All other Cubits
            BlocProvider(create: (_) => RateCubit(getIt<RateRepo>())),
            BlocProvider(create: (_) => NavBarCubit()),
            BlocProvider<ConnectivityCubit>(
              create: (context) => ConnectivityCubit(),
            ),
            BlocProvider(create: (context) => SavedCubit(getIt<SavedRepo>())),
            BlocProvider<HomeCubit>(
              create: (context) => HomeCubit(getIt<HomeRepo>())..fetchHome(),
            ),
            BlocProvider<SlidersCubit>(
              create: (context) =>
                  SlidersCubit(getIt<HomeRepo>())..fetchSliders(),
            ),
            BlocProvider<RegisterCubit>(
              create: (context) =>
                  RegisterCubit(authRepository: getIt<AuthRepository>()),
            ),
            //CitiesCubit
            BlocProvider<CitiesCubit>(
              create: (context) =>
                  CitiesCubit(citiesRepository: getIt<CitiesRepository>()),
            ),
            BlocProvider<LoginCubit>(
              create: (context) => LoginCubit(getIt<AuthRepository>()),
            ),
            BlocProvider<SocialAuthCubit>(
              create: (context) => SocialAuthCubit(
                getIt<AuthRepository>(),
                getIt<SocialAuthService>(),
              ),
            ),
            BlocProvider<LogoutCubit>(
              create: (context) => LogoutCubit(getIt<AuthRepository>()),
            ),
            BlocProvider<DeleteProfileCubit>(
              create: (context) => DeleteProfileCubit(getIt<AuthRepository>()),
            ),
            BlocProvider(
              create: (context) => FavoriteCubit(getIt<FavoriteRepo>()),
            ),
            BlocProvider<RegionsCubit>(
              create: (context) {
                return getIt<RegionsCubit>()..fetchRegions();
              },
            ),
            BlocProvider(
              create: (context) => UserInfoCubit(getIt<AuthRepository>()),
            ),
            BlocProvider<ForceUpdateCubit>(
              create: (context) => ForceUpdateCubit(getIt<ForceUpdateRepo>()),
            ),
          ],
          child: MultiBlocListener(
            listeners: [
              // ✅ Connectivity Listener
              BlocListener<ConnectivityCubit, ConnectivityStatus>(
                listener: (context, state) {
                  final parentContext = parentKey.currentContext;
                  if (parentContext == null) return; // 🔒 حماية من null

                  if (state == ConnectivityStatus.disconnected) {
                    GoRouter.of(parentContext).push(RoutesKeys.kNoInternetView);
                  } else {
                    if (GoRouter.of(parentContext).canPop()) {
                      GoRouter.of(parentContext).pop();
                    }
                  }
                },
              ),

              // ✅ ADD Locale Listener
              // ✅ ENHANCED Locale Listener مع Auto Refresh
              BlocListener<LocaleCubit, LocaleState>(
                listener: (context, state) async {
                  try {
                    if (state is LocaleLoaded) {
                      await context.setLocale(state.locale);

                      // ✅ Auto Refresh Data بعد تغيير اللغة
                      try {
                        print('🔄 Refreshing data after language change...');

                        // Refresh Home Data
                        context.read<HomeCubit>().fetchHome();
                        context.read<SlidersCubit>().fetchSliders();

                        // Refresh Regions Data
                        context.read<RegionsCubit>().fetchRegions();
                        // context.read<GetSearchGlobalCubit>().refresh();

                        // أضف أي cubit تاني محتاج refresh هنا:
                        // context.read<RateCubit>().fetchRates();
                        // context.read<SavedCubit>().getSaved();
                        // context.read<FavoriteCubit>().getFavorites();

                        print('✅ Data refresh completed');
                      } catch (refreshError) {
                        print('❌ Error refreshing data: $refreshError');
                      }
                    } else if (state is LocaleError) {
                      ScaffoldMessenger.of(context).showSnackBar(
                        SnackBar(
                          content: Text(state.message),
                          backgroundColor: Colors.red,
                        ),
                      );
                    }
                  } catch (e) {
                    debugPrint('Error in LocaleCubit listener: $e');
                  }
                },
              ),

              // BlocListener<LocaleCubit, LocaleState>(
              //   listener: (context, state) {
              //     if (state is LocaleLoaded) {
              //       context.setLocale(state.locale);
              //     } else if (state is LocaleError) {
              //       if (mounted) {
              //         showCustomFailureToast(state.message);
              //       }
              //     }
              //   },
              // ),
            ],
            child: BlocBuilder<LocaleCubit, LocaleState>(
              builder: (context, localeState) {
                return OKToast(
                  child: MaterialApp.router(
                    theme: ThemeData(
                      colorScheme: ColorScheme.fromSeed(
                        seedColor: AppColors.primary,
                      ),
                      useMaterial3: true,
                      textTheme: ThemeData.light().textTheme.apply(
                        fontFamily: AppFonts.brandoArabic,
                      ),
                      scaffoldBackgroundColor: Colors.white,
                    ),
                    debugShowCheckedModeBanner: false,
                    routerConfig: AppRouter.router,

                    // ✅ REPLACE OLD LOCALIZATION WITH EasyLocalization
                    locale: context.locale,
                    supportedLocales: context.supportedLocales,
                    localizationsDelegates: context.localizationDelegates,

                    // ✅ REMOVE FIXED RTL BUILDER
                    // The RTL will be handled automatically by easy_localization
                  ),
                );
              },
            ),
          ),
        ),
      ),
    );
  }
}

// ✅ FIXED isRTL function
bool isRTL(BuildContext context) {
  try {
    final localeCubit = context.read<LocaleCubit>();
    return localeCubit.isRTL;
  } catch (e) {
    // Fallback to context.locale if LocaleCubit is not accessible
    return context.locale.languageCode == 'ar';
  }
}

// ✅ Alternative simple version
bool isRTLSimple(BuildContext context) {
  return context.locale.languageCode == 'ar';
}

// bool isRTL(BuildContext context) {
//   return true;






// }

// dart run build_runner build --delete-conflicting-outputs
// dart run build_runner watch --delete-conflicting-outputs

// test card 5123450000000008


// dart pub global activate intl_utils     -- > for first time only
// flutter pub global run intl_utils:generate  -- > after any updates in the keys

// git tag deploy-apple-8.1.1

// git tag deploy-google-8.1.1 
// git push --tags