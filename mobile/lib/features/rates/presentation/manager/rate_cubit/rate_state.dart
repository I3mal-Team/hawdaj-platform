part of 'rate_cubit.dart';

abstract class RateState extends Equatable {
  const RateState();
  @override
  List<Object?> get props => [];
}

class RateInitial extends RateState {}

class RateLoading extends RateState {}

class RateReady extends RateState {
  final String name;
  final String email;
  final double rating;

  const RateReady({
    required this.name,
    required this.email,
    required this.rating,
  });

  RateReady copyWith({String? name, String? email, double? rating}) {
    return RateReady(
      name: name ?? this.name,
      email: email ?? this.email,
      rating: rating ?? this.rating,
    );
  }

  @override
  List<Object?> get props => [name, email, rating];
}

class RateAdded extends RateState {}

class RateError extends RateState {
  final String message;
  const RateError(this.message);

  @override
  List<Object?> get props => [message];
}
