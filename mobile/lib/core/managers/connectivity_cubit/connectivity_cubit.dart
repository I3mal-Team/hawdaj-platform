import 'package:flutter_bloc/flutter_bloc.dart';
import 'package:connectivity_plus/connectivity_plus.dart';

class ConnectivityCubit extends Cubit<ConnectivityStatus> {
  final Connectivity _connectivity = Connectivity();
  ConnectivityCubit() : super(ConnectivityStatus.connected) {
    monitorConnectivity();
  }

  void monitorConnectivity() {
    _connectivity.onConnectivityChanged.listen((
      List<ConnectivityResult> result,
    ) {
      emit(
        result.contains(ConnectivityResult.none)
            ? ConnectivityStatus.disconnected
            : ConnectivityStatus.connected,
      );
    });
  }
}

enum ConnectivityStatus { connected, disconnected }
