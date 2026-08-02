import 'dart:io';

import 'package:flutter/material.dart';
import 'package:flutter/services.dart';
import 'package:hawdaj/core/components/custom_success_toast.dart';
import 'package:hawdaj/core/databases/api/dio_consumer.dart';
import 'package:hawdaj/core/services/service_locator.dart';
import 'package:hawdaj/core/utils/app_environment_manager.dart';

class EnvSwitchScreen extends StatefulWidget {
  const EnvSwitchScreen({super.key});

  @override
  State<EnvSwitchScreen> createState() => _EnvSwitchScreenState();
}

class _EnvSwitchScreenState extends State<EnvSwitchScreen> {
  String selectedEnv = AppEnvironmentManager().currentEnv;
  bool chuckerEnabled = AppEnvironmentManager().isChuckerEnabled;

  final String _devPassword = 'dev123';
  final TextEditingController _passwordController = TextEditingController();
  bool _isAuthenticated = false;
  bool _isError = false;

  void _authenticate() {
    final isOk = _passwordController.text.trim() == _devPassword;
    setState(() {
      _isAuthenticated = isOk;
      _isError = !isOk;
    });
    if (isOk) {
      showCustomSuccessToast('تمت المصادقة بنجاح');
      _passwordController.clear();
    }
  }

  void _onEnvChanged(String? newVal) async {
    if (newVal == null) return;
    if (newVal == 'production') {
      await AppEnvironmentManager().setProduction();
    } else {
      await AppEnvironmentManager().setTest();
    }
    getIt<DioConsumer>().refreshBaseUrl();
    setState(() => selectedEnv = newVal);
    showCustomSuccessToast('تم تغيير البيئة بنجاح');
  }

  Future<void> _onChuckerChanged(bool value) async {
    await AppEnvironmentManager().setChuckerEnabled(value);
    getIt<DioConsumer>().refreshChuckerInterceptor();
    setState(() => chuckerEnabled = value);
    showCustomSuccessToast(
      value
          ? 'تم تفعيل Chucker (يرجى إعادة تشغيل التطبيق)'
          : 'تم إلغاء تفعيل Chucker (يرجى إعادة تشغيل التطبيق)',
    );
  }

  @override
  void dispose() {
    _passwordController.dispose();
    super.dispose();
  }

