import {
  ChangeDetectionStrategy,
  ChangeDetectorRef,
  Component,
  computed,
  signal,
  inject,
  effect,
  OnInit,
  Output,
  EventEmitter
} from '@angular/core';
import { toSignal } from '@angular/core/rxjs-interop';
import { startWith } from 'rxjs';
import { CommonModule } from '@angular/common';
import {
  FormArray,
  FormControl,
  FormGroup,
  ReactiveFormsModule,
  Validators
} from '@angular/forms';
import { TranslateModule, TranslateService } from '@ngx-translate/core';
import { DialogService } from 'primeng/dynamicdialog';
import { MultiSelectModule } from 'primeng/multiselect';
import { DropdownModule } from 'primeng/dropdown';
import { CalendarModule } from 'primeng/calendar';
import { PropertyItemType } from './property-item-type.enum';
import { SeasonType } from './season-type.enum';
import {
  DepartmentOption,
  PropertyUploadFile,
  SocialMediaItem,
  SocialMediaPlatform,
  SeasonOption,
  PriceOption,
  FoodTypeOption,
  CityOption,
  RegionOption,
  LocationData,
  StoreType,
  StoreAddressType,
  StoreTypeOption,
  StoreAddressTypeOption
} from './property-item.model';
import { PropertyFileUploadComponent } from './components/property-file-upload/property-file-upload.component';
import { SocialMediaPopupComponent } from './components/social-media-popup/social-media-popup.component';
import { LocationPickerComponent } from './components/location-picker/location-picker.component';
import { SvgIconComponent } from 'src/app/shared/components/svg-icon/svg-icon.component';
import {
  urlValidator,
  dateRangeValidator,
  storeRulesValidator,
  requiredFileArray,
  isValidUrl
} from './property-form.validators';
import { PropertyOptionsFacade } from 'src/app/domains/profile-settings/facades';
import { PropertiesService } from 'src/app/domains/profile-settings/services';
import { ICreatePropertyRequestDto } from 'src/app/domains/profile-settings/dtos/requests/create-property-request.dto';
import { AlertsService } from 'src/app/services/alerts.service';
import { ContactInfoLinksComponent } from 'src/app/components/personal-profile/basic-info-details/contact-info-links/contact-info-links.component';
import { PlacesService } from 'src/app/services/places.service';
import { StoresService } from 'src/app/services/stores.service';
import { RestaurantsService } from 'src/app/restaurants-management/services/Restaurants.service';
import { PublicService } from 'src/app/modules/shared/services/public.service';

type SelectOption<T> = {
  label: string;
  value: T;
};

type PropertyItemFormValue = {
  propertyType: PropertyItemType | null;
  departments: DepartmentOption[];
  propertyName: string;
  propertyAddress: string;
  description: string;
  city: CityOption | null;
  region: RegionOption | null;
  location?: LocationData;
  ownershipFile: PropertyUploadFile | null;
  propertyImages: PropertyUploadFile[];
  socialMedia: SocialMediaItem[];
  // Conditional fields
  bestSeasons?: SeasonOption[];
  prices?: PriceOption[];
  foodTypes?: FoodTypeOption[];
  menuImage?: PropertyUploadFile | null;
  videoLink?: string;
  ticketLink?: string;
  startDate?: Date | null;
  endDate?: Date | null;
  // ✅ Ensure these are always in the type
  storeType?: StoreType | null;
  storeAddressType?: StoreAddressType | null;
  storeUrl?: string | null;
};

type PropertyItemFormControls = {
  propertyType: FormControl<PropertyItemType | null>;
  departments: FormControl<DepartmentOption[]>;
  propertyName: FormControl<string>;
  description: FormControl<string>;
  city: FormControl<CityOption | null>;
  region: FormControl<RegionOption | null>;
  location?: FormControl<LocationData | null>;
  ownershipFile: FormControl<PropertyUploadFile | null>;
  propertyImages: FormControl<PropertyUploadFile[]>;
  socialMedia: FormArray<FormControl<SocialMediaItem>>;
  // Conditional fields
  propertyAddress?: FormControl<string>;
  bestSeasons?: FormControl<SeasonOption[]>;
  prices?: FormControl<PriceOption[]>;
  foodTypes?: FormControl<FoodTypeOption[]>;
  menuImage?: FormControl<PropertyUploadFile | null>;
  videoLink?: FormControl<string>;
  ticketLink?: FormControl<string>;
  startDate?: FormControl<Date | null>;
  endDate?: FormControl<Date | null>;

  storeType?: FormControl<StoreType | null>;
  storeAddressType?: FormControl<StoreAddressType | null>;
  storeUrl?: FormControl<string | null>;
};

@Component({
  selector: 'app-create-update-property-item',
  standalone: true,
  imports: [
    CommonModule,
    ReactiveFormsModule,
    TranslateModule,
    MultiSelectModule,
    DropdownModule,
    CalendarModule,
    PropertyFileUploadComponent,
    SvgIconComponent,
    ContactInfoLinksComponent
  ],
  templateUrl: './create-update-property-item.component.html',
  styleUrls: ['./create-update-property-item.component.scss'],
  changeDetection: ChangeDetectionStrategy.OnPush
})
export class CreateUpdatePropertyItemComponent implements OnInit {
  private readonly dialogService = inject(DialogService);
  private readonly propertiesService = inject(PropertiesService);
  private readonly alertsService = inject(AlertsService);
  private readonly publicService = inject(PublicService);
  private readonly placesService = inject(PlacesService);
  private readonly storesService = inject(StoresService);
  private readonly restaurantsService = inject(RestaurantsService);
  private readonly cdr = inject(ChangeDetectorRef);
  private readonly translateService = inject(TranslateService);
  readonly propertyOptionsFacade = new PropertyOptionsFacade();

  @Output() isSuccess = new EventEmitter<boolean>();
  @Output() cancel = new EventEmitter<void>();


  // Start Store Types Variables
  readonly storeTypeOptions = signal<StoreTypeOption[]>([
    { id: StoreType.Online, name: 'properties.storeTypes.online' },
    { id: StoreType.Offline, name: 'properties.storeTypes.offline' }
  ]);

  readonly storeAddressTypeOptions = signal<StoreAddressTypeOption[]>([
    { id: StoreAddressType.Map, name: 'properties.addressTypes.map' },
    { id: StoreAddressType.Link, name: 'properties.addressTypes.link' }
  ]);

  readonly selectedStoreType = signal<StoreType | null>(null);

