# Tab List Component

## Description
مكون قائمة التبويبات (Tabs) قابل لإعادة الاستخدام مع دعم الاختيار المفرد والمتعدد.

## Features
- ✅ دعم الاختيار المفرد (Single Selection)
- ✅ دعم الاختيار المتعدد (Multiple Selection)
- ✅ عرض الأيقونات اختياري
- ✅ دعم البادجات (Badges)
- ✅ دعم RTL/LTR
- ✅ تصميم متجاوب (Responsive)
- ✅ تنظيف الكود وسهولة القراءة

## Usage

### Basic Example
```typescript
import { TabListComponent, ITabItem, ITabListConfig } from 'src/app/shared/components/tab-list';

export class MyComponent {
  readonly tabs = signal<ITabItem[]>([
    {
      id: 'tab1',
      label: 'profile.personalInfo',
      icon: 'edit-icon',
      active: true
    },
    {
      id: 'tab2',
      label: 'profile.posts',
      icon: 'camera-icon',
      active: false,
      badge: 5
    }
  ]);

  readonly config = signal<ITabListConfig>({
    isMultiple: false,
    showIcons: true,
    customClass: 'my-custom-class'
  });

  onTabChange(tab: ITabItem | ITabItem[]): void {
    console.log('Selected tab:', tab);
  }
}
```

### Template
```html
<app-tab-list
  [tabItems]="tabs()"
  [tabConfig]="config()"
  (tabChange)="onTabChange($event)"
  (tabClick)="onTabClick($event)">
</app-tab-list>
```

## API

### Inputs

| Input | Type | Required | Description |
|-------|------|----------|-------------|
| `tabItems` | `ITabItem[]` | Yes | قائمة التبويبات |
| `tabConfig` | `ITabListConfig` | No | إعدادات المكون |

### Outputs

| Output | Type | Description |
|--------|------|-------------|
| `tabChange` | `EventEmitter<ITabItem \| ITabItem[]>` | يُطلق عند تغيير التحديد |
| `tabClick` | `EventEmitter<ITabItem>` | يُطلق عند النقر على أي تبويب |

### Interfaces

#### ITabItem
```typescript
interface ITabItem {
  id: string;           // معرف فريد
  label: string;        // النص المعروض
  icon?: string;        // اسم الأيقونة
  active: boolean;      // حالة التفعيل
  disabled?: boolean;   // حالة التعطيل
  badge?: number;       // عدد البادج
  data?: any;          // بيانات مخصصة
}
```

#### ITabListConfig
```typescript
interface ITabListConfig {
  isMultiple?: boolean;   // اختيار متعدد
  showIcons?: boolean;    // عرض الأيقونات
  customClass?: string;   // فئات CSS مخصصة
}
```

## Styling

يمكن تخصيص الألوان والأبعاد من خلال CSS Variables:

```scss
.tab-list {
  --spacing-xxs: 0.125rem;
  --spacing-md: 0.5rem;
  --Font-size-text-md: 1rem;
  --Line-height-text-md: 1.5rem;
}
```

## Examples

### Single Selection (Default)
```typescript
config = { isMultiple: false }
```

### Multiple Selection
```typescript
config = { isMultiple: true }
```

### Without Icons
```typescript
config = { showIcons: false }
```

## Browser Support
- ✅ Chrome (Latest)
- ✅ Firefox (Latest)
- ✅ Safari (Latest)
- ✅ Edge (Latest)




