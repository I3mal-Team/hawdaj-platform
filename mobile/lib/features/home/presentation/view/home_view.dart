import 'package:flutter/material.dart';
import 'package:flutter_bloc/flutter_bloc.dart';
import 'package:hawdaj/core/managers/location_cubit/location_cubit.dart';
import 'package:hawdaj/features/home/presentation/view/widgets/home_view_body.dart';

class HomeView extends StatefulWidget {
  const HomeView({super.key});

  @override
  State<HomeView> createState() => _HomeViewState();
}

class _HomeViewState extends State<HomeView> {
  @override
  void initState() {
    super.initState();

    context.read<LocationCubit>().fetchCurrentLocation();
  }

  @override
  Widget build(BuildContext context) {
    return const Scaffold(body: HomeViewBody());
  }
}