  readonly isOnlineStore = computed(
    () => this.selectedStoreType() === StoreType.Online
  );

  readonly isOfflineStore = computed(
    () => this.selectedStoreType() === StoreType.Offline
  );
  // End Store Types Variables

  readonly socialMediaPlatforms = signal<SocialMediaPlatform[]>([
    { id: 'whatsapp', name: 'properties.platforms.whatsapp' },
    { id: 'facebook', name: 'properties.platforms.facebook' },
    { id: 'instagram', name: 'properties.platforms.instagram' },
    { id: 'website', name: 'properties.platforms.website' },
    { id: 'twitter', name: 'properties.platforms.twitter' },
    { id: 'linkedin', name: 'properties.platforms.linkedin' },
    { id: 'youtube', name: 'properties.platforms.youtube' },
    { id: 'tiktok', name: 'properties.platforms.tiktok' },
    { id: 'snapchat', name: 'properties.platforms.snapchat' }
  ]);

  readonly propertyTypes = signal<SelectOption<PropertyItemType>[]>([
    { label: 'properties.types.place', value: PropertyItemType.Place },
    { label: 'properties.types.store', value: PropertyItemType.Store },
    { label: 'properties.types.zad', value: PropertyItemType.Zad },
    { label: 'properties.types.event', value: PropertyItemType.Event }
  ]);

  // Dynamic categories based on property type
  readonly departmentOptions = signal<DepartmentOption[]>([]);
  readonly isLoadingDepartments = signal<boolean>(false);

  readonly seasonOptionsBase = signal<SeasonOption[]>([
    { id: SeasonType.AllYear, name: 'properties.seasons.allYear' },
    { id: SeasonType.Spring, name: 'properties.seasons.spring' },
    { id: SeasonType.Summer, name: 'properties.seasons.summer' },
    { id: SeasonType.Fall, name: 'properties.seasons.fall' },
    { id: SeasonType.Winter, name: 'properties.seasons.winter' }
  ]);

  // Computed signal to translate season names
  readonly seasonOptions = computed(() => {
    return this.seasonOptionsBase().map(option => ({
      ...option,
      name: this.translateService.instant(option.name)
    }));
  });

  // Use facade signals for API data
  readonly priceOptions = this.propertyOptionsFacade.prices;
  readonly foodTypeOptions = this.propertyOptionsFacade.foodCategories;

  // Dynamic regions and cities from placesService
  readonly regionOptions = signal<RegionOption[]>([]);
  readonly cityOptions = signal<CityOption[]>([]);
  readonly isLoadingRegions = signal<boolean>(false);
  readonly isLoadingCities = signal<boolean>(false);

  readonly today = new Date();

  readonly propertyForm = new FormGroup<PropertyItemFormControls>({
    propertyType: new FormControl<PropertyItemType | null>(null, {
      validators: [Validators.required]
    }),
    departments: new FormControl<DepartmentOption[]>({ value: [], disabled: true }, {
      nonNullable: true,
      validators: [Validators.required]
    }),
    propertyName: new FormControl<string>('', {
      nonNullable: true,
      validators: [Validators.required]
    }),
    description: new FormControl<string>('', {
      nonNullable: true,
      validators: [Validators.required, Validators.minLength(20)]
    }),
    city: new FormControl<CityOption | null>({ value: null, disabled: true }, {
      validators: [Validators.required]
    }),
    region: new FormControl<RegionOption | null>(null, {
      validators: [Validators.required]
    }),
    ownershipFile: new FormControl<PropertyUploadFile | null>(null, {
      validators: [Validators.required]
    }),
    propertyImages: new FormControl<PropertyUploadFile[]>([], {
      nonNullable: true,
      validators: [requiredFileArray()]
    }),
    socialMedia: new FormArray<FormControl<SocialMediaItem>>([])
  },
    {
      validators: [
        dateRangeValidator(),
        storeRulesValidator()
      ]
    });

  readonly selectedType = toSignal(
    this.controls?.propertyType.valueChanges.pipe(
      startWith(this.controls?.propertyType.value)
    ),
    { initialValue: null }
  );
  readonly isPlace = computed(() => this.selectedType() === PropertyItemType.Place);
  readonly isZad = computed(() => this.selectedType() === PropertyItemType.Zad);
  readonly isEvent = computed(() => this.selectedType() === PropertyItemType.Event);
  readonly isStore = computed(() => this.selectedType() === PropertyItemType.Store);
  readonly isPropertyTypeSelected = computed(() => this.selectedType() !== null);
  readonly shouldShowDepartments = computed(() => this.selectedType() !== null && this.selectedType() !== PropertyItemType.Event);
  readonly isRegionSelected = computed(() => {
    const region = this.selectedRegion();
    const hasRegion = region !== null;
    console.log('isRegionSelected computed:', hasRegion, region);
    return hasRegion;
  });
  readonly cityPlaceholder = computed(() => {
    const isRegion = this.isRegionSelected();
    const isLoading = this.isLoadingCities();
    console.log('cityPlaceholder computed - isRegion:', isRegion, 'isLoading:', isLoading);

    if (!isRegion) {
      return 'properties.fields.selectRegionFirst';
    }
    if (isLoading) {
      return 'properties.fields.pleaseWait';
    }
    return 'properties.fields.selectCity';
  });

  private previousType: PropertyItemType | null = null;
  private previousRegionId: number | null = null;

  readonly selectedRegion = toSignal(
    this.controls?.region.valueChanges.pipe(
      startWith(this.controls?.region.value)
    ),
    { initialValue: null }
  );

  constructor() {
    effect(() => {
      const type = this.selectedType();
      console.log('Property type changed:', type);
      if (type) {
        // If type changed, reset form and errors
        if (this.previousType !== null && this.previousType !== type) {
          this.resetFormOnTypeChange();
        }
        this.previousType = type;
        this.updateConditionalFields(type);

        // Load categories based on property type
        if (type !== PropertyItemType.Event) {
          console.log('Loading categories for type:', type);
          // Keep disabled during loading, will be enabled after loading completes
          this.loadCategoriesByType(type);
        } else {
          // Disable departments for events (but keep it visible)
          this.controls?.departments.disable();
          this.controls?.departments.setValue([]);
          this.departmentOptions.set([]);
        }
      } else {
        // Disable departments when no property type is selected
        this.controls?.departments.disable();
        this.controls?.departments.setValue([]);
        this.departmentOptions.set([]);
      }
    }, { allowSignalWrites: true });

    // Effect to load cities when region changes
    effect(() => {
      const region = this.selectedRegion();
      if (region?.id) {
        // Only load if region changed
        if (this.previousRegionId !== region.id) {
          console.log('Region selected:', region);
          this.previousRegionId = region.id;
          this.loadCities(region.id);
        }
      } else {
        // Disable city when no region is selected
        this.previousRegionId = null;
        this.controls?.city.disable();
        this.controls?.city.setValue(null);
        this.cityOptions.set([]);
      }
    }, { allowSignalWrites: true });

    // Update the storeAddressType effect
    effect(() => {
      const addressType = this.controls?.storeAddressType?.value;
      if (addressType) {
        this.storeAddressType.set(addressType);
        this.handleStoreAddressTypeChange(addressType);
      }
    }, { allowSignalWrites: true });

  }

