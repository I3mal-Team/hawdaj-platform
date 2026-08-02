/* ---------- Angular Core ---------- */
import {
  AfterViewInit,
  ChangeDetectionStrategy,
  ChangeDetectorRef,
  Component,
  Inject,
  PLATFORM_ID,
  signal,
  computed,
  effect,
  OnDestroy
} from '@angular/core';
import { CommonModule, isPlatformBrowser } from '@angular/common';
import { RouterModule, Router } from '@angular/router';
import { FormsModule } from '@angular/forms';

/* ---------- Third-party Modules ---------- */
import { TranslateModule, TranslateService } from '@ngx-translate/core';
import { ToastModule } from 'primeng/toast';
import { MultiSelectModule } from 'primeng/multiselect';
import { Subject } from 'rxjs';
import { debounceTime, takeUntil } from 'rxjs/operators';
import { DialogService } from 'primeng/dynamicdialog';


/* ---------- Services & Facade ---------- */
import { MetadataService } from 'src/app/modules/shared/services/metadata.service';
import { PublicService } from 'src/app/modules/shared/services/public.service';
import { IPropertyItem } from '../../../dtos';

/* ---------- Shared Components ---------- */;
import { SkeletonComponent } from 'src/app/modules/shared/components/skeleton/skeleton.component';
import { SvgIconComponent } from "src/app/shared/components/svg-icon";
import { TabListComponent, ITabItem, ITabListConfig } from 'src/app/shared/components/tab-list';
import { ListCardComponent } from 'src/app/Common/component/list-card/list-card.component';
import { item } from 'src/app/Common/component/list-card/interface/list-card';

/* ---------- Enums & Constants ---------- */
import { environment } from 'src/environments/environment';
import { ProfileSettingsRouteData, ProfileSettingsRoutesEnum } from '../../../constants';


// Types
export interface IMyPropertyType {
  name: string;
  value: string;
}

import { ConfirmDeleteTripComponent } from 'src/app/components/my-trips/components/confirm-delete-trip/confirm-delete-trip.component';
import { PropertiesFacade } from '../../../facades';
import { CreateUpdatePropertyItemComponent } from '../create-update-property-item';

@Component({
  selector: 'app-my-properties-listing',
  standalone: true,
  imports: [
    /* Angular */
    CommonModule,
    RouterModule,
    FormsModule,
    /* Third-party */
    TranslateModule,
    ToastModule,
    MultiSelectModule,
    /* Shared Components */
    SkeletonComponent,
    SvgIconComponent,
    TabListComponent,
    CreateUpdatePropertyItemComponent,
    ListCardComponent,
  ],
  templateUrl: './my-properties-listing.component.html',
  styleUrls: ['./my-properties-listing.component.scss'],
  changeDetection: ChangeDetectionStrategy.OnPush
})
export class MyPropertiesListingComponent implements AfterViewInit, OnDestroy {
  private readonly destroy$ = new Subject<void>();
  private readonly searchSubject = new Subject<string>();

  /** ---------- Facade ---------- */
  readonly facade = new PropertiesFacade();

  /** ---------- Signals ---------- */
  readonly homeShowFooter = signal(false);
  readonly currentLanguage = signal<string>('ar');
  readonly searchQuery = signal<string>('');
  readonly selectedMyPropertyTypes = signal<IMyPropertyType[]>([]);

  /** ---------- SEO Texts ---------- */
  readonly pageTitle = signal<string>('');
  readonly pageDescription = signal<string>('');
  readonly breadcrumb = signal<string>('');

  /** ---------- Computed Signals ---------- */
  readonly isRtlLayout = computed(() => this.currentLanguage() === 'ar');
  readonly isCreateMode = signal<boolean>(false);

  readonly shouldShowSearch = computed(() => {
    // Don't show search while loading
    if (this.facade.isLoadingPropertiesList()) {
      return false;
    }

    // If user is typing/has search value, keep search visible regardless of counts
    const currentQuery = this.searchQuery();
    if (currentQuery && currentQuery.trim().length > 0) {
      return true;
    }

    const selectedType = this.facade.selectedType();

    const placesCount = this.facade.placesList().length;
    const storesCount = this.facade.storesList().length;
    const eventsCount = this.facade.eventsList().length;
    const zadsCount = this.facade.zadList().length;

    const countsByType: Record<'place' | 'store' | 'event' | 'zad', number> = {
      place: placesCount,
      store: storesCount,
      event: eventsCount,
      zad: zadsCount
    };

    if (selectedType === 'all') {
      const totalCount = placesCount + storesCount + eventsCount + zadsCount;
      return totalCount >= 2;
    }

    return (countsByType[selectedType as 'place' | 'store' | 'event' | 'zad'] || 0) >= 2;
  });

