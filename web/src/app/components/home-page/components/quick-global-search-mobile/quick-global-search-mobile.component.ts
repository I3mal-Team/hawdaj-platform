import { FormBuilder, FormsModule, ReactiveFormsModule, Validators } from '@angular/forms';
import { PublicService } from 'src/app/modules/shared/services/public.service';
import { Subscription, catchError, finalize, tap } from 'rxjs';
import { ChangeDetectorRef, Component } from '@angular/core';
import { TranslateModule } from '@ngx-translate/core';
import { DropdownModule } from 'primeng/dropdown';
import { SidebarModule } from 'primeng/sidebar';
import { CommonModule, NgOptimizedImage } from '@angular/common';
import { Router } from '@angular/router';
import { AlertsService } from 'src/app/services/alerts.service';
import { PlacesService } from 'src/app/services/places.service';

@Component({
  selector: 'quick-global-search-mobile',
  standalone: true,
  imports: [
    ReactiveFormsModule,
    TranslateModule,
    DropdownModule,
    SidebarModule,
    CommonModule,
    FormsModule,
    NgOptimizedImage
  ],
  templateUrl: './quick-global-search-mobile.component.html',
  styleUrls: ['./quick-global-search-mobile.component.scss']
})
export class QuickGlobalSearchMobileComponent {
  private subscriptions: Subscription[] = [];

  constructor(
    private alertsService: AlertsService,
    private placesService: PlacesService,
    public publicService: PublicService,
    private cdr: ChangeDetectorRef,
    private fb: FormBuilder,
    private router: Router
  ) { }

  searchForm: any = this.fb.group(
    {
      name: ['', {
        validators: [Validators.required],
        updateOn: 'change'
      }],
      region: [null, {
        validators: [Validators.required],
        updateOn: 'change'
      }],
      city: [null, {
        validators: [Validators.required],
        updateOn: 'change'
      }],
    },
  );
  get formControls(): any {
    return this.searchForm?.controls;
  }

  // Regions Variables
  regions: any = [];
  isLoadingRegions: boolean = false;

  // Cities Variables
  cities: any = [];
  isLoadingCities: boolean = false;

  isLoadingSearch: boolean = false;
  displaySearch: boolean = false;

  nameFocus: boolean = false;
  focusRegion: boolean = false;
  focusCity: boolean = false;

  ngOnInit(): void {
    this.getRegions();
  }

  // Start Change Control
  onChangeControl(type: string): void {
    switch (type) {
      case 'name':
        this.handleValidator('name', ['region', 'city']);
        break;
      case 'region':
        this.handleValidator('region', ['name', 'city']);
        break;
      case 'city':
        this.handleValidator('city', ['name', 'region']);
        break;
      default:
        break;
    }
  }
  private handleValidator(target: string, removeValidators: string[]): void {
    const isValid = this.formControls?.[target]?.valid;
    if (isValid) {
      removeValidators.forEach(validator => {
        this.publicService?.removeValidators(this.searchForm, [validator]);
      });
    } else {
      ['name', 'region', 'city']
        .filter(validator => !removeValidators.includes(validator))
        .forEach(validator => {
          this.publicService?.addValidators(this.searchForm, [validator]);
        });
    }
  }
  // End Change Control

  // Start Regions Functions
  getRegions(): void {
    this.isLoadingRegions = true;
    let regionsSubscription: Subscription = this.placesService?.getRegions()
      .pipe(
        tap((res: any) => this.processRegionsResponse(res)),
        catchError(err => this.handleError(err)),
        finalize(() => this.finalizeRegionsLoading())
      ).subscribe();
    this.subscriptions.push(regionsSubscription);
  }
  private processRegionsResponse(response: any): void {
    if (response.code === 200) {
      this.regions = response?.data;
    } else {
      this.handleError(response.message);
      return;
    }
    this.isLoadingRegions = false;
  }
  private finalizeRegionsLoading(): void {
    this.isLoadingRegions = false;
  }
  clearSelectedRegion(event: Event): void {
    event.stopPropagation(); 
    this.searchForm.get('region')?.reset(); 
    this.searchForm.get('city')?.reset(); 
    this.cities = [];
  }
  // End Regions Functions

  // Start Cities Functions
  getCitiesByRegionId(region: any): void {
    this.formControls.city.reset();
    const regionId = region?.value?.id;
    if (regionId) {
      this.isLoadingCities = true;
      let citiesSubscription: Subscription = this.placesService?.getCities(regionId)
        .pipe(
          tap((res: any) => this.processCitiesResponse(res)),
          catchError(err => this.handleError(err)),
          finalize(() => this.finalizeCitiesLoading())
        ).subscribe();
      this.subscriptions.push(citiesSubscription);
    }
  }

  clearSelectedCity(event: Event): void {
    event.stopPropagation(); 
    this.searchForm.get('city')?.reset(); 
  }
  private processCitiesResponse(response: any): void {
    if (response.code === 200) {
      this.cities = response?.data;
    } else {
      this.handleError(response.message);
      return;
    }
    this.isLoadingCities = false;
  }
  private finalizeCitiesLoading(): void {
    this.isLoadingCities = false;
  }
  // End Cities Functions

  // Start Search Function
  search(): void {
    if (this.searchForm.valid) {
      this.isLoadingSearch = true;
      const { name, region, city }: any = this.searchForm?.value;
      const navigationParams: any = {};
      if (name) {
        navigationParams.name = name;
      }
      if (region && region?.id) {
        navigationParams.regionId = region?.id;
      }
      if (city && city.id) {
        navigationParams.cityId = city?.id;
      }
      this.router.navigate(['/search-result', navigationParams]);
      this.isLoadingSearch = false;
    } else {
      this.alertsService?.openToast('info', this.publicService?.translateTextFromJson('validations.enterNameOrLatOrLng'))
    }
  }
  // End Search Function

  explore(): void { }

  /* --- Handle api requests error messages --- */
  private handleError(err: any): any {
    this.setErrorMessage(err || this.publicService.translateTextFromJson('general.errorOccur'));
  }
  private setErrorMessage(message: string): void {
    this.alertsService.openToast('error', 'error', message);
    this.publicService.show_loader.next(false);
  }

  ngOnDestroy(): void {
    this.subscriptions.forEach((subscription: Subscription) => {
      if (subscription && subscription.closed) {
        subscription.unsubscribe();
      }
    });
  }
}
