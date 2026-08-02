
// Modules
import { DynamicDialogConfig, DynamicDialogRef } from 'primeng/dynamicdialog';
import { Component, Inject, PLATFORM_ID } from '@angular/core';
import { Subject, Subscription, debounceTime } from 'rxjs';
import { FormBuilder, FormsModule, ReactiveFormsModule, Validators } from '@angular/forms';
import { CommonModule, isPlatformBrowser } from '@angular/common';
// Services
import { AlertsService } from 'src/app/services/alerts.service';
import { PublicService } from 'src/app/modules/shared/services/public.service';
import { TripsService } from 'src/app/services/trips.service';
import { TranslateModule } from '@ngx-translate/core';
import { DropdownModule } from 'primeng/dropdown';
import { InputTextModule } from 'primeng/inputtext';
import { RouterModule } from '@angular/router';
import { SkeletonModule } from 'primeng/skeleton';

@Component({
  standalone: true,
  imports: [
    CommonModule,
    TranslateModule,
    ReactiveFormsModule,
    FormsModule,
    DropdownModule,
    InputTextModule,
    RouterModule,
    SkeletonModule
  ],
  selector: 'app-add-another-place',
  templateUrl: './add-another-place.component.html',
  styleUrls: ['./add-another-place.component.scss']
})
export class AddAnotherPlaceComponent {
  private unsubscribe: Subscription[] = [];
  private searchSubject = new Subject<any>();

  date: any;
  allDates: any = [];

  isLoadingSearch: boolean = false;
  data: any;
  tripId: any;
  suggestedPlaces: any = [];
  searchPlaces: any = [];
  isLoading: boolean = false;

  skipCount: any = 0;
  maxResultCount: any = 4;
  keyword: any = null;
  totalCount: any;

  placeForm: any = this.fb.group(
    {
      date: [null, [Validators.required]]
    }
  );
  get formControls(): any {
    return this.placeForm?.controls;
  }
  constructor(
    @Inject(PLATFORM_ID) private platformId: Object,
    private alertsService: AlertsService,
    public publicService: PublicService,
    private tripsService: TripsService,
    private config: DynamicDialogConfig,
    private ref: DynamicDialogRef,
    private fb: FormBuilder
  ) { }

  ngOnInit(): void {
    this.publicService.closeModal.subscribe((res: boolean) => {
      if (res == true) {
        this.ref.close();
        this.publicService.closeModal.next(false);
      }
    });
    this.tripId = this.config?.data?.id;
    this.config?.data?.allDates?.forEach((item: any) => {
      this.allDates?.push({
        title: this.publicService?.convertTimeOrDate(item, 'date2'),
        date: item,
      });
    });

    this.searchSubject
      .pipe(
        debounceTime(1000)
      )
      .subscribe(event => {
        this.searchService(event);
      });
    this.getSuggestedPlaces();
  }

  getSuggestedPlaces(preventLoading?: any): void {
    if (isPlatformBrowser(this.platformId)) {
      preventLoading ? '' : this.isLoading = true;
      this.tripsService?.getSuggestedPlaces(this.skipCount, this.maxResultCount, this.keyword)?.subscribe(
        (res: any) => {
          if (res?.code == 200) {
            this.isLoading = false;
            this.isLoadingSearch = false;
            this.suggestedPlaces = res?.result;
            this.totalCount = res?.totalCount;
          } else {
            this.isLoading = false;
            this.isLoadingSearch = false;
            res?.message
              ? this.alertsService?.openToast('error', res?.message)
              : '';
          }
        },
        (err: any) => {
          // err ? this.alertsService?.openToast('error', err?.message) : '';
          this.isLoading = false;
          this.isLoadingSearch = false;
        }
      );
      setTimeout(() => {
        this.isLoading = false;
      }, 2400);
      let data = [{
        id: 1,
        name: 'String arch',
        location: 'Riyadh, Riyadh',
        image: 'assets/images/home/dummy/img5.png',
        isAdded: false
      }, {
        id: 2,
        name: 'Natural arch',
        location: 'Riyadh, Riyadh',
        image: 'assets/images/home/dummy/img4.png',
        isAdded: false
      }, {
        id: 3,
        name: 'Natural arch',
        location: 'Riyadh, Riyadh',
        image: 'assets/images/home/dummy/img6.png',
        isAdded: false
      }, {
        id: 4,
        name: 'Natural arch',
        location: 'Riyadh, Riyadh',
        image: 'assets/images/home/dummy/img7.png',
        isAdded: false
      },
      ];
      this.suggestedPlaces = data;
      this.searchPlaces = this.suggestedPlaces;
    }
  }

  handleSearch(event: any): void {
    this.searchSubject.next(event);
  }
  searchService(event: any): void {
    this.keyword = event;
    this.skipCount = 0;
    this.isLoadingSearch = true;
    this.getSuggestedPlaces();
  }
  clearSearchValue(event: any): void {
    event.value = '';
    this.skipCount = 0;
    this.keyword = null;
    this.isLoadingSearch = true;
    this.getSuggestedPlaces();
  }
  // filterPlaces(event: any): void {
  //   this.searchPlaces = this.suggestedPlaces?.filter((place: any) => {
  //     return place?.name?.toLocaleLowerCase()?.includes(event?.toLocaleLowerCase());
  //   });
  // }
  // clearSearchValue(search: any): void {
  //   search.value = '';
  //   this.searchPlaces = this.suggestedPlaces;
  // }

  addPlace(item: any): void {
    this.suggestedPlaces?.forEach((element: any) => {
      if (element?.id == item?.id) {
        element.isAdded = true;
      }
    });
  }
  removePlace(item: any): void {
    this.suggestedPlaces?.forEach((element: any) => {
      if (element?.id == item?.id) {
        element.isAdded = false;
      }
    });
  }

  save(): void {
    if (isPlatformBrowser(this.platformId)) {
      if (this.placeForm?.valid) {
        let data: any;
        let placesId: any = [];
        this.suggestedPlaces?.forEach((element: any) => {
          if (element?.isAdded) {
            placesId?.push(element?.id);
          }
        });
        data = {
          placesIds: placesId,
          date: this.date,
          tripId: this.tripId
        }
        this.tripsService?.addPlaces(data)?.subscribe(
          (res: any) => {
            if (res?.code == 200) {
              this.isLoading = false;
              this.suggestedPlaces = res?.result;
              this.totalCount = res?.totalCount;
            } else {
              this.isLoading = false;
              res?.message
                ? this.alertsService?.openToast('error', res?.message)
                : '';
            }
          },
          (err: any) => {
            err ? this.alertsService?.openToast('error', err?.message) : '';
            this.isLoading = false;
          }
        );
      } else {
        this.publicService?.validateAllFormFields(this.placeForm);
      }
    }
  }
  close(): void {
    this.ref?.close();
  }

  loadMore(): void {
    this.skipCount = +this.skipCount + this.maxResultCount;
    this.skipCount <= this.totalCount + 1 ? this.getSuggestedPlaces(true) : '';
  }

  ngOnDestroy(): void {
    this.unsubscribe?.forEach((sb) => sb?.unsubscribe());
  }
}
