import { DynamicDialogConfig, DynamicDialogRef } from 'primeng/dynamicdialog';
import { PlacesService } from '../../../../services/places.service';
import { AlertsService } from 'src/app/services/alerts.service';
import { Component, ChangeDetectorRef } from '@angular/core';
import { debounceTime, Subscription, Subject } from 'rxjs';

@Component({
  selector: 'app-region-modal',
  templateUrl: './region-modal.component.html',
  styleUrls: ['./region-modal.component.scss']
})
export class RegionModalComponent {
  private unsubscribe: Subscription[] = [];

  private searchSubject = new Subject<any>();

  selectedService: any;
  skipCount: Number = 0;
  maxResultCount: number = 20;
  totalCount: any;
  isLoadingSearch: boolean = false;
  keyword: any = null;
  data: any;
  regions: any = [];
  searchRegions: any = [];
  isLoadingRegions: boolean = false;
  loadingMore: boolean = false;

  constructor(
    private alertsService: AlertsService,
    private placesService: PlacesService,
    public config: DynamicDialogConfig,
    private cdr: ChangeDetectorRef,
    public ref: DynamicDialogRef,
  ) { }

  ngOnInit(): void {
    this.getStatesLookup();
    this.searchSubject
      .pipe(
        debounceTime(500)
      )
      .subscribe((event: any) => {
        this.searchService(event);
      });
    this.data = this.config.data;
    this.regions = this.data.state;
  }


  getStatesLookup(preventLoading?: any): any {
    preventLoading ? this.loadingMore = true : this.isLoadingRegions = true;
    this.placesService?.getRegions()?.subscribe(
      (res: any) => {
        if (res?.code == 200) {
          // if (this.skipCount == 0) {
          this.regions = res?.data ? res?.data : [];
          this.searchRegions = this.regions;
          // } else {
          //   this.states.push(...(res?.data ? res?.data : []));
          // }
          this.isLoadingRegions = false;
          this.isLoadingSearch = false;
          this.loadingMore = false;
          this.totalCount = res?.data?.totalCount ? res?.data?.totalCount : 0;
        } else {
          this.isLoadingRegions = false;
          this.isLoadingSearch = false;
          this.loadingMore = false;
          res?.error?.message ? this.alertsService?.openToast('error', res?.error?.message) : '';
        }
      },
      (err: any) => {
        err ? this.alertsService?.openToast('error', err) : '';
        this.isLoadingRegions = false;
        this.isLoadingSearch = false;
        this.loadingMore = false;
      })
  }
  filterRegion(event: any): void {
    // this.isSearchCategories = true;
    this.searchRegions = this.regions?.filter((region: any) => {
      return region?.name?.toLocaleLowerCase()?.includes(event?.toLocaleLowerCase());
    });
  }
  loadMore(): void {
    this.skipCount = +this.skipCount + this.maxResultCount;
    this.skipCount <= this.totalCount + 1 ? this.getStatesLookup(true) : '';
  }
  searchService(event: any): void {
    this.keyword = event;
    this.skipCount = 0;
    this.isLoadingSearch = true;
    this.getStatesLookup();
  }

  handleSearch(event: any): void {
    this.searchSubject.next(event);
  }

  clearSearchValue(event: any): void {
    event.value = '';
    this.skipCount = 0;
    this.keyword = null;
    // this.isLoadingSearch = true;
    this.searchRegions = this.regions;
    // this.getStatesLookup();
  }
  selectState(item?: any): void {
    this.ref?.close({ region: item });
  }
  cancel(): void {
    this.ref?.close();
  }
  ngOnDestroy(): void {
    this.unsubscribe?.forEach((sb) => sb?.unsubscribe());
  }
}

