import 'package:hawdaj/core/components/pop_up/pop_up_item.dart';
import 'package:hawdaj/core/components/pop_up/pop_up_wrapper.dart';
import 'package:flutter/material.dart';

class CauseActionsPopup extends StatelessWidget {
  const CauseActionsPopup({super.key});

  @override
  Widget build(BuildContext context) {
    return PopUpWrapper(
      autoClose: true,
      items: [
        GlobalPopUpData(
          title: 'تعيين مستخدم',
          iconName: 'at',
          onTap: () async {},
        ),
        GlobalPopUpData(
          title: 'إنشاء قضية استئناف',
          iconName: 'add-cause',
          onTap: () {},
        ),
      ],
      child: SizedBox(
        // onTap: context.read<CauseCellCubit>().toggleOpen,
        width: 50,
        child: Image.asset('assets/icons/dots.png', width: 24, height: 24),
      ),
    );
  }
}
