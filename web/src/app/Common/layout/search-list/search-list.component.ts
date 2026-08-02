import { CommonModule, isPlatformBrowser, NgOptimizedImage } from '@angular/common';
import { Component, EventEmitter, inject, Input, OnInit, Output, PLATFORM_ID, SimpleChanges } from '@angular/core';
import { FormBuilder, FormGroup } from '@angular/forms';
import { ReactiveFormsModule } from '@angular/forms';
import { TranslateModule, TranslateService } from '@ngx-translate/core';
import { DropdownModule } from 'primeng/dropdown';
import { PublicService } from 'src/app/modules/shared/services/public.service';
import { FormInputConfig } from '../../interfaces/FormInputConfig';
import { LazyLoadImageDirective } from 'src/app/modules/shared/directives/lazy-load-image.directive';
import { CalendarModule } from 'primeng/calendar';
import { Router } from '@angular/router';
import { MultiSelectModule } from 'primeng/multiselect';


@Component({
  selector: 'app-search-list',
  standalone: true,
  imports: [CommonModule, ReactiveFormsModule, DropdownModule,
    MultiSelectModule, TranslateModule, LazyLoadImageDirective, NgOptimizedImage, CalendarModule],
  templateUrl: './search-list.component.html',
  styleUrls: ['./search-list.component.scss']
})
export class SearchListComponent implements OnInit {
  @Input() fieldsConfig: FormInputConfig[] = [];
  @Input() defaultSelectId: number;
  @Input() title: string;
  @Input() searchTitle: string;
  @Output() fieldChanged: EventEmitter<any> = new EventEmitter();
  @Output() formValues: EventEmitter<any> = new EventEmitter();
  @Output() clearEvent: EventEmitter<any> = new EventEmitter();

  private platformId = inject(PLATFORM_ID);
  private router = inject(Router);
  currentLanguage: string;
  public publicService = inject(PublicService);
  private translateService = inject(TranslateService);

  searchForm!: FormGroup;
  focusStates: { [key: string]: boolean } = {};

  constructor(private fb: FormBuilder) {
    if (isPlatformBrowser(this.platformId)) {
      this.currentLanguage = this.publicService.getCurrentLanguage();
    }
  }
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
        if (this.fieldsConfig.find(field => field.name === 'city')) {
          this.fieldsConfig.find(field => field.name === 'city').placeholder = 'placeholder.selectRegionFirst';
        }
      } else {
        cityControl?.enable();
        if (this.fieldsConfig.find(field => field.name === 'city')) {
          this.fieldsConfig.find(field => field.name === 'city').placeholder = 'placeholder.selectCity';
        }
      }
    });
  }
  search() {
    this.formValues.emit(this.searchForm)
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

      if (!formControls?.['region'].value) {
        this.searchForm.get('city')?.setValue(null);
        this.searchForm.get('city')?.disable();
      } else {
        this.searchForm.get('city')?.enable();
      }
    }

    if (fieldName === 'placeName') {
      if (formControls?.['placeName']?.valid) {
        this.publicService?.removeValidators(this.searchForm, ['region']);
        this.publicService?.removeValidators(this.searchForm, ['category']);
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

    if (fieldName !== 'city') {
      this.fieldChanged.emit({ fieldName, value: formControls[fieldName].value });
    }

    this.search();
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
}
