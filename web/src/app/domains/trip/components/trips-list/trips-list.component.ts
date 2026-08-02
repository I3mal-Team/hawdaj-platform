/* ---------- Angular Core ---------- */
import {
  AfterViewInit,
  ChangeDetectionStrategy,
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
import { TripsFacade } from '../../facades';

/* ---------- Shared Components ---------- */
import { Banner2Component } from 'src/app/Common/layout/banner-2/banner-2.component';
import { NoResult2Component } from 'src/app/Common/layout/no-result-2/no-result-2.component';
import { SharedPaginationComponent } from 'src/app/Common/layout/shared-pagination/shared-pagination.component';
import { SkeletonComponent } from 'src/app/modules/shared/components/skeleton/skeleton.component';
import { NewFooterComponent } from 'src/app/modules/shared/components/new-footer/new-footer.component';
import { SvgIconComponent } from "src/app/shared/components/svg-icon";
import { TabListComponent, ITabItem, ITabListConfig } from 'src/app/shared/components/tab-list';
import { TripListCardComponent } from "src/app/components/my-trips/components/my-trips-list-v2/trip-list-card/trip-list-card.component";

/* ---------- Enums & Constants ---------- */
import { TripRouteData, TripRoutesEnum } from '../../constants';
import { TRAVEL_TYPE_TABS, TAB_LIST_CONFIG } from '../../configs';
import { environment } from 'src/environments/environment';
import { ITripItem } from '../../dtos';
import { ConfirmDeleteTripComponent } from 'src/app/components/my-trips/components/confirm-delete-trip/confirm-delete-trip.component';
import { PrepearTripStepperComponent } from '../prepear-trip-stepper';

// Types
export interface TravelType {
  name: string;
  value: string;
}

@Component({
  selector: 'app-trips-list',
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
    Banner2Component,
    NoResult2Component,
    SharedPaginationComponent,
    SkeletonComponent,
    NewFooterComponent,
    SvgIconComponent,
    TabListComponent,
    TripListCardComponent
  ],
  templateUrl: './trips-list.component.html',
  styleUrls: ['./trips-list.component.scss'],
  changeDetection: ChangeDetectionStrategy.OnPush
})
export class TripsListComponent implements AfterViewInit, OnDestroy {
  private readonly destroy$ = new Subject<void>();
  private readonly searchSubject = new Subject<string>();

  /** ---------- Facade ---------- */
  readonly facade = new TripsFacade();

  /** ---------- Signals ---------- */
  readonly homeShowFooter = signal(false);
  readonly currentLanguage = signal<string>('ar');
  readonly searchQuery = signal<string>('');
  readonly selectedTravelTypes = signal<TravelType[]>([]);

  /** ---------- SEO Texts ---------- */
  readonly pageTitle = signal<string>('');
  readonly pageDescription = signal<string>('');
  readonly breadcrumb = signal<string>('');

  /** ---------- Computed Signals ---------- */
  readonly isRtlLayout = computed(() => this.currentLanguage() === 'ar');

  /** ---------- Travel Type Filter Tabs ---------- */
  readonly travelTypeTabs = signal<ITabItem[]>(TRAVEL_TYPE_TABS);
  readonly selectedVehicleId = signal<number | null>(null);
  readonly tabListConfig = signal<ITabListConfig>(TAB_LIST_CONFIG);

  constructor(
    @Inject(PLATFORM_ID) private platformId: Object,
    private metadataService: MetadataService,
    private publicService: PublicService,
    private translateService: TranslateService,
    private dialogService: DialogService,
    private router: Router
  ) {
    // Setup search debouncing
    this.setupSearchDebounce();
  }

  ngAfterViewInit(): void {
    if (isPlatformBrowser(this.platformId)) {
      const lang = this.publicService.getCurrentLanguage() || 'ar';
      this.currentLanguage.set(lang);

      // Set SEO texts dynamically from TripsListPage
      this.pageTitle.set(TripRouteData.TripsListPage.title[lang]);
      this.pageDescription.set(TripRouteData.TripsListPage.meta.description[lang]);
      this.breadcrumb.set(TripRouteData.TripsListPage.breadcrumb[lang]);
    }

    this.updateMetaTags();
    this.facade.loadTrips(1, this.facade.perPage(), false, this.searchQuery(), this.selectedVehicleId());
  }

  ngOnDestroy(): void {
    this.destroy$.next();
    this.destroy$.complete();
    this.searchSubject.complete();
  }

