import 'dart:convert';

import 'package:bloc/bloc.dart';
import 'package:equatable/equatable.dart';
import 'package:flutter/material.dart';
import 'package:hawdaj/features/trip/data/model/trip_model/save_trip_to_email_params.dart';
import 'package:hawdaj/features/trip/data/repo/trip_repo.dart';

part 'save_trip_state.dart';

class SaveTripCubit extends Cubit<SaveTripState> {
  SaveTripCubit(this.tripRepo) : super(SaveTripInitial());

  final TripRepo tripRepo;

  /// Controller for trip name input
  final TextEditingController tripNameController = TextEditingController();

  void dispose() {
    tripNameController.dispose();
  }

  /// Simple validation for required fields
  String? validateInputs() {
    if (tripNameController.text.trim().isEmpty) {
      return 'اسم الرحلة مطلوب';
    }
    return null;
  }

  /// Build request params from structured items
  SaveTripToEmailParams buildParams({
    required String date,
    required String days,
    required String endDate,
    required String itemPerDay,
    required List<List<int>> items,
    required String region1,
    required String region2,
    required String startDate,
    String? prepareToken,
    int? userId,
  }) {
    return SaveTripToEmailParams(
      date: date,
      days: days,
      email: null, // غير مطلوب في هذا الاستخدام
      endDate: endDate,
      itemPerDay: itemPerDay,
      items: items,
      name: tripNameController.text.trim(),
      region1: region1,
      region2: region2,
      startDate: startDate,
      userName: null,
      prepareToken: prepareToken,
      userId: userId,
    );
  }

  /// Build request params when items come as a JSON string
  SaveTripToEmailParams buildParamsFromItemsString({
    required String date,
    required String days,
    required String endDate,
    required String itemPerDay,
    required String itemsAsJsonString,
    required String region1,
    required String region2,
    required String startDate,
    String? prepareToken,
    int? userId,
  }) {
    final decoded = (itemsAsJsonString.trim().isNotEmpty)
        ? (jsonDecode(itemsAsJsonString) as List)
        : const <dynamic>[];

    final items = decoded
        .map<List<int>>(
          (inner) => (inner as List)
              .map<int>((e) => int.tryParse(e.toString()) ?? 0)
              .toList(),
        )
        .toList();

    return buildParams(
      date: date,
      days: days,
      endDate: endDate,
      itemPerDay: itemPerDay,
      items: items,
      region1: region1,
      region2: region2,
      startDate: startDate,
      prepareToken: prepareToken,
      userId: userId,
    );
  }

  /// Submit using items as provided
  Future<void> submit({
    required String date,
    required String days,
    required String endDate,
    required String itemPerDay,
    required List<List<int>> items,
    required String region1,
    required String region2,
    required String startDate,
    String? prepareToken,
    int? userId,
  }) async {
    final validation = validateInputs();
    if (validation != null) {
      emit(SaveTripError(validation));
      return;
    }

    final body = buildParams(
      date: date,
      days: days,
      endDate: endDate,
      itemPerDay: itemPerDay,
      items: items,
      region1: region1,
      region2: region2,
      startDate: startDate,
      prepareToken: prepareToken,
      userId: userId,
    );

    await saveTrip(body);
  }

  /// Core API call
  Future<void> saveTrip(SaveTripToEmailParams body) async {
    emit(SaveTripLoading());
    final result = await tripRepo.saveTrip(body);
    result.fold(
      (failure) => emit(SaveTripError(failure.errMessage)),
      (success) => emit(SaveTripSuccess(success)),
    );
  }

  // ---------------------------------------------------------------------------
  // Deletion helpers BEFORE submit
  // ---------------------------------------------------------------------------

  /// Remove a specific item by index from items (defensive copy)
  /// - [dayIndex]: index of the day (outer list)
  /// - [itemIndex]: index within that day's list (inner list)
  /// If a day becomes empty after deletion, it will be removed as well.
  List<List<int>> removeItemFromItems(
    List<List<int>> items, {
    required int dayIndex,
    required int itemIndex,
  }) {
    final updated = List<List<int>>.from(
      items.map((day) => List<int>.from(day)),
    );

    if (dayIndex >= 0 &&
        dayIndex < updated.length &&
        itemIndex >= 0 &&
        itemIndex < updated[dayIndex].length) {
      updated[dayIndex].removeAt(itemIndex);
      if (updated[dayIndex].isEmpty) {
        updated.removeAt(dayIndex);
      }
    }
    return updated;
  }

  /// Remove the first occurrence of a value within a specific day
  /// Returns a new list with the change applied.
  List<List<int>> removeValueFromDay(
    List<List<int>> items, {
    required int dayIndex,
    required int value,
  }) {
    final updated = List<List<int>>.from(
      items.map((day) => List<int>.from(day)),
    );

    if (dayIndex >= 0 && dayIndex < updated.length) {
      updated[dayIndex].remove(value);
      if (updated[dayIndex].isEmpty) {
        updated.removeAt(dayIndex);
      }
    }
    return updated;
  }

  /// Remove all occurrences of a given value across all days
  /// Empty days are pruned.
  List<List<int>> removeValueEverywhere(
    List<List<int>> items, {
    required int value,
  }) {
    final updated = items
        .map((day) => day.where((e) => e != value).toList())
        .where((day) => day.isNotEmpty)
        .map((day) => List<int>.from(day))
        .toList();
    return updated;
  }

  /// Convenience: delete then submit in one shot
  Future<void> removeAndSubmit({
    required String date,
    required String days,
    required String endDate,
    required String itemPerDay,
    required List<List<int>> items,
    required String region1,
    required String region2,
    required String startDate,
    String? prepareToken,
    int? userId,
    // Deletion selectors (one of them or both)
    int? dayIndex,
    int? itemIndex,
    int? valueToRemoveEverywhere,
  }) async {
    var updated = items;

    // If both dayIndex & itemIndex are provided -> remove by index
    if (dayIndex != null && itemIndex != null) {
      updated = removeItemFromItems(
        updated,
        dayIndex: dayIndex,
        itemIndex: itemIndex,
      );
    }

    // If a global value removal is requested
    if (valueToRemoveEverywhere != null) {
      updated = removeValueEverywhere(updated, value: valueToRemoveEverywhere);
    }

    await submit(
      date: date,
      days: days,
      endDate: endDate,
      itemPerDay: itemPerDay,
      items: updated,
      region1: region1,
      region2: region2,
      startDate: startDate,
      prepareToken: prepareToken,
      userId: userId,
    );
  }
}
