import { Component, Output, EventEmitter, ChangeDetectorRef, Inject, PLATFORM_ID, inject } from '@angular/core';
import { PlacesService } from '../../../../../../services/places.service';
import { environment } from './../../../../../../../environments/environment';
import { PublicService } from './../../../../services/public.service';
import { keys } from './../../../../configs/localstorage-key';
import { Subscription, Subject, debounceTime } from 'rxjs';
import { CommonModule, isPlatformBrowser } from '@angular/common';
import { Router, RouterModule } from '@angular/router';
import { TranslateModule } from '@ngx-translate/core';
import { SkeletonComponent } from '../../../skeleton/skeleton.component';
import { AlertsService } from 'src/app/services/alerts.service';
import { NoResultComponent } from "../../../../../../Common/layout/no-result/no-result.component";
import { StripHtmlPipe } from "../../../../../../Common/pipes/strip-html.pipe";

@Component({
  standalone: true,
  imports: [
    CommonModule,
    RouterModule,
    TranslateModule,
    SkeletonComponent,
    NoResultComponent,
    StripHtmlPipe
  ],
  selector: 'app-invite-to-visit',
  templateUrl: './invite-to-visit.component.html',
  styleUrls: ['./invite-to-visit.component.scss']
})
export class InviteToVisitComponent {
  private unsubscribe: Subscription[] = [];
  currentLanguage: any;
  private searchSubject = new Subject<any>();

  public isVisitMegaMenuVisible = false;
  @Output() openPlaceHandler = new EventEmitter();
  isLoadingCategories: boolean = false;
  categoriesList: any = [];

  placesList: any = [];
  placesListTotalCount: number = 0;
  isLoadingPlaces: boolean = false;
  placesListKeyword: any = null;
  placeName: any = null;
  regionId: any = null;
  cityId: any = null;
  categoryId: any = null;
  subCategoryId: any = null;

  isLoadingSearch: boolean = false;
  page: any = 1;
  perPage: any = 6;
  placeKeyword: any = null;
  private platformId = inject(PLATFORM_ID);
  private publicService = inject(PublicService);
  private alertsService = inject(AlertsService);
  private placesService = inject(PlacesService);
  private cdr = inject(ChangeDetectorRef);
  private router = inject(Router);

  constructor() { }

  ngOnInit(): void {
    if (isPlatformBrowser(this.platformId)) {
      this.currentLanguage = this.publicService.getCurrentLanguage();
    }
    this.getAllCategories();

    this.searchSubject.pipe(debounceTime(500)).subscribe(event => {
      this.searchService(event);
    });
  }

  getAllCategories(hideLoading?: boolean): void {
    hideLoading ? '' : this.isLoadingCategories = true;
    this.placesService?.getCategories()?.subscribe(
      (res: any) => {
        if (res?.code == 200) {
          this.categoriesList = res?.data;
          this.categoriesList[0]?.id;
          this.getPlacesByCategoryId(this.categoriesList[0]?.id);
          this.isLoadingCategories = false;
        } else {
          res?.message
            ? this.alertsService?.openToast('error', res?.message)
            : '';
          this.isLoadingCategories = false;
        }
      },
      (err: any) => {
        err ? this.alertsService?.openToast('error', err) : '';
        this.isLoadingCategories = false;
      }
    );
  }
  handleSearch(event: any): void {
    this.searchSubject.next(event);
  }
  searchService(event: any): void {
    this.placesListKeyword = event;
    this.page = 1;
    this.isLoadingSearch = true;
    this.getPlacesList(true);
  }
  clearSearchValue(event: any): void {
    event.value = '';
    this.page = 1;
    this.placesListKeyword = null;
    this.isLoadingSearch = true;
    this.getPlacesList(true);
  }
  openPlaceWithCategoryId(id?: any): void {
    this.openPlaceHandler.emit();

    const navigationExtras = id ? { queryParams: { categoryId: id } } : {};
    this.router.navigate(['/places'], navigationExtras);
    if (this.router.url.includes('/places')) {
      this.router.navigate(['/places'], navigationExtras);

      if (isPlatformBrowser(this.platformId)) {
        setTimeout(() => {
          window.location.reload();
        }, 0);
      }
    }

  }

  openPlaceDetails(id: any): void {
    if (id) {
      this.openPlaceHandler?.emit();
      this.router?.navigate(['/places/details/', id]);
      this.publicService?.placeCategoryDetails?.next(id);
    }
  }
  getPlacesList(hideFullLoading?: boolean): void {
    if (hideFullLoading) {
      this.isLoadingPlaces = false;
    } else {
      this.isLoadingPlaces = true;
    }
    this.placesService?.getPlaces(this.page, this.perPage, this.placesListKeyword, this.placeName, this.regionId, this.cityId, this.categoryId, this.subCategoryId)?.subscribe(
      (res: any) => {
        if (res?.code == 200) {
          this.placesList = res?.data?.items ? res?.data?.items : [];
          this.placesListTotalCount = res?.data?.total ? res?.data?.total : 0;
          this.isLoadingPlaces = false;
          this.isLoadingSearch = false;
        } else {
          res?.message ? this.alertsService?.openToast('error', res?.message) : '';
          this.isLoadingPlaces = false;
          this.isLoadingSearch = false;
        }
      },
      (err: any) => {
        err ? this.alertsService?.openToast('error', err) : '';
        this.isLoadingPlaces = false;
        this.isLoadingSearch = false;
      }
    );
    this.cdr.detectChanges();
  }
  getPlacesByCategoryId(category_id?: any): void {
    this.categoryId = category_id;
    this.getPlacesList();
  }

  ngOnDestroy(): void {
    this.unsubscribe?.forEach((sb) => sb?.unsubscribe());
  }
}