  ngAfterViewInit(): void {
    // Mark controls as ready after view initialization
    setTimeout(() => {
      this.controlsReady.set(true);
    });
  }

  private resetFormOnTypeChange(): void {
    // Reset all conditional fields (this will remove menuImage control if it exists)
    this.removeConditionalFields();

    // Reset social media array
    while (this.socialMediaArray.length > 0) {
      this.socialMediaArray.removeAt(0);
    }

    // Reset file uploads
    this.controls?.ownershipFile.setValue(null);
    this.controls?.propertyImages.setValue([]);

    // Reset menuImage if control exists
    if (this.controls?.menuImage) {
      this.controls?.menuImage.setValue(null);
    }

    // Reset form errors
    Object.keys(this.propertyForm.controls).forEach(key => {
      const control = this.propertyForm.get(key);
      if (control) {
        control.setErrors(null);
        control.markAsUntouched();
        control.markAsPristine();
      }
    });

    // Reset form-level errors
    this.propertyForm.setErrors(null);
    this.propertyForm.markAsUntouched();
    this.propertyForm.markAsPristine();
  }

  ngOnInit(): void {
    // Load all options on component init
    this.propertyOptionsFacade.loadPrices();
    this.propertyOptionsFacade.loadFoodCategories();
    // Load regions from placesService
    this.loadRegions();


    // Add storeUrl control initially (disabled)
    this.propertyForm.addControl(
      'storeUrl',
      new FormControl<string | null>({ value: null, disabled: true }, {
        validators: [Validators.required, urlValidator()]
      })
    );
  }

  private loadRegions(): void {
    this.isLoadingRegions.set(true);
    this.placesService.getRegions().subscribe({
      next: (response: any) => {
        let regions: RegionOption[] = [];

        // Handle different response structures
        if (response?.data) {
          if (Array.isArray(response.data)) {
            regions = response.data.map((item: any) => ({
              id: item.id || item.region_id,
              name: item.name || item.title || item.region_name
            }));
          } else if (response.data?.items) {
            regions = response.data.items.map((item: any) => ({
              id: item.id || item.region_id,
              name: item.name || item.title || item.region_name
            }));
          }
        } else if (Array.isArray(response)) {
          regions = response.map((item: any) => ({
            id: item.id || item.region_id,
            name: item.name || item.title || item.region_name
          }));
        }

        this.regionOptions.set(regions);
        this.isLoadingRegions.set(false);
      },
      error: (error: any) => {
        console.error('Error loading regions:', error);
        this.alertsService.openToast('error', 'Error loading regions');
        this.regionOptions.set([]);
        this.isLoadingRegions.set(false);
      }
    });
  }

  private loadCities(regionId: number): void {
    this.isLoadingCities.set(true);
    this.controls?.city.setValue(null);
    this.cityOptions.set([]);

    this.placesService.getCities(regionId).subscribe({
      next: (response: any) => {
        let cities: CityOption[] = [];

        // Handle different response structures
        if (response?.data) {
          if (Array.isArray(response.data)) {
            cities = response.data.map((item: any) => ({
              id: item.id || item.city_id,
              name: item.name || item.title || item.city_name
            }));
          } else if (response.data?.items) {
            cities = response.data.items.map((item: any) => ({
              id: item.id || item.city_id,
              name: item.name || item.title || item.city_name
            }));
          }
        } else if (Array.isArray(response)) {
          cities = response.map((item: any) => ({
            id: item.id || item.city_id,
            name: item.name || item.title || item.city_name
          }));
        }

        this.cityOptions.set(cities);
        this.isLoadingCities.set(false);
        // Enable city after loading completes
        this.controls?.city.enable();
      },
      error: (error: any) => {
        console.error('Error loading cities:', error);
        this.alertsService.openToast('error', 'Error loading cities');
        this.cityOptions.set([]);
        this.isLoadingCities.set(false);
        // Keep disabled on error
      }
    });
  }

  private loadCategoriesByType(type: PropertyItemType): void {
    console.log('loadCategoriesByType called with type:', type);
    this.isLoadingDepartments.set(true);
    this.controls?.departments.setValue([]);
    this.departmentOptions.set([]);

    let categoriesObservable: any;

    switch (type) {
      case PropertyItemType.Place:
        console.log('Calling placesService.getCategories()');
        categoriesObservable = this.placesService.getCategories();
        break;
      case PropertyItemType.Store:
        console.log('Calling storesService.getStoresCategories()');
        categoriesObservable = this.storesService.getStoresCategories();
        break;
      case PropertyItemType.Zad:
        console.log('Calling restaurantsService.getCategories()');
        categoriesObservable = this.restaurantsService.getCategories();
        break;
      default:
        console.log('Unknown type, returning');
        this.isLoadingDepartments.set(false);
        return;
    }

    if (!categoriesObservable) {
      console.error('No observable returned for type:', type);
      this.isLoadingDepartments.set(false);
      return;
    }

    console.log('Subscribing to categories observable');
    categoriesObservable.subscribe({
      next: (response: any) => {
        console.log('Categories response received:', response);
        let categories: DepartmentOption[] = [];

        // Handle different response structures
        if (response?.data) {
          // If response has data array
          if (Array.isArray(response.data)) {
            categories = response.data.map((item: any) => ({
              id: item.id || item.category_id,
              name: item.name || item.title || item.category_name
            }));
          } else if (response.data?.items) {
            // If response has items array
            categories = response.data.items.map((item: any) => ({
              id: item.id || item.category_id,
              name: item.name || item.title || item.category_name
            }));
          }
        } else if (Array.isArray(response)) {
          // If response is directly an array
          categories = response.map((item: any) => ({
            id: item.id || item.category_id,
            name: item.name || item.title || item.category_name
          }));
        }

        console.log('Parsed categories:', categories);
        this.departmentOptions.set(categories);
        this.isLoadingDepartments.set(false);
        // Enable departments after loading completes
        this.controls?.departments.enable();
      },
      error: (error: any) => {
        console.error('Error loading categories:', error);
        this.alertsService.openToast('error', 'Error loading categories');
        this.departmentOptions.set([]);
        this.isLoadingDepartments.set(false);
        // Keep disabled on error
      }
    });
  }