  /** ---------- SEO ---------- */
  private updateMetaTags(): void {
    const lang = this.currentLanguage();
    const routeData = TripRouteData.TripsListPage;

    this.metadataService.updateTitle(routeData.title[lang]);
    this.metadataService.updateMetaTagsName([
      { name: 'title', content: routeData.title[lang] },
      { name: 'description', content: routeData.meta.description[lang] },
      { name: 'keyword', content: routeData.meta.keyword[lang] },
    ]);
    this.metadataService.updateMetaTagsProperty([
      { property: 'og:url', content: `${environment.publicUrl}/${lang}/${TripRoutesEnum.TRIP1}` },
      { property: 'og:title', content: routeData.title[lang] },
      { property: 'og:description', content: routeData.meta.description[lang] },
    ]);
    this.metadataService.setSharePreviewImage(null);
  }

  /** ---------- Search & Filter Functionality ---------- */
  private setupSearchDebounce(): void {
    this.searchSubject.pipe(
      debounceTime(750),
      takeUntil(this.destroy$)
    ).subscribe(query => {
      // Only search if query is empty or has at least 2 characters
      if (!query || query.trim().length >= 2) {
        this.facade.loadTrips(1, this.facade.perPage(), false, query, this.selectedVehicleId());
      }
    });
  }

  protected onSearchInput(event: Event): void {
    const query = (event.target as HTMLInputElement).value;
    this.searchQuery.set(query);

    // If query is less than 2 characters, clear search and load all trips
    if (query.trim().length < 2) {
      this.searchSubject.next('');
    } else {
      this.searchSubject.next(query);
    }
  }

  protected clearSearch(): void {
    this.searchQuery.set('');
    this.facade.loadTrips(1, this.facade.perPage(), false, '', this.selectedVehicleId());
  }

  protected onSearchButtonClick(): void {
    const query = this.searchQuery();
    // Only search if query has at least 2 characters
    if (query && query.trim().length >= 2) {
      this.facade.loadTrips(1, this.facade.perPage(), false, query, this.selectedVehicleId());
    }
  }


  /** ---------- Lazy-loaded Section Handler ---------- */
  protected onSectionInView(): void {
    this.homeShowFooter.set(true);
  }

  /** ---------- Navigation ---------- */
  protected exploreTrip(item: ITripItem): void {
    this.router.navigate([`/${TripRoutesEnum.TRIP1}`, item?.token]);
  }

  /** Delete trip with confirm dialog */
  deleteTrip(item?: ITripItem) {
    const ref = this.dialogService.open(ConfirmDeleteTripComponent, {
      width: '35%',
      header: this.publicService.translateTextFromJson('general.confirmDelete'),
      styleClass: 'auth-dialog confirm-delete-trip',
    });

    ref.onClose?.subscribe((res: any) => {
      if (res?.isConfirmed && item?.token) {
        this.facade.deleteTrip(item.token, () => {
          // Refresh trips after deletion
          this.facade.loadTrips(this.facade.currentPage(), this.facade.perPage(), false, this.searchQuery(), this.selectedVehicleId());
        });
      }
    });
  }

  /** ---------- Pagination ---------- */
  protected onPageChange(page: number): void {
    this.facade.loadTrips(page, this.facade.perPage(), false, this.searchQuery(), this.selectedVehicleId());
  }

  /** ---------- Travel Type Filter ---------- */
  protected onTravelTypeTabChange(tab: ITabItem | ITabItem[]): void {
    // Single selection mode
    if (!Array.isArray(tab)) {
      const selectedType = tab.data?.type;

      if (selectedType === 'all') {
        // Show all trips (no filter)
        console.log('Show all trips');
        this.selectedVehicleId.set(null);
        this.selectedTravelTypes.set([]);
      } else {
        // Filter by selected travel type
        console.log('Filter by travel type:', selectedType);
        const vehicleId = selectedType === 'air' ? 1 : selectedType === 'land' ? 2 : null;
        this.selectedVehicleId.set(vehicleId);

        // Update selected travel types signal
        this.selectedTravelTypes.set([{
          name: this.translateService.instant(
            selectedType === 'air' ? 'createTrip.airTravel' : 'createTrip.landTravel'
          ),
          value: selectedType
        }]);
      }

      this.facade.loadTrips(1, this.facade.perPage(), false, this.searchQuery(), this.selectedVehicleId());
    }
  }

  /** ---------- Create Trip Action ---------- */
  protected createTrip(): void {
    const ref: any = this?.dialogService?.open(PrepearTripStepperComponent, {
      width: '65%',
      height: '100vh',
      dismissableMask: false,
      styleClass: 'start-trip-dialog',
      baseZIndex: 10001,
    });
  }
}
