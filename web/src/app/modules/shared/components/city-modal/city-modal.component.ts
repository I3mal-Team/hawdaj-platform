import { DynamicDialogConfig, DynamicDialogRef } from 'primeng/dynamicdialog';
import { PlacesService } from '../../../../services/places.service';
import { AlertsService } from 'src/app/services/alerts.service';
import { Component, ChangeDetectorRef, PLATFORM_ID, Inject } from '@angular/core';
import { debounceTime, Subscription, Subject } from 'rxjs';
import { isPlatformBrowser } from '@angular/common';
@Component({
  selector: 'app-city-modal',
  templateUrl: './city-modal.component.html',
  styleUrls: ['./city-modal.component.scss']
})
export class CityModalComponent {
  private unsubscribe: Subscription[] = [];

  private searchSubject = new Subject<any>();

  selectedService: any;
  skipCount: Number = 0;
  maxResultCount: number = 20;
  totalCount: any;
  isLoadingSearch: boolean = false;
  keyword: any = null;
  data: any;
  cities: any = [];
  searchCities: any = [];
  isLoadingCities: boolean = false;
  loadingMore: boolean = false;
  regionId: any;

  constructor(
    private alertsService: AlertsService,
    private placesService: PlacesService,
    public config: DynamicDialogConfig,
    private cdr: ChangeDetectorRef,
    public ref: DynamicDialogRef,
    @Inject(PLATFORM_ID) private platformId: Object

  ) { }

  ngOnInit(): void {
    this.data = this.config.data?.data;
    this.regionId = this.config.data?.regionId;
    if (isPlatformBrowser(this.platformId) && this.regionId) {
      this.getCities();
    }
    if (isPlatformBrowser(this.platformId)) {

      this.searchSubject
        .pipe(
          debounceTime(500)
        )
        .subscribe((event: any) => {
          this.searchService(event);
        });
    }
  }

  getCities(preventLoading?: any): any {
    preventLoading ? this.loadingMore = true : this.isLoadingCities = true;
    this.placesService?.getCities(this.regionId)?.subscribe(
      (res: any) => {
        if (res?.code == 200) {
          // if (this.skipCount == 0) {
          this.cities = res?.data ? res?.data : [];
          this.searchCities = this.cities;
          // } else {
          //   this.states.push(...(res?.data ? res?.data : []));
          // }
          this.isLoadingCities = false;
          this.isLoadingSearch = false;
          this.loadingMore = false;
          this.totalCount = res?.data?.totalCount ? res?.data?.totalCount : 0;
        } else {
          this.isLoadingCities = false;
          this.isLoadingSearch = false;
          this.loadingMore = false;
          res?.error?.message ? this.alertsService?.openToast('error', res?.error?.message) : '';
        }
      },
      (err: any) => {
        err ? this.alertsService?.openToast('error', err) : '';
        this.isLoadingCities = false;
        this.isLoadingSearch = false;
        this.loadingMore = false;
      })
  }
  filterCities(event: any): void {
    // this.isSearchCategories = true;
    this.searchCities = this.cities?.filter((city: any) => {
      return city?.name?.toLocaleLowerCase()?.includes(event?.toLocaleLowerCase());
    });
  }
  loadMore(): void {
    this.skipCount = +this.skipCount + this.maxResultCount;
    this.skipCount <= this.totalCount + 1 ? this.getCities(true) : '';
  }
  searchService(event: any): void {
    this.keyword = event;
    this.skipCount = 0;
    this.isLoadingSearch = true;
    this.getCities();
  }

  handleSearch(event: any): void {
    if (isPlatformBrowser(this.platformId)) {
      this.searchSubject.next(event);
    }
  }

  clearSearchValue(event: any): void {
    event.value = '';
    this.skipCount = 0;
    this.keyword = null;
    // this.isLoadingSearch = true;
    this.searchCities = this.cities;
    // this.getCities();
  }
  selectCity(item?: any): void {
    this.ref?.close({ city: item });
  }
  cancel(): void {
    this.ref?.close();
  }
  ngOnDestroy(): void {
    this.unsubscribe?.forEach((sb) => sb?.unsubscribe());
  }
}

