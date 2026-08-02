import 'dart:io';
import 'package:easy_localization/easy_localization.dart';
import 'package:flutter/material.dart';
import 'package:flutter_bloc/flutter_bloc.dart';
import 'package:equatable/equatable.dart';
import 'package:hawdaj/features/home/data/model/explore_category_model.dart';
import 'package:hawdaj/features/landmarks/data/models/landmark_model.dart';
import 'package:hawdaj/features/landmarks/data/repositories/landmarks_repository_repo.dart';

part 'add_landmark_state.dart';

class AddLandmarkCubit extends Cubit<AddLandmarkState> {
  final LandmarksRepository repository;

  AddLandmarkCubit({
    required this.repository,
    ExploreCategoryModel? selectedCategory,
  }) : super(AddLandmarkInitial()) {
    if (selectedCategory != null) {
      _selectedCategory = selectedCategory;
      categoryController.text = '${selectedCategory.name}';
    }
  }

  // Controllers managed by the cubit
  final nameController = TextEditingController();
  final descriptionController = TextEditingController();
  final addressController = TextEditingController();
  final categoryController = TextEditingController();

  // State variables
  ExploreCategoryModel? _selectedCategory;
  List<File> _selectedImages = [];

  // Getters
  ExploreCategoryModel? get selectedCategory => _selectedCategory;
  List<File> get selectedImages => _selectedImages;

  /// Initialize the cubit
  void init() {
    if (_selectedCategory != null) {
      categoryController.text = _selectedCategory!.name.tr(); // ✅ الترجمة هنا
    }
    emit(AddLandmarkReady(imageCount: _selectedImages.length));
  }

  /// Set the selected category
  void setSelectedCategory(ExploreCategoryModel category) {
    _selectedCategory = category;
    categoryController.text = '${category.name.tr()}'; // ✅ الترجمة هنا فقط
    emit(AddLandmarkReady(imageCount: _selectedImages.length));
  }

  /// Add images to the selected images list
  void addImages(List<File> images) {
    print('addImages called with ${images.length} images');
    _selectedImages.addAll(images);
    print('Total images after adding: ${_selectedImages.length}');
    emit(AddLandmarkReady(imageCount: _selectedImages.length));
  }

  /// Remove an image from the selected images list
  void removeImage(int index) {
    print('removeImage called with index: $index');
    if (index >= 0 && index < _selectedImages.length) {
      _selectedImages.removeAt(index);
      print('Total images after removing: ${_selectedImages.length}');
      emit(AddLandmarkReady(imageCount: _selectedImages.length));
    }
  }

  /// Clear all selected images
  void clearImages() {
    _selectedImages.clear();
    emit(AddLandmarkReady(imageCount: _selectedImages.length));
  }

  /// Validate form data
  bool validateForm() {
    return nameController.text.trim().isNotEmpty &&
        descriptionController.text.trim().isNotEmpty &&
        addressController.text.trim().isNotEmpty;
  }

  /// Create landmark
  Future<void> createLandmark() async {
    if (!validateForm()) {
      emit(AddLandmarkError('يرجى ملء جميع الحقول المطلوبة'));
      return;
    }

    emit(AddLandmarkLoading());

    try {
      // Convert File objects to file paths
      List<String> imagePaths = selectedImages
          .map((file) => file.path)
          .toList();

      final request = CreateLandmarkRequest(
        type: selectedCategory?.type ?? 'store',
        name: nameController.text.trim(),
        description: descriptionController.text.trim(),
        address: addressController.text.trim(),
        images: imagePaths,
      );

      final result = await repository.createLandmark(request);

      result.fold((failure) => emit(AddLandmarkError(failure.errMessage)), (
        response,
      ) {
        if (response.success) {
          emit(
            AddLandmarkSuccess(
              LandmarkModel(
                id: DateTime.now().millisecondsSinceEpoch.toString(),
                name: nameController.text.trim(),
                description: descriptionController.text.trim(),
                address: addressController.text.trim(),
                category:
                    selectedCategory ??
                    ExploreCategoryModel(
                      id: '1',
                      name: 'Store',
                      imagePath: '',
                      routes: '',
                      type: 'store',
                      manor: false,
                    ),
                images: selectedImages.map((file) => file.path).toList(),
                createdAt: DateTime.now(),
                updatedAt: DateTime.now(),
              ),
            ),
          );
        } else {
          emit(AddLandmarkError(response.message));
        }
      });
    } catch (e) {
      emit(AddLandmarkError('حدث خطأ غير متوقع: ${e.toString()}'));
    }
  }

  /// Reset form to initial state
  void _resetForm() {
    nameController.clear();
    descriptionController.clear();
    addressController.clear();
    _selectedCategory = null;
    _selectedImages.clear();
  }

  /// Reset state to initial
  void resetState() {
    _resetForm();
    emit(AddLandmarkInitial());
  }

  @override
  Future<void> close() {
    nameController.dispose();
    descriptionController.dispose();
    addressController.dispose();
    return super.close();
  }
}
