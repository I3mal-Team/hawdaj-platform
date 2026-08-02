# CLAUDE.md — Web (Angular) · نقطة دخول

> **هذا الريبو:** Hawdaj Web Frontend (Angular 16 SSR) — `/Users/mac/hawdaj-frontend`.
> **قاعدة المعرفة المشتركة (مصدر الحقيقة الوحيد):** `/Users/mac/hawdaj-docs` (GitHub: `I3mal-Team/hawdaj-docs`).
> التوثيق الكامل **لا يوجد هنا** — كله في الريبو المشترك. اقرأ منه قبل أي مهمة.

## ابدأ من الدستور
اقرأ **`/Users/mac/hawdaj-docs/CLAUDE.md`** (الدستور الكامل). ثم اتبعه. لتفاصيل الويب خصوصًا: `/Users/mac/hawdaj-docs/WEB_FRONTEND.md` + `WEB_MOBILE_COMPARISON.md`.

## تحميل السياق الإلزامي (قبل أي مهمة — لا تتخطَّ)
من `/Users/mac/hawdaj-docs/`:
`FEATURE_INDEX.md` · `FEATURE_GRAPH.md` · `API_INDEX.md` · `DATABASE_INDEX.md` · `SEARCH_INDEX.md` · `PROJECT_PROGRESS.md` · `CHANGE_LOG.md` · `KNOWN_ISSUES.md` · مستند الميزة المتأثّرة (`FEATURES/NN_*.md` + قسم "Web Frontend") · الـworkflow المناسب (`WORKFLOWS/`) · المعايير (`STANDARDS/`).

## وعي عابر للريبوهات (Cross-Repo)
هذا الريبو **ليس معزولًا** — يشارك نفس الـAPI مع الموبايل.
- Web `/Users/mac/hawdaj-frontend` · Backend `/Users/mac/hawdaj-api` · Mobile `/Users/mac/hawdaj/Untitled`.
قبل أي تعديل: حدّد الأثر على Backend/Mobile/DB/APIs/الميزات المشتركة. لو تغيّر عقد API، يبدأ Backend أولًا ثم الويب والموبايل. وثّق الأثر قبل التنفيذ.

## مزامنة التوثيق (بعد أي تنفيذ — إلزامي، بلا استئذان)
حدّث `/Users/mac/hawdaj-docs`: مستند الميزة (+ قسم "Web Frontend") · FEATURE_INDEX · WEB_MOBILE_COMPARISON · PROJECT_PROGRESS · **CHANGE_LOG** (دائمًا) · **KNOWN_ISSUES** (حادثة) · الـworkflow. **المهمة لا تكتمل قبل مزامنة التوثيق.**

## القاعدة
لا تنفّذ كودًا قبل قراءة `/Users/mac/hawdaj-docs/CLAUDE.md` واتّباع خطواته حتى الموافقة الصريحة.