  private getFormValue(): PropertyItemFormValue {
    const rawValue = this.propertyForm.getRawValue();
    return {
      propertyType: rawValue.propertyType,
      departments: rawValue.departments,
      propertyName: rawValue.propertyName,
      propertyAddress: rawValue.propertyAddress,
      description: rawValue.description,
      city: rawValue.city,
      region: rawValue.region,
      location: rawValue.location || undefined,
      ownershipFile: rawValue.ownershipFile,
      propertyImages: rawValue.propertyImages,
      socialMedia: this.socialMediaArray.controls.map((ctrl) => ctrl.value),
      // Conditional fields
      bestSeasons: rawValue.bestSeasons,
      prices: rawValue.prices,
      foodTypes: rawValue.foodTypes,
      menuImage: rawValue.menuImage,
      videoLink: rawValue.videoLink,
      ticketLink: rawValue.ticketLink,
      startDate: rawValue.startDate,
      endDate: rawValue.endDate,
      // ✅ ADD store-related fields
      storeType: rawValue.storeType,
      storeAddressType: rawValue.storeAddressType,
      storeUrl: rawValue.storeUrl
    };
  }

  readonly isSubmitting = signal(false);
  readonly isSubmitted = signal(false);

  get controls(): PropertyItemFormControls {
    return this.propertyForm?.controls;
  }

  onCancel(): void {
    if (this.isSubmitting()) return;
    this.resetFullForm();
    this.cancel.emit();
  }

  public clearControl(controlName: keyof PropertyItemFormControls, event: Event): void {
    event?.stopPropagation();
    if (this.isSubmitting()) return;
    const control = this.propertyForm.get(controlName as string);
    if (!control) return;
    const currentValue = control.value as unknown;
    if (Array.isArray(currentValue)) {
      control.setValue([]);
    } else {
      control.setValue(null);
    }
    control.markAsDirty();
    control.markAsTouched();
  }

  onSubmit(): void {
    // First, manually trigger store validation
    if (this.selectedType() === PropertyItemType.Store) {
      this.validateStoreControls();
    }
    // Mark all controls touched so validation errors are visible
    this.propertyForm.markAllAsTouched();
    this.propertyForm.updateValueAndValidity(); // 👈 IMPORTANT

    // Debug form value
    console.log('=== FORM RAW VALUE ===');
    console.log(this.propertyForm.getRawValue());

    // Debug getFormValue
    console.log('=== getFormValue() RESULT ===');
    console.log(this.getFormValue());

    if (this.propertyForm.invalid) {
      console.log('🚫 FORM INVALID');
      this.logInvalidControls();

      return;
    }

    // Set submitting state
    this.isSubmitting.set(true);

    const request = this.buildPropertyRequest();
    console.log('At Submit | Payload: ', request);

    this.propertiesService.createProperty(request).subscribe({
      next: (response) => {
        this.isSubmitting.set(false);
        if (response.code === 200) {
          this.alertsService.openToast('success', response.message || 'Property created successfully');

          // Reset form fully
          this.resetFullForm();

          // Emit success event
          this.isSuccess.emit(true);
        } else {
          this.handleApiError(response);
        }
      },
      error: (error) => {
        this.isSubmitting.set(false);
        this.handleApiError(error);
        console.error('Property creation error:', error);
      }
    });
  }

  private handleApiError(error: any): void {
    console.error('API Error:', error);

    // 1️⃣ Determine a meaningful message from backend safely
    let apiMessage: string | null = null;

    if (typeof error === 'string' && error.trim() !== '') {
      apiMessage = error;
    } else if (error?.message && typeof error.message === 'string') {
      apiMessage = error.message;
    } else if (error?.error?.message && typeof error.error.message === 'string') {
      apiMessage = error.error.message;
    }

    if (apiMessage) {
      this.alertsService.openToast('error', apiMessage);
      return;
    }

    // 2️⃣ Fallback by status code
    const statusCode = error?.status || error?.code;
    let translationKey = 'errors.generic';

    switch (statusCode) {
      case 422:
        translationKey = 'errors.validation';
        break;
      case 400:
        translationKey = 'errors.badRequest';
        break;
      case 401:
        translationKey = 'errors.unauthorized';
        break;
      case 403:
        translationKey = 'errors.forbidden';
        break;
      case 404:
        translationKey = 'errors.notFound';
        break;
      case 500:
        translationKey = 'errors.server';
        break;
    }

    const translatedMessage = this.publicService?.translateTextFromJson(translationKey) || 'Something went wrong';
    this.alertsService.openToast('error', translatedMessage);
  }

  private logInvalidControls(): void {
    Object.entries(this.propertyForm.controls).forEach(([key, control]) => {
      if (control.invalid) {
        console.log(`❌ Invalid control: ${key}`, {
          value: control.value,
          errors: control.errors,
          status: control.status
        });
      }
    });

    console.log('Form errors:', this.propertyForm.errors);
    console.log('Form status:', this.propertyForm.status);
  }