  @override
  Widget build(BuildContext context) {
    final isDark = Theme.of(context).brightness == Brightness.dark;
    return Scaffold(
      appBar: AppBar(
        centerTitle: true,
        title: const Text('إعدادات بيئة المطور'),
        backgroundColor: isDark ? Colors.black : Colors.blue,
        foregroundColor: Colors.white,
        elevation: 3,
        leading: IconButton(
          icon: const Icon(Icons.close),
          onPressed: () => Navigator.pop(context),
        ),
      ),
      body: SafeArea(
        child: Center(
          child: AnimatedContainer(
            duration: const Duration(milliseconds: 400),
            constraints: const BoxConstraints(maxWidth: 400),
            margin: const EdgeInsets.all(24),
            padding: const EdgeInsets.symmetric(vertical: 36, horizontal: 20),
            decoration: BoxDecoration(
              color: isDark ? Colors.black : Colors.white,
              borderRadius: BorderRadius.circular(24),
              boxShadow: [
                BoxShadow(
                  color: isDark
                      ? Colors.black26
                      : Colors.grey.withOpacity(0.09),
                  blurRadius: 21,
                  offset: const Offset(0, 10),
                ),
              ],
            ),
            child: SingleChildScrollView(
              child: Column(
                mainAxisSize: MainAxisSize.min,
                children: [
                  if (!_isAuthenticated) ...[
                    Icon(Icons.lock, size: 54, color: Colors.orange.shade400),
                    const SizedBox(height: 22),
                    Text(
                      'أدخل كلمة مرور المطور',
                      style: TextStyle(
                        fontWeight: FontWeight.w700,
                        fontSize: 16,
                        color: isDark ? Colors.orange : Colors.black,
                      ),
                    ),
                    const SizedBox(height: 10),
                    TextField(
                      controller: _passwordController,
                      obscureText: true,
                      decoration: InputDecoration(
                        border: OutlineInputBorder(),
                        hintText: 'كلمة السر',
                        errorText: _isError ? 'خطأ في كلمة المرور' : null,
                        suffixIcon: IconButton(
                          icon: const Icon(Icons.vpn_key),
                          onPressed: _authenticate,
                          tooltip: 'تحقق',
                        ),
                      ),
                      onSubmitted: (_) => _authenticate(),
                    ),
                    const SizedBox(height: 15),
                    ElevatedButton.icon(
                      style: ElevatedButton.styleFrom(
                        backgroundColor: Colors.blue,
                        minimumSize: const Size.fromHeight(40),
                        textStyle: const TextStyle(fontWeight: FontWeight.w600),
                      ),
                      icon: const Icon(Icons.login),
                      onPressed: _authenticate,
                      label: const Text(
                        'دخول المطور',
                        style: TextStyle(color: Colors.white),
                      ),
                    ),
                  ],
                  if (_isAuthenticated) ...[
                    Icon(
                      selectedEnv == 'production'
                          ? Icons.cloud_done
                          : Icons.bug_report,
                      size: 54,
                      color: selectedEnv == 'production'
                          ? Colors.blue
                          : Colors.red,
                    ),
                    const SizedBox(height: 18),
                    Text(
                      'اختر البيئة:',
                      style: TextStyle(
                        fontSize: 16,
                        fontWeight: FontWeight.w700,
                        color: isDark ? Colors.white : Colors.black87,
                      ),
                    ),
                    const SizedBox(height: 18),
                    DropdownButtonFormField<String>(
                      value: selectedEnv,
                      items: const [
                        DropdownMenuItem(
                          value: 'production',
                          child: Text('بيئة الإنتاج'),
                        ),
                        DropdownMenuItem(
                          value: 'test',
                          child: Text('بيئة الاختبار'),
                        ),
                      ],
                      onChanged: _onEnvChanged,
                      decoration: InputDecoration(
                        filled: true,
                        fillColor: isDark ? Colors.grey[850] : Colors.grey[100],
                        border: OutlineInputBorder(
                          borderRadius: BorderRadius.circular(12),
                          borderSide: BorderSide(
                            color: selectedEnv == 'production'
                                ? Colors.blue
                                : Colors.red,
                          ),
                        ),
                      ),
                      icon: Icon(
                        Icons.keyboard_arrow_down_rounded,
                        color: selectedEnv == 'production'
                            ? Colors.blue
                            : Colors.red,
                      ),
                      dropdownColor: isDark ? Colors.grey[900] : Colors.white,
                      style: TextStyle(
                        color: isDark ? Colors.white : Colors.black,
                        fontWeight: FontWeight.w600,
                      ),
                    ),
                    const SizedBox(height: 22),
                    SwitchListTile(
                      title: const Text('تفعيل مراقبة الشبكة Chucker'),
                      value: chuckerEnabled,
                      onChanged: _onChuckerChanged,
                      activeColor: Colors.green,
                    ),
                    const SizedBox(height: 22),
                    ListTile(
                      contentPadding: EdgeInsets.zero,
                      title: Text(
                        'البيئة الحالية:',
                        style: TextStyle(
                          color: Colors.grey[600],
                          fontWeight: FontWeight.w500,
                          fontSize: 15,
                        ),
                      ),
                      subtitle: Text(
                        AppEnvironmentManager().baseUrl,
                        style: TextStyle(
                          fontWeight: FontWeight.bold,
                          color: selectedEnv == 'production'
                              ? Colors.blue
                              : Colors.red,
                          fontSize: 16,
                        ),
                      ),
                    ),
                    const SizedBox(height: 28),

                    ElevatedButton.icon(
                      icon: const Icon(Icons.restart_alt),
                      label: const Text('إعادة تشغيل التطبيق'),
                      style: ElevatedButton.styleFrom(
                        backgroundColor: Colors.orange,
                        foregroundColor: Colors.white,
                        minimumSize: const Size.fromHeight(43),
                        textStyle: const TextStyle(fontWeight: FontWeight.w600),
                      ),
                      onPressed: () {
                        // يغلق التطبيق تماماً
                        if (Platform.isAndroid) {
                          SystemNavigator.pop(); // الأفضل للاندرويد
                        } else if (Platform.isIOS) {
                          exit(0); // iOS لا يدعم SystemNavigator.pop
                        }
                      },
                    ),
                  ],
                ],
              ),
            ),
          ),
        ),
      ),
    );
  }
}
