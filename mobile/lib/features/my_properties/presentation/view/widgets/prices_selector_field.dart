import 'package:easy_localization/easy_localization.dart';
import 'package:flutter/material.dart';
import 'package:flutter_bloc/flutter_bloc.dart';
import 'package:flutter_screenutil/flutter_screenutil.dart';
import 'package:hawdaj/core/components/custom_text_field/custom_app_form_text_field.dart';
import 'package:hawdaj/core/components/pop_up/pop_up_item.dart';
import 'package:hawdaj/core/components/pop_up/pop_up_wrapper.dart';
import 'package:hawdaj/core/styles/app_colors.dart';
import 'package:hawdaj/core/utils/app_assets.dart';
import 'package:hawdaj/features/my_properties/presentation/manager/add_property_form_cubit/add_property_form_cubit.dart';
import 'package:hawdaj/features/tour%D9%80guide/presentation/view/widgets/section_field.dart';
import 'package:hawdaj/features/trip/presentation/manager/fetch_prices_cubit/fetch_prices_cubit.dart';

class PricesSelectorField extends StatefulWidget {
  const PricesSelectorField({super.key});

  @override
  State<PricesSelectorField> createState() => _PricesSelectorFieldState();
}

class _PricesSelectorFieldState extends State<PricesSelectorField> {
  final TextEditingController _controller = TextEditingController();

  void _updateText(List<GlobalPopUpData> items, int? selectedId) {
    final selected = items.firstWhere(
      (e) => e.id == selectedId,
      orElse: () => GlobalPopUpData(id: 0, title: ''),
    );
    _controller.text = selected.title;
  }

  @override
  Widget build(BuildContext context) {
    return BlocBuilder<FetchPricesCubit, FetchPricesState>(
      builder: (context, state) {
        if (state is FetchPricesLoading) {
          return const Center(child: CircularProgressIndicator());
        } else if (state is FetchPricesError) {
          return Text('فشل تحميل الأسعار: ${state.message}');
        } else if (state is FetchPricesSuccess) {
          final cubit = context.read<AddPropertyFormCubit>();
          final selectedId = cubit.state.priceId;

          final items = state.pricesModel.map((price) {
            return GlobalPopUpData(id: price.id ?? 0, title: price.name ?? '');
          }).toList();

          // تحديث النص داخل الكنترولر
          _updateText(items, selectedId);

          return SectionField(
            label: 'choose_price_category'.tr(),
            child: PopUpWrapper(
              selectedId: selectedId,
              items: items,
              onChanged: (selected) {
                cubit.updatePriceId(selected.id);
                _controller.text = selected.title;
              },
              child: CustomTextField(
                hint: 'choose_price_category'.tr(),
                controller: _controller,
                enabled: false,
                allowUpperHint: false,
                style: TextStyle(color: AppColors.uiBlack),
                leadingIconPath: AppAssets.coinText,
                trailing: Image.asset(AppAssets.arrowDown, width: 20.w),
              ),
            ),
          );
        }

        return const SizedBox.shrink();
      },
    );
  }

  @override
  void dispose() {
    _controller.dispose();
    super.dispose();
  }
}
