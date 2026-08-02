import 'package:easy_localization/easy_localization.dart';
import 'package:flutter/material.dart';
import 'package:flutter_bloc/flutter_bloc.dart';
import 'package:flutter_screenutil/flutter_screenutil.dart';
import 'package:hawdaj/features/exploration/presentation/manager/get_search_global_cubit/get_search_global_cubit.dart';
import 'package:hawdaj/features/exploration/presentation/manager/map_cubit/map_cubit.dart'
    show MapCubit;

class MultiSelectPillsBar extends StatelessWidget {
  const MultiSelectPillsBar({super.key});

  @override
  Widget build(BuildContext context) {
    final mapCubit = context.watch<MapCubit>();

    final options = <_Opt>[
      _Opt(label: 'filter_events', value: 'events'),
      _Opt(label: 'filter_places', value: 'places'),
      _Opt(label: 'filter_stores', value: 'stores'),
      _Opt(label: 'filter_zads', value: 'zads'),
      _Opt(label: 'filter_all', value: 'all'),
    ];

    final selected = mapCubit.viewAs.isEmpty
        ? <String>{}
        : mapCubit.viewAs.split(',').where((e) => e.isNotEmpty).toSet();

    return SizedBox(
      height: 40.h,
      child: ListView.separated(
        reverse: true, // RTL يحط العناصر من اليمين
        padding: EdgeInsets.symmetric(horizontal: 12.w),
        scrollDirection: Axis.horizontal,
        itemBuilder: (_, i) {
          final o = options[i];
          final isSel =
              selected.contains(o.value) ||
              (o.value == 'all' && selected.isEmpty);

          return _FilterPill(
            label: o.label.tr(context: context),
            selected: isSel,
            onTap: () {
              final set = {...selected};

              if (o.value == 'all') {
                set.clear();
              } else {
                if (set.contains(o.value)) {
                  set.remove(o.value);
                } else {
                  set.add(o.value);
                }
              }

              mapCubit.changeViewAs(set.isEmpty ? '' : set.join(','));

              final searchCubit = context.read<GetSearchGlobalCubit>();
              searchCubit.refresh();
            },
          );
        },
        separatorBuilder: (_, __) => SizedBox(width: 10.w),
        itemCount: options.length,
      ),
    );
  }
}

class _FilterPill extends StatelessWidget {
  final String label;
  final bool selected;
  final VoidCallback onTap;

  const _FilterPill({
    required this.label,
    required this.selected,
    required this.onTap,
  });

  @override
  Widget build(BuildContext context) {
    return InkWell(
      borderRadius: BorderRadius.circular(16.r),
      onTap: onTap,
      child: AnimatedContainer(
        duration: const Duration(milliseconds: 180),
        padding: EdgeInsets.symmetric(horizontal: 16.w, vertical: 8.h),
        decoration: BoxDecoration(
          color: selected ? const Color(0xFFF0E8FF) : Colors.white,
          borderRadius: BorderRadius.circular(16.r),
          border: Border.all(
            color: selected ? const Color(0xFF7B61FF) : const Color(0xFFE9E9EF),
            width: 1,
          ),
          boxShadow: [
            BoxShadow(
              color: Colors.black.withOpacity(0.05),
              blurRadius: 8,
              offset: const Offset(0, 3),
            ),
          ],
        ),
        child: Text(
          label,
          style: TextStyle(
            fontSize: 16.sp,
            color: selected ? const Color(0xFF5E49CC) : const Color(0xFF222222),
            fontWeight: FontWeight.w600,
          ),
        ),
      ),
    );
  }
}

class _Opt {
  final String label;
  final String value;
  _Opt({required this.label, required this.value});
}