  private buildPropertyRequest(): ICreatePropertyRequestDto {
    const formValue = this.getFormValue();

    console.log('=== FORM VALUE ===');
    console.log(formValue);

    // --- Social Media Links ---
    const socialLinks = formValue.socialMedia.reduce<Record<string, string>>((acc, item) => {
      const id = item.platform.id === 'personal_account' ? 'website' : item.platform.id;
      if (item.link) acc[id] = item.link;
      return acc;
    }, {});

    console.log('=== SOCIAL MEDIA LINKS ===');
    console.log(socialLinks);

    // --- Base Request ---
    const request: ICreatePropertyRequestDto = {
      type: formValue.propertyType || '',
      title: formValue.propertyName,
      description: formValue.description,
      lat: formValue.location?.lat?.toString() || '',
      long: formValue.location?.lng?.toString() || '',
      address: formValue.propertyAddress || '',
      region_id: formValue.region?.id?.toString() || '',
      city_id: formValue.city?.id?.toString() || '',
      categories: formValue.departments.map(d => d.id.toString()),
      whatsapp: socialLinks['whatsapp'],
      facebook_link: socialLinks['facebook'],
      instagram_link: socialLinks['instagram'],
      website_link: socialLinks['website'],
      ownership_proof_file: formValue.ownershipFile?.file,
      image: formValue.propertyImages.length ? formValue.propertyImages[0].file : undefined,
    };

    console.log('=== BASE REQUEST ===');
    console.log({
      ...request,
      ownership_proof_file: formValue.ownershipFile,
      propertyImages: formValue.propertyImages
    });

    // --- Conditional Fields by Property Type ---
    switch (formValue.propertyType) {
      case PropertyItemType.Place:
        request.best_seasons = formValue.bestSeasons?.map(s => s.id) || [];
        request.prices = formValue.prices?.map(p => p.id.toString()) || [];
        console.log('=== PLACE FIELDS ===', {
          best_seasons: request.best_seasons,
          prices: request.prices
        });
        break;

      case PropertyItemType.Zad:
        request.food_categories = formValue.foodTypes?.map(f => f.id.toString()) || [];
        request.menu_file = formValue.menuImage?.file;
        console.log('=== ZAD FIELDS ===', {
          food_categories: request.food_categories,
          menu_file: formValue.menuImage
        });
        break;

      case PropertyItemType.Event:
        request.video_link = formValue.videoLink || '';
        request.ticket_link = formValue.ticketLink || '';
        if (formValue.startDate) request.date_from = this.formatDate(formValue.startDate);
        if (formValue.endDate) request.date_to = this.formatDate(formValue.endDate);
        console.log('=== EVENT FIELDS ===', {
          video_link: request.video_link,
          ticket_link: request.ticket_link,
          date_from: request.date_from,
          date_to: request.date_to
        });
        break;

      case PropertyItemType.Store:
        // --- Store Basic Info ---
        request.con_type = formValue.storeType?.toString() || '';
        console.log('=== STORE TYPE (Control) ===', this.controls.storeType?.value);
        console.log('=== STORE TYPE (Request) ===', request.con_type);

        console.log('=== STORE ADDRESS TYPE (Control) ===', this.controls.storeAddressType?.value);
        console.log('=== STORE URL (Control) ===', this.controls.storeUrl?.value);

        // Map StoreAddressType to API address_type
        if (formValue.storeAddressType) {
          request.address_type = formValue.storeAddressType.toString();
          console.log('=== ADDRESS TYPE ===', request.address_type);
        }

        if (formValue.storeType === StoreType.Online) {
          request.address = formValue.storeUrl || '';
          console.log('=== ONLINE STORE ===', {
            storeUrlControl: this.controls.storeUrl?.value,
            request_address: request.address
          });
        } else if (formValue.storeType === StoreType.Offline) {
          request.region_id = formValue.region?.id?.toString() || '';
          request.city_id = formValue.city?.id?.toString() || '';
          console.log('=== OFFLINE STORE REGION/CITY ===', {
            regionControl: this.controls.region?.value,
            cityControl: this.controls.city?.value,
            region_id: request.region_id,
            city_id: request.city_id
          });

          if (formValue.storeAddressType === StoreAddressType.Map) {
            request.lat = formValue.location?.lat?.toString() || '';
            request.long = formValue.location?.lng?.toString() || '';
            request.address = formValue.location?.address || '';
            console.log('=== OFFLINE STORE MAP ADDRESS ===', {
              storeAddressTypeControl: this.controls.storeAddressType?.value,
              locationControl: this.controls.location?.value,
              lat: request.lat,
              long: request.long,
              address: request.address
            });
          } else if (formValue.storeAddressType === StoreAddressType.Link) {
            request.address = formValue.storeUrl || '';
            console.log('=== OFFLINE STORE LINK ADDRESS ===', {
              storeAddressTypeControl: this.controls.storeAddressType?.value,
              storeUrlControl: this.controls.storeUrl?.value,
              request_address: request.address
            });
          }
        }
        break;
    }

    console.log('=== FINAL REQUEST OBJECT ===');
    console.log({
      ...request,
      ownership_proof_file: formValue.ownershipFile,
      menu_file: formValue.menuImage,
      propertyImages: formValue.propertyImages
    });

    return request;
  }

  private resetFullForm(): void {
    // Remove all dynamic fields based on type
    this.removeConditionalFields();

    // Reset social media array
    while (this.socialMediaArray.length > 0) {
      this.socialMediaArray.removeAt(0);
    }

    // Reset basic fields
    this.propertyForm.reset({
      propertyType: null,
      departments: [],
      propertyName: '',
      propertyAddress: undefined,
      description: '',
      city: null,
      region: null,
      ownershipFile: null,
      propertyImages: []
    });

    // Clear file uploads
    this.controls?.ownershipFile.setValue(null);
    this.controls?.propertyImages.setValue([]);

    // Reset validation status
    this.propertyForm.markAsPristine();
    this.propertyForm.markAsUntouched();
    this.propertyForm.updateValueAndValidity();
  }

  private formatDate(date: Date): string {
    const year = date.getFullYear();
    const month = String(date.getMonth() + 1).padStart(2, '0');
    const day = String(date.getDate()).padStart(2, '0');
    return `${year}-${month}-${day}`;
  }

  protected onOwnershipFileChange(files: PropertyUploadFile[]): void {
    this.controls?.ownershipFile.setValue(files[0] ?? null);
    this.controls.ownershipFile.markAsTouched();
    this.controls.ownershipFile.updateValueAndValidity();
  }

  protected onMenuImageChange(files: PropertyUploadFile[]): void {
    this.controls?.menuImage?.setValue(files[0] ?? null);
    this.controls.menuImage.markAsTouched();
    this.controls.menuImage.updateValueAndValidity();
  }

  protected onPropertyImagesChange(files: PropertyUploadFile[]): void {
    const control = this.controls.propertyImages;

    control.setValue(files, { emitEvent: false });

    // 🔥 force revalidation immediately
    control.updateValueAndValidity({ onlySelf: true, emitEvent: false });

    // 🔥 UX: show error only after interaction
    if (files.length === 0) {
      control.markAsTouched();
    } else {
      control.markAsPristine();
    }
  }


