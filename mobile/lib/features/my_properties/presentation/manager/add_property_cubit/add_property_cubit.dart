import 'package:bloc/bloc.dart';
import 'package:equatable/equatable.dart';
import 'package:hawdaj/features/my_properties/data/model/create_property_request.dart';
import 'package:hawdaj/features/my_properties/data/repo/properties_repo.dart';

part 'add_property_state.dart';

class AddPropertyCubit extends Cubit<AddPropertyState> {
  AddPropertyCubit(this.repo) : super(AddPropertyInitial());
  final PropertiesRepo repo;

  Future<void> addProperty(CreatePropertyRequest param) async {
    emit(AddPropertyLoading());

    final result = await repo.addProperties(param);
    result.fold((failure) => emit(AddPropertyError(failure.errMessage)), (
      data,
    ) {
      emit(AddPropertySuccess(data));
    });
  }
}
