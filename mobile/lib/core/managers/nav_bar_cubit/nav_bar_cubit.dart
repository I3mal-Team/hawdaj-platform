import 'package:flutter_bloc/flutter_bloc.dart';

class NavBarCubit extends Cubit<bool> {
  NavBarCubit() : super(true); // Initial state: NavBar is visible

  void showNavBar() => emit(true);
  void hideNavBar() => emit(false);
}