  getDepartmentsPlaceholder(): string {
    if (!this.isPropertyTypeSelected()) {
      return 'properties.fields.selectPropertyTypeFirst';
    }
    if (this.isLoadingDepartments()) {
      return 'properties.fields.pleaseWait';
    }
    return 'properties.fields.selectDepartments';
  }

  protected shouldShowError(controlName: string): boolean {
    // For dynamic controls, check if they exist
    if (controlName === 'storeUrl' || controlName === 'storeAddressType' || controlName === 'location') {
      const control = this.propertyForm.get(controlName);
      if (!control) return false;

      return control.invalid && (control.touched || control.dirty || this.isSubmitted());
    }

    // For static controls
    const control = this.controls[controlName as keyof PropertyItemFormControls];
    if (!control) return false;

    return control.invalid && (control.touched || control.dirty || this.isSubmitted());
  }


  get socialMediaArray(): FormArray<FormControl<SocialMediaItem>> {
    return this.controls?.socialMedia;
  }

  openSocialMediaPopup(item?: SocialMediaItem): void {
    const ref = this.dialogService.open(SocialMediaPopupComponent, {
      width: '35%',
      header: item ? 'Edit Social Media' : 'Add Social Media',
      styleClass: 'social-media-dialog',
      data: {
        platforms: this.socialMediaPlatforms(),
        item
      }
    });

    ref.onClose.subscribe((result: SocialMediaItem | null) => {
      if (result) {
        if (item) {
          this.updateSocialMediaItem(result);
        } else {
          this.addSocialMediaItem(result);
        }
      }
    });
  }

  private addSocialMediaItem(item: SocialMediaItem): void {
    const control = new FormControl<SocialMediaItem>(item, {
      nonNullable: true
    });
    this.socialMediaArray.push(control);
  }

  private updateSocialMediaItem(item: SocialMediaItem): void {
    const index = this.socialMediaArray.controls.findIndex(
      (ctrl) => ctrl.value.id === item.id
    );
    if (index !== -1) {
      this.socialMediaArray.at(index).setValue(item);
    }
  }

  onDeleteSocialMedia(index: number): void {
    this.socialMediaArray.removeAt(index);
  }

  onEditSocialMedia(index: number): void {
    const item = this.socialMediaArray.at(index).value;
    this.openSocialMediaPopup(item);
  }

  trackBySocialMediaId(_: number, item: SocialMediaItem): string {
    return item.id;
  }

  // Convert socialMediaArray to format expected by social-links component
  // social-links component expects items[0] to be a user object with social properties
  socialMediaForComponent(): any[] {
    const socialMediaItems = this.socialMediaArray.controls.map((ctrl) => ctrl.value);

    // Create a user-like object with social properties
    // social-links component will extract these in ngOnInit
    const userObject: any = {};
    socialMediaItems.forEach((item) => {
      // Map platform IDs to user property names
      // social-links expects properties like: facebook, twitter, instagram, etc.
      const platformId = item.platform.id;
      if (platformId === 'personal_account' || platformId === 'website') {
        userObject['personal_account'] = item.link;
      } else {
        userObject[platformId] = item.link;
      }
    });

    // Return array with single user object as social-links expects items[0]
    return [userObject];
  }

  // Handle social links changes from social-links component
  getSocialLinks(socialLinks: any[]): void {
    // Clear existing social media array
    while (this.socialMediaArray.length > 0) {
      this.socialMediaArray.removeAt(0);
    }

    // Convert social-links format to SocialMediaItem format
    // social-links provides: [{ name: { id: 'facebook', title: 'facebook' }, link: 'https://...' }]
    // We need: [{ id: '...', platform: { id: 'facebook', name: '...' }, link: 'https://...' }]
    socialLinks
      .filter((item) => item?.link) // Only include items with links
      .forEach((item) => {
        let platformId = item.name?.id || item.name?.title;
        if (platformId && item.link) {
          // Map personal_account to website (since we use website in our platform list)
          if (platformId === 'personal_account') {
            platformId = 'website';
          }

          // Find the platform name from our platforms list
          let platform = this.socialMediaPlatforms().find((p) => p.id === platformId);

          // If platform not found in our list, create a default one
          if (!platform) {
            platform = {
              id: platformId,
              name: `properties.platforms.${platformId}`
            };
          }

          const socialMediaItem: SocialMediaItem = {
            id: `${platformId}-${Date.now()}-${Math.random()}`,
            platform: {
              id: platformId,
              name: platform.name
            },
            link: item.link
          };
          const control = new FormControl<SocialMediaItem>(socialMediaItem, {
            nonNullable: true
          });
          this.socialMediaArray.push(control);
        }
      });
  }

  protected openLocationPicker(): void {
    const ref = this.dialogService.open(LocationPickerComponent, {
      width: '100vw',
      height: '100vh',
      closable: true,
      styleClass: 'location-picker-dialog',
      data: {
        location: this.controls?.location?.value || null
      }
    });

    ref.onClose.subscribe((result: LocationData | null) => {
      if (!result) return;

      // تأكد إن location control موجود
      if (!this.controls.location) {
        this.propertyForm.addControl(
          'location',
          new FormControl<LocationData | null>(null)
        );
      }

      this.controls.location.setValue(result);

      const address = result.address || result.name || '';

      if (address && this.controls.propertyAddress) {
        this.controls.propertyAddress.setValue(address);
        this.controls.propertyAddress.markAsDirty();
        this.controls.propertyAddress.markAsTouched();
      }

      // OnPush
      this.cdr.markForCheck();
    });
  }


  private updateConditionalFields(type: PropertyItemType): void {
    // Remove all conditional fields first
    this.removeConditionalFields();

    // Add fields based on type
    switch (type) {
      case PropertyItemType.Place:
        this.addPlaceFields();
        break;
      case PropertyItemType.Zad:
        this.addZadFields();
        break;
      case PropertyItemType.Event:
        this.addEventFields();
        break;
      case PropertyItemType.Store:
        this.addStoreFields();
        break;
    }
  }

  // Start Store Types Functions
  // Add this property
  readonly storeAddressType = signal<StoreAddressType | null>(null);

  // Add this to track if controls are ready
  readonly controlsReady = signal<boolean>(false);

