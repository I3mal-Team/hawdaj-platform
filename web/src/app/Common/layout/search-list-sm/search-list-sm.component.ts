import { CommonModule, isPlatformBrowser } from '@angular/common';
import { Component, EventEmitter, inject, Input, OnInit, Output, PLATFORM_ID, SimpleChanges } from '@angular/core';
import { FormBuilder, FormGroup } from '@angular/forms';
import { ReactiveFormsModule } from '@angular/forms';
import { TranslateModule } from '@ngx-translate/core';
import { DropdownModule } from 'primeng/dropdown';
import { PublicService } from 'src/app/modules/shared/services/public.service';
import { SidebarModule } from 'primeng/sidebar';
import { FormInputConfig } from '../../interfaces/FormInputConfig';
import { Router } from '@angular/router';
import { CalendarModule } from 'primeng/calendar';
import { MultiSelectModule } from 'primeng/multiselect';

@Component({
  selector: 'app-search-list-sm',
  standalone: true,
  imports: [CommonModule, ReactiveFormsModule, DropdownModule, TranslateModule, SidebarModule, CalendarModule, MultiSelectModule],
  templateUrl: './search-list-sm.component.html',
  styleUrls: ['./search-list-sm.component.scss']
})
export class SearchListSmComponent {
  @Input() fieldsConfig: FormInputConfig[] = [];
  @Input() searchTitle: string;
  @Input() searchSubtitle: string;

  @Input() defaultSelectId: number;
  @Output() fieldChanged: EventEmitter<any> = new EventEmitter();
  @Output() formValues: EventEmitter<any> = new EventEmitter();
  @Output() clearEvent: EventEmitter<any> = new EventEmitter();
  searchSection: boolean = true;
  focusedField: string | null = null;
  searchPerformed: boolean = false;

  displaySearch: boolean = false;

  private platformId = inject(PLATFORM_ID);
  private router = inject(Router);
  public publicService = inject(PublicService);

  searchForm!: FormGroup;
  focusStates: { [key: string]: boolean } = {};

  constructor(private fb: FormBuilder) { }

  ngOnInit(): void {
    this.searchForm = this.fb.group(
      this.fieldsConfig?.reduce((acc, field) => {
        acc[field?.name] = [field?.defaultValue || null, field?.validation || []];
        return acc;
      }, {})
    );
    const regionControl = this.searchForm.get('region');
    const cityControl = this.searchForm.get('city');

    if (!regionControl?.value) {
      const cityField = this.fieldsConfig.find(field => field.name === 'city');
      if (cityField) {
        cityField.placeholder = cityField.hint;
      }
      cityControl?.disable();
    }

    regionControl?.valueChanges.subscribe(value => {
      if (!value) {
        cityControl?.setValue(null);
        cityControl?.disable();
        this.fieldsConfig.find(field => field.name === 'city').placeholder = 'placeholder.selectRegionFirst';
      } else {
        cityControl?.enable();
        this.fieldsConfig.find(field => field.name === 'city').placeholder = 'placeholder.selectCity';
      }
    });
  }

  search() {
    this.formValues.emit(this.searchForm)
    if (this.searchForm.valid) {
      this.searchPerformed = true;
      this.displaySearch = false;
    }
  }

  onChangeControl(fieldName: string) {
    const formControls = this.searchForm.controls;
    if (fieldName === 'region') {
      if (formControls?.['region']?.valid) {
        this.publicService?.removeValidators(this.searchForm, ['placeName']);
        this.publicService?.removeValidators(this.searchForm, ['category']);
      } else {
        this.publicService?.addValidators(this.searchForm, ['placeName']);
        this.publicService?.addValidators(this.searchForm, ['category']);
      }
    }

    if (fieldName === 'placeName') {
      if (formControls?.['placeName']?.valid) {
        this.publicService?.removeValidators(this.searchForm, ['region']);
        this.publicService?.removeValidators(this.searchForm, ['category']);
        this.clearEvent.emit(this.searchForm)
      } else {
        this.publicService?.addValidators(this.searchForm, ['region']);
        this.publicService?.addValidators(this.searchForm, ['category']);
      }
    }
    if (fieldName === 'type') {
      if (formControls?.['type']?.valid) {
        this.publicService?.removeValidators(this.searchForm, ['placeName']);
        this.publicService?.removeValidators(this.searchForm, ['region']);
        this.publicService?.removeValidators(this.searchForm, ['category']);
        this.clearEvent.emit(this.searchForm)
      } else {
        this.publicService?.removeValidators(this.searchForm, ['placeName']);
        this.publicService?.addValidators(this.searchForm, ['region']);
        this.publicService?.addValidators(this.searchForm, ['category']);
      }
    }
    this.fieldChanged.emit({ fieldName, value: formControls[fieldName].value });
  }
  isFormFilled(): boolean {
    return this.searchPerformed;
  }
  getSelectedValues(fieldName: string): string {
    const values = this.searchForm.value[fieldName];
    return values && values.length > 0
      ? values.map((item: any) => item.name).join(', ')
      : this.publicService?.translateTextFromJson(this.fieldsConfig.find(field => field.name === fieldName)?.placeholder);
  }

  clearField(fieldName: string, event: Event) {
    event.stopPropagation();
    this.searchForm.get(fieldName)?.setValue(null);
    if (fieldName === 'region') {
      this.searchForm.get('city')?.setValue(null);
    }
    if (fieldName === 'type') {
      this.searchForm.get('type')?.setValue(null);
    }
    if (fieldName != 'region') {
      this.search();
      return;
    }
    this.clearEvent.emit(this.searchForm)
  }

  clearAll() {
    this.searchPerformed = false;
    this.searchForm.reset();
    this.clearEvent.emit();
    this.displaySearch = false;

    const cityControl = this.searchForm.get('city');
    if (cityControl) {
      cityControl.disable();
    }

    const cityField = this.fieldsConfig.find(field => field.name === 'city');
    if (cityField) {
      cityField.placeholder = 'placeholder.selectRegionFirst';
    }
  }

}