  /** ---------- Property Type Filter Tabs ---------- */
  readonly IMyPropertyTypeTabs = signal<ITabItem[]>([
    {
      id: 'place',
      label: 'properties.types.place',
      icon: '',
      active: true,
      data: { type: 'place' }
    },
    {
      id: 'store',
      label: 'properties.types.store',
      icon: '',
      active: false,
      data: { type: 'store' }
    },
    {
      id: 'event',
      label: 'properties.types.event',
      icon: '',
      active: false,
      data: { type: 'event' }
    },
    {
      id: 'zad',
      label: 'properties.types.zad',
      icon: '',
      active: false,
      data: { type: 'zad' }
    }
  ]);

  readonly tabListConfig = signal<ITabListConfig>({
    isMultiple: false,
    showIcons: true,
    customClass: ''
  });

  constructor(
    @Inject(PLATFORM_ID) private platformId: Object,
    private metadataService: MetadataService,
    private publicService: PublicService,
    private translateService: TranslateService,
    private dialogService: DialogService,
    private router: Router,
    private cdr: ChangeDetectorRef
  ) {
    // Setup search debouncing
    this.setupSearchDebounce();
  }

  ngAfterViewInit(): void {
    if (isPlatformBrowser(this.platformId)) {
      const lang = this.publicService.getCurrentLanguage() || 'ar';
      this.currentLanguage.set(lang);

      // Set SEO texts dynamically from MyProperties
      this.pageTitle.set(ProfileSettingsRouteData.MyProperties.title[lang]);
      this.pageDescription.set(ProfileSettingsRouteData.MyProperties.meta.description[lang]);
      this.breadcrumb.set(ProfileSettingsRouteData.MyProperties.breadcrumb[lang]);
    }

    this.updateMetaTags();
    this.facade.loadProperties(1, this.facade.perPage(), false, '', 'place');
  }

  ngOnDestroy(): void {
    this.destroy$.next();
    this.destroy$.complete();
    this.searchSubject.complete();
  }

  /** ---------- SEO ---------- */
  private updateMetaTags(): void {
    const lang = this.currentLanguage();
    const routeData = ProfileSettingsRouteData.MyProperties;

    this.metadataService.updateTitle(routeData.title[lang]);
    this.metadataService.updateMetaTagsName([
      { name: 'title', content: routeData.title[lang] },
      { name: 'description', content: routeData.meta.description[lang] },
      { name: 'keyword', content: routeData.meta.keyword[lang] },
    ]);
    this.metadataService.updateMetaTagsProperty([
      { property: 'og:url', content: `${environment.publicUrl}/${lang}/${ProfileSettingsRoutesEnum.MY_PROPERTIES}` },
      { property: 'og:title', content: routeData.title[lang] },
      { property: 'og:description', content: routeData.meta.description[lang] },
    ]);
    this.metadataService.setSharePreviewImage(null);
  }

  /** ---------- Search & Filter Functionality ---------- */
  private setupSearchDebounce(): void {
    this.searchSubject.pipe(
      debounceTime(300),
      takeUntil(this.destroy$)
    ).subscribe(query => {
      // Update keyword in facade for local filtering (no API call)
      this.facade.keyword.set(query || null);
    });
  }

  protected onSearchInput(event: Event): void {
    const query = (event.target as HTMLInputElement).value;
    this.searchQuery.set(query);
    this.searchSubject.next(query);
  }

  protected clearSearch(): void {
    this.searchQuery.set('');
    this.facade.keyword.set(null);
  }


  public shouldShowHeaderCreateButton(): boolean {
    return !(this.facade.hasNoData() && !this.hasActiveSearch());
  }


  /** ---------- Lazy-loaded Section Handler ---------- */
  protected onSectionInView(): void {
    this.homeShowFooter.set(true);
  }

  /** ---------- Navigation ---------- */
  protected exploreProperty(item: IPropertyItem): void {
    if (!item?.slug || !item?.type) return;

    const routes: Record<string, string> = {
      place: '/places/details',
      store: '/stores',
      event: '/events/event-details',
      zad: '/restaurants'
    };

    const route = routes[item.type];
    if (route) {
      this.router.navigate([route, item.slug]);
    }
  }