  private addStoreFields(): void {
    // 1️⃣ Add storeType control
    const storeTypeControl = new FormControl<StoreType | null>(null, {
      validators: [Validators.required]
    });
    this.propertyForm.addControl('storeType', storeTypeControl);

    // 2️⃣ Always have propertyAddress control (required for Offline store)
    if (!this.controls.propertyAddress) {
      this.propertyForm.addControl(
        'propertyAddress',
        new FormControl<string>('', { nonNullable: true })
      );
    }

    // 3️⃣ Sync signal
    this.selectedStoreType.set(storeTypeControl.value);

    // 4️⃣ Listen for changes
    storeTypeControl.valueChanges.subscribe((type) => {
      this.selectedStoreType.set(type);
      this.handleStoreTypeChange(type);
    });
  }

  // Add this signal to your component class
  readonly storeUrl = signal<string | null>(null);

  // Add this method to your CreateUpdatePropertyItemComponent class
  protected onStoreUrlInput(event: Event): void {
    const input = event.target as HTMLInputElement;
    const url = input.value;

    // Ensure the storeUrl control exists
    let storeUrlControl = this.controls.storeUrl;

    if (!storeUrlControl) {
      // Create the control dynamically
      storeUrlControl = new FormControl<string | null>(null, {
        validators: [Validators.required, urlValidator()]
      });
      this.propertyForm.addControl('storeUrl', storeUrlControl);
    }

    // Update the control value
    storeUrlControl.setValue(url);
    storeUrlControl.markAsTouched();
    storeUrlControl.markAsDirty();

    // Trigger validation
    storeUrlControl.updateValueAndValidity();
    this.propertyForm.updateValueAndValidity();

    // Update the form value for the storeUrl field
    const currentValue = this.getFormValue();
    currentValue.storeUrl = url;

    // Update the signal if you have one
    if (this.storeUrl()) {
      this.storeUrl.set(url);
    }
  }

  protected onStoreUrlBlur(event: Event): void {
    const input = event.target as HTMLInputElement;
    const url = input.value;

    // Only validate on blur if there's a value
    if (url.trim()) {
      // Ensure control exists
      let storeUrlControl = this.controls.storeUrl;

      if (!storeUrlControl) {
        storeUrlControl = new FormControl<string | null>(null, {
          validators: [Validators.required, urlValidator()]
        });
        this.propertyForm.addControl('storeUrl', storeUrlControl);
      }

      // Mark as touched (shows errors if invalid)
      storeUrlControl.markAsTouched();

      // Format URL if needed (add https:// if missing)
      if (url && !url.startsWith('http://') && !url.startsWith('https://')) {
        const formattedUrl = `https://${url}`;
        storeUrlControl.setValue(formattedUrl);
        input.value = formattedUrl;
        this.storeUrl.set(formattedUrl);
      }

      storeUrlControl.updateValueAndValidity();
    }
  }

  private resetStoreFields(): void {
    // الحقول الديناميكية المتعلقة بالـ Store
    const dynamicKeys: (keyof PropertyItemFormControls)[] = ['storeAddressType', 'storeUrl', 'location', 'propertyAddress'];

    dynamicKeys.forEach(key => {
      if (this.controls[key]) {
        this.propertyForm.removeControl(key);
      }
    });

    // إعادة ضبط الإشارة
    this.selectedStoreType.set(null);
  }

  protected handleStoreTypeChange(type: StoreType | null): void {

    if (!type) return;

    // Get or create storeUrl control
    let storeUrlControl = this.controls.storeUrl;
    if (!storeUrlControl) {
      storeUrlControl = new FormControl<string | null>({ value: null, disabled: true }, {
        validators: [Validators.required, urlValidator()]
      });
      this.propertyForm.addControl('storeUrl', storeUrlControl);
    }

    // 1️⃣ Remove any previous dynamic controls
    ['storeAddressType', 'storeUrl', 'location'].forEach(key => {
      if (this.controls[key]) this.propertyForm.removeControl(key as keyof PropertyItemFormControls);
    });

    // 2️⃣ Enable propertyAddress and reset
    const propertyAddressCtrl = this.controls.propertyAddress;
    if (propertyAddressCtrl) {
      propertyAddressCtrl.enable({ emitEvent: false });
      propertyAddressCtrl.setValue('', { emitEvent: false });
      propertyAddressCtrl.clearValidators();
      propertyAddressCtrl.updateValueAndValidity({ emitEvent: false });
    }

    // 3️⃣ Apply validators per store type
    if (type === StoreType.Online) {
      // Online store needs storeUrl only
      this.propertyForm.addControl(
        'storeUrl',
        new FormControl<string | null>(null, {
          validators: [Validators.required, urlValidator()]
        })
      );

      // Offline-only fields not required
      ['region', 'city'].forEach(key => {
        const control = this.controls[key];
        if (control) {
          control.clearValidators();
          control.updateValueAndValidity({ emitEvent: false });
        }
      });
    } else if (type === StoreType.Offline) {
      // Offline store requires region, city, and storeAddressType
      ['region', 'city'].forEach(key => {
        const control = this.controls[key];
        if (control) {
          control.setValidators([Validators.required]);
          control.updateValueAndValidity({ emitEvent: false });
          control.enable({ emitEvent: false });
        }
      });

      this.propertyForm.addControl(
        'storeAddressType',
        new FormControl<StoreAddressType | null>(null, {
          validators: [Validators.required]
        })
      );
    }

    // 🔥 CRITICAL: Force form validation update
    this.propertyForm.updateValueAndValidity({ emitEvent: false });

    // 🔥 Also update each control's validity
    Object.keys(this.propertyForm.controls).forEach(key => {
      const control = this.propertyForm.get(key);
      if (control) {
        control.updateValueAndValidity({ emitEvent: false });
      }
    });
  }

