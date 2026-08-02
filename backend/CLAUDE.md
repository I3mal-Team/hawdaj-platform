# CLAUDE.md — Backend (Laravel) · نقطة دخول

> **هذا الريبو:** Hawdaj Backend + Admin Dashboard (Laravel 8.12) — `/Users/mac/hawdaj-api`.
> **قاعدة المعرفة المشتركة (مصدر الحقيقة الوحيد):** `/Users/mac/hawdaj-docs` (GitHub: `I3mal-Team/hawdaj-docs`).
> التوثيق الكامل **لا يوجد هنا** — كله في الريبو المشترك. اقرأ منه قبل أي مهمة.

## ابدأ من الدستور
اقرأ **`/Users/mac/hawdaj-docs/CLAUDE.md`** (الدستور الكامل). ثم اتبعه.

## تحميل السياق الإلزامي (قبل أي مهمة — لا تتخطَّ)
من `/Users/mac/hawdaj-docs/`:
`FEATURE_INDEX.md` · `FEATURE_GRAPH.md` · `API_INDEX.md` · `DATABASE_INDEX.md` · `SEARCH_INDEX.md` · `PROJECT_PROGRESS.md` · `CHANGE_LOG.md` · `KNOWN_ISSUES.md` · مستند الميزة المتأثّرة (`FEATURES/NN_*.md`) · الـworkflow المناسب (`WORKFLOWS/`) · المعايير (`STANDARDS/`).

## وعي عابر للريبوهات (Cross-Repo)
هذا الريبو **ليس معزولًا** — الـAPI هنا يخدم **الموبايل والويب معًا**. أي تغيير في عقد API/DB يمسّهما.
- Backend `/Users/mac/hawdaj-api` · Mobile `/Users/mac/hawdaj/Untitled` · Web `/Users/mac/hawdaj-frontend`.
قبل أي تعديل: حدّد الأثر على Mobile/Web/DB/APIs/الميزات المشتركة. **القاعدة الذهبية: تغيير عقد API يبدأ من هنا (Backend) ثم يُحدَّث العميلان.** وثّق الأثر قبل التنفيذ.

## مزامنة التوثيق (بعد أي تنفيذ — إلزامي، بلا استئذان)
حدّث `/Users/mac/hawdaj-docs`: مستند الميزة · FEATURE_INDEX · FEATURE_GRAPH · **API_INDEX** (لو تغيّر endpoint) · **DATABASE_INDEX** (لو تغيّر جدول/migration) · PROJECT_PROGRESS · **CHANGE_LOG** (دائمًا) · **KNOWN_ISSUES** (حادثة) · الـworkflow. **المهمة لا تكتمل قبل مزامنة التوثيق.**

## القاعدة
لا تنفّذ كودًا قبل قراءة `/Users/mac/hawdaj-docs/CLAUDE.md` واتّباع خطواته حتى الموافقة الصريحة.
> ملاحظة: تذكرة قائمة بهذا الريبو: `TICKET_supervisor_login_email.md` (بريد إنشاء المستخدم/SMTP).
