import 'package:easy_localization/easy_localization.dart';
import 'package:flutter/material.dart';
import 'package:flutter_bloc/flutter_bloc.dart';
import 'package:hawdaj/core/services/service_locator.dart';
import 'package:hawdaj/features/tasneef/data/models/category_model.dart';
import 'package:hawdaj/features/tasneef/domain/repositories/tasneef_repository.dart';
import 'package:hawdaj/features/tasneef/presentation/cubits/categories/categories_state.dart';
import 'package:hawdaj/features/tasneef/presentation/cubits/categories/sub_categories_cubit.dart';
import 'package:hawdaj/features/tasneef/presentation/views/widgets/generic_selection_bottomsheet.dart';
import 'package:hawdaj/core/components/custom_text_field/custom_app_form_text_field.dart';

class ZadFoodCategory extends StatelessWidget {
  final CategoryModel? selectedCategory;

  final Function(CategoryModel?) onChanged;

  const ZadFoodCategory({
    super.key,
    required this.selectedCategory,
    required this.onChanged,
  });

  @override
  Widget build(BuildContext context) {
    return BlocProvider(
      create: (context) =>
          SubCategoriesCubit(repository: getIt<TasneefRepository>())
            ..loadZadFoodCategories(),
      child: _Body(selectedCategory: selectedCategory, onChanged: onChanged),
    );
  }
}

class _Body extends StatelessWidget {
  final Function(CategoryModel?) onChanged;

  final CategoryModel? selectedCategory;

  const _Body({required this.onChanged, required this.selectedCategory});

  @override
  Widget build(BuildContext context) {
    return BlocBuilder<SubCategoriesCubit, CategoriesCState>(
      builder: (context, state) {
        if (state is SubCategoriesLoading) {
          return Center(child: CircularProgressIndicator());
        } else if (state is SubCategoriesSuccess) {
          return GestureDetector(
            onTap: () {
              GenericSelectionBottomModal.showInlineSelection<CategoryModel>(
                context: context,
                items: state.subCategories,
                title: 'type'.tr(),
                displayText: (category) => category.name ?? '',
                onItemSelected: (category) {
                  onChanged(category);
                },
              );
            },
            child: CustomTextField(
              enabled: false,
              hint: 'type'.tr(),
              controller: TextEditingController(
                text: selectedCategory?.name ?? 'type'.tr(),
              ),
            ),
          );
        }

        return Text(state.runtimeType.toString());
      },
    );
  }
}