  protected handleStoreAddressTypeChange(type: StoreAddressType | null): void {
    if (!type) return;

    // Get or ensure storeUrl control exists
    let storeUrlControl = this.controls.storeUrl;
    if (!storeUrlControl) {
      storeUrlControl = new FormControl<string | null>({ value: null, disabled: true }, {
        validators: [Validators.required, urlValidator()]
      });
      this.propertyForm.addControl('storeUrl', storeUrlControl);
    }

    if (type === StoreAddressType.Link) {
      storeUrlControl.enable();
      storeUrlControl.setValidators([Validators.required, urlValidator()]);
    } else if (type === StoreAddressType.Map) {
      storeUrlControl.disable();
      storeUrlControl.clearValidators();
    }

    storeUrlControl.updateValueAndValidity();


    setTimeout(() => {
      // 1️⃣ Remove previous dynamic controls
      ['storeUrl', 'location'].forEach(key => {
        if (this.controls[key]) this.propertyForm.removeControl(key as keyof PropertyItemFormControls);
      });

      // 2️⃣ Add the correct control per address type
      if (type === StoreAddressType.Map) {
        this.propertyForm.addControl(
          'location',
          new FormControl<LocationData | null>(null, {
            validators: [Validators.required]
          })
        );
      } else if (type === StoreAddressType.Link) {
        this.propertyForm.addControl(
          'storeUrl',
          new FormControl<string | null>(null, {
            validators: [Validators.required, urlValidator()]
          })
        );

        // Force change detection
        this.cdr.detectChanges();
      }

      // 🔥 Force validation update
      this.propertyForm.updateValueAndValidity();
    });

    // Update touched state for new controls
    setTimeout(() => {
      if (type === StoreAddressType.Link && this.controls.storeUrl) {
        this.controls.storeUrl.markAsTouched();
        this.controls.storeUrl.updateValueAndValidity();
      } else if (type === StoreAddressType.Map && this.controls.location) {
        this.controls.location.markAsTouched();
        this.controls.location.updateValueAndValidity();
      }
    });
  }

  // Add a method to manually trigger store validation
  private validateStoreControls(): void {
    const propertyType = this.controls.propertyType.value;
    const storeType = this.controls.storeType?.value;

    if (propertyType !== PropertyItemType.Store) return;

    // Manually check storeUrl if it exists
    if (this.controls.storeUrl) {
      const urlValue = this.controls.storeUrl.value;
      if (urlValue) {
        // Manually validate URL
        const urlControl = this.controls.storeUrl;
        const isValid = isValidUrl(urlValue);

        if (!isValid) {
          urlControl.setErrors({ invalidUrl: true });
        } else {
          // Clear URL errors
          const errors = urlControl.errors;
          if (errors?.['invalidUrl']) {
            delete errors['invalidUrl'];
            urlControl.setErrors(Object.keys(errors).length ? errors : null);
          }
        }
      }
    }

    // Trigger form validation
    this.propertyForm.updateValueAndValidity();
  }

  // End Store Types Functions

  private removeConditionalFields(): void {
    const fieldsToRemove: (keyof PropertyItemFormControls)[] = [
      'bestSeasons',
      'prices',
      'foodTypes',
      'menuImage',
      'videoLink',
      'ticketLink',
      'startDate',
      'endDate',
      'propertyAddress', // ✅ ADD
      'storeType',
      'storeAddressType',
      'location'
    ];

    fieldsToRemove.forEach((field) => {
      if (this.controls[field]) {
        this.propertyForm.removeControl(field);
      }
    });
  }

  private addPlaceFields(): void {
    // 🔹 ADD propertyAddress HERE
    this.propertyForm.addControl(
      'propertyAddress',
      new FormControl<string>('', {
        nonNullable: true,
        validators: [Validators.required]
      })
    );

    this.propertyForm.addControl(
      'bestSeasons',
      new FormControl<SeasonOption[]>([], {
        nonNullable: true,
        validators: [Validators.required]
      })
    );
    this.propertyForm.addControl(
      'prices',
      new FormControl<PriceOption[]>([], {
        nonNullable: true,
        validators: [Validators.required]
      })
    );
  }

  private addZadFields(): void {
    // 🔹 ADD propertyAddress HERE
    this.propertyForm.addControl(
      'propertyAddress',
      new FormControl<string>('', {
        nonNullable: true,
        validators: [Validators.required]
      })
    );

    this.propertyForm.addControl(
      'foodTypes',
      new FormControl<FoodTypeOption[]>([], {
        nonNullable: true,
        validators: [Validators.required]
      })
    );
    this.propertyForm.addControl(
      'menuImage',
      new FormControl<PropertyUploadFile | null>(null, {
        validators: [Validators.required]
      })
    );
  }

  private addEventFields(): void {
    // 🔹 ADD propertyAddress HERE
    this.propertyForm.addControl(
      'propertyAddress',
      new FormControl<string>('', {
        nonNullable: true,
        validators: [Validators.required]
      })
    );

    this.propertyForm.addControl(
      'videoLink',
      new FormControl<string>('', {
        nonNullable: true,
        validators: [Validators.required, urlValidator()]
      })
    );
    this.propertyForm.addControl(
      'ticketLink',
      new FormControl<string>('', {
        nonNullable: true,
        validators: [Validators.required, urlValidator()]
      })
    );
    const startDateControl = new FormControl<Date | null>(null, {
      validators: [Validators.required]
    });
    this.propertyForm.addControl('startDate', startDateControl);

    const endDateControl = new FormControl<Date | null>(null, {
      validators: [Validators.required]
    });
    this.propertyForm.addControl('endDate', endDateControl);

    // Subscribe to startDate changes to reset endDate if it's before startDate
    // and enable/disable endDate control based on startDate
    startDateControl.valueChanges.subscribe((startDate) => {
      if (startDate) {
        endDateControl.enable({ emitEvent: false });
        const endDate = endDateControl.value;
        if (endDate && endDate < startDate) {
          endDateControl.setValue(null, { emitEvent: false });
        }
      } else {
        endDateControl.disable({ emitEvent: false });
        endDateControl.setValue(null, { emitEvent: false });
      }
    });

    // Initially disable endDate if startDate is not set
    if (!startDateControl.value) {
      endDateControl.disable({ emitEvent: false });
    }
  }

  getErrorMessage(controlName: keyof PropertyItemFormControls): string {
    const control = this.controls[controlName];
    if (!control || !control.errors) {
      return '';
    }

    if (control.errors['required']) {
      return 'properties.errors.required';
    }

    if (control.errors['invalidUrl']) {
      return 'properties.errors.invalidUrl';
    }

    if (control.errors['invalidPhoneOrUrl']) {
      return 'properties.errors.invalidPhoneOrUrl';
    }

    if (control.errors['minlength']) {
      return 'properties.errors.minLength';
    }

    if (control.errors['dateRangeInvalid']) {
      return 'properties.errors.dateRangeInvalid';
    }

    if (control.errors['startDatePast']) {
      return 'properties.errors.startDatePast';
    }

    if (this.propertyForm.errors?.['storeUrlRequired']) {
      return 'properties.errors.storeUrlRequired';
    }

    if (this.propertyForm.errors?.['regionCityRequired']) {
      return 'properties.errors.required';
    }

    if (this.propertyForm.errors?.['storeAddressTypeRequired']) {
      return 'properties.errors.storeAddressTypeRequired';
    }

    return '';
  }
}