  /** Delete Property with confirm dialog */
  protected deleteProperty(item?: IPropertyItem) {
    if (!item?.id) return;

    const ref = this.dialogService.open(ConfirmDeleteTripComponent, {
      width: '35%',
      header: this.publicService.translateTextFromJson('general.confirmDelete'),
      styleClass: 'auth-dialog confirm-delete-trip',
    });

    ref.onClose?.subscribe((res: any) => {
      if (res?.isConfirmed && item?.id) {
        // Data will be updated locally in the facade, no need to reload
        this.facade.deleteProperty(item.id);
      }
    });
  }

  /** Map Property to Card Item */
  protected mapPropertyToCardItem(property: IPropertyItem): item {
    return {
      title: property.title,
      address: property.address,
      address_name: property.city?.name || property.region?.name || '',
      image: property.image || property.cover_image || '',
      is_favorite: property.is_favorite || false,
      rate: property.rate || 0,
      ratings: [],
      slug: property.slug,
      date_from: property.date_from || undefined,
      date_to: property.date_to || undefined,
      website_link: property.website_link || undefined,
      foodCategory: property.food_categories?.map(cat => cat.name).join(', ') || undefined
    };
  }

  /** ---------- Pagination ---------- */
  protected onPageChange(page: number): void {
    this.facade.loadProperties(page, this.facade.perPage(), false, this.searchQuery(), this.facade.selectedType());
  }

  /** ---------- Property Type Filter ---------- */
  protected onMyPropertyTypeTabChange(tab: ITabItem | ITabItem[]): void {
    // Single selection mode
    if (!Array.isArray(tab)) {
      const selectedType = tab.data?.type as 'place' | 'store' | 'event' | 'zad';

      // Reset search when changing category
      this.searchQuery.set('');
      this.facade.keyword.set(null);

      // Just update the selected type - no API call needed
      // The computed signals will automatically filter the displayed data
      this.facade.selectedType.set(selectedType);

      // Update selected property types signal
      this.selectedMyPropertyTypes.set([{
        name: this.translateService.instant(`properties.types.${selectedType}`),
        value: selectedType
      }]);
    }
  }

  /** ---------- Create Property Action ---------- */
  protected createProperty(): void {
    this.isCreateMode.set(true);
  }

  public cancelCreateMode(): void {
    this.isCreateMode.set(false);
    if (isPlatformBrowser(this.platformId)) {
      window.scrollTo({ top: 0, behavior: 'smooth' });
    }
  }

  protected isSuccessAdding(event: Event | boolean): void {
    if (event) {
      // Exit create mode first to show the list view
      this.isCreateMode.set(false);
      
      // Force change detection immediately
      this.cdr.markForCheck();

      // Reset tab selection
      const tabs = this.IMyPropertyTypeTabs();
      tabs.forEach((t, idx) => t.active = idx === 0);
      this.IMyPropertyTypeTabs.set([...tabs]);

      // Update selected type in facade
      const selectedType = tabs[0].data?.type as 'place' | 'store' | 'event' | 'zad';
      this.facade.selectedType.set(selectedType);

      // Reset search and filters (optional, only if needed)
      this.searchQuery.set('');
      this.selectedMyPropertyTypes.set([]);

      // Reload the property list, starting from page 1
      this.facade.loadProperties(
        1,                     // page
        this.facade.perPage(), // perPage
        false,                 // append (false to reset)
        '',                    // search query (empty string)
        selectedType           // selected type
      );

      // Force change detection after data is loaded
      // Use a longer timeout to ensure API call completes
      setTimeout(() => {
        this.cdr.markForCheck();
        this.cdr.detectChanges();
      }, 300);

      // Reset any other variables/signals if needed
      this.homeShowFooter.set(false);
    }
  }


  /** ---------- Empty State Message ---------- */
  protected getEmptyStateMessage(): string {
    const searchQuery = this.searchQuery();
    const hasSearch = searchQuery && searchQuery.trim() !== '';

    // If there's a search query, show "no search results" message
    if (hasSearch) {
      return this.translateService.instant('general.noSearchResult') || 'No search results found';
    }

    // Otherwise, show category-specific message
    const selectedType = this.facade.selectedType();
    const typeLabel = this.translateService.instant(`properties.types.${selectedType}`);

    const lang = this.currentLanguage();
    if (lang === 'ar') {
      return `لا يوجد لهذه الفئة (${typeLabel})`;
    } else if (lang === 'en') {
      return `No properties for this category (${typeLabel})`;
    } else {
      return this.translateService.instant('properties.noPropertiesYet');
    }
  }

  /** ---------- Check if search is active ---------- */
  protected hasActiveSearch(): boolean {
    const searchQuery = this.searchQuery();
    return !!(searchQuery && searchQuery.trim() !== '');
  }
}
