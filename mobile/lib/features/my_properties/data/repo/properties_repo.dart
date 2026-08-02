import 'package:dartz/dartz.dart';
import 'package:hawdaj/core/errors/failure.dart';
import 'package:hawdaj/features/my_properties/data/model/create_property_request.dart';
import 'package:hawdaj/features/my_properties/data/model/my_properties_response.dart';

abstract class PropertiesRepo {
  Future<Either<Failure, String>> addProperties(CreatePropertyRequest param);
  //my-properties
  Future<Either<Failure, MyPropertiesResponse>> getMyProperties();
}
