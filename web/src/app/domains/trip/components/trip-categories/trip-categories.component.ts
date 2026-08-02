import { PlacesService } from '../../../../services/places.service';
import { AlertsService } from 'src/app/services/alerts.service';
import { ChangeDetectionStrategy, ChangeDetectorRef, Component, EventEmitter, inject, Output, PLATFORM_ID } from '@angular/core';
import { TranslateModule } from '@ngx-translate/core';
import { Subscription, catchError, tap } from 'rxjs';
import { SkeletonModule } from 'primeng/skeleton';
import { CommonModule, isPlatformBrowser } from '@angular/common';
import { CategoryCardComponent } from '../category-card';
import { Category } from '../../interfaces';
import { PublicService } from 'src/app/modules/shared/services/public.service';

@Component({
  selector: 'trip-categories',
  standalone: true,
  imports: [CommonModule, TranslateModule, CategoryCardComponent, SkeletonModule],
  templateUrl: './trip-categories.component.html',
  styleUrls: ['./trip-categories.component.scss'],
  changeDetection: ChangeDetectionStrategy.OnPush
})
export class TripCategoriesComponent {
  private subscriptions: Subscription[] = [];
  private platformId = inject(PLATFORM_ID);
  private publicService = inject(PublicService);

  @Output() selectedCategory = new EventEmitter<{ categoriesIds: any[] }>();

  categoriesList: Category[] = [];
  searchCategories: Category[] = [];
  categoriesIds: any = [];
  isLoadingCategories: boolean = false;
  isSearchCategories: boolean = false;
  currentLanguage: string = 'ar';

  constructor(
    private placesService: PlacesService,
    private alertsService: AlertsService,
    private cdr: ChangeDetectorRef
  ) { }
  ngOnInit(): void {
    if (isPlatformBrowser(this.platformId)) {
      this.currentLanguage = this.publicService.getCurrentLanguage();
    }
    this.getCategories();
  }

  getCategories(): void {
    this.isLoadingCategories = true;
    this.cdr.detectChanges();
    let categoriesSubscription: any = this.placesService?.getCategories()?.pipe(
      tap(res => this.handleSuccessCategories(res)),
      catchError(err => this.handleError(err))
    ).subscribe();
    this.subscriptions.push(categoriesSubscription);
  }
  private handleSuccessCategories(res: any): void {
    if (res?.code == 200) {
      this.categoriesList = res?.data;
      this.initializeCategories();
      this.isLoadingCategories = false;
      this.cdr.detectChanges();
    } else {
      this.handleError(res?.error?.message || 'An error has occurred');
    }
  }
  private initializeCategories(): void {
    this.searchCategories = this.categoriesList?.map((item: any) => ({ ...item, isChecked: false }));
  }
  private handleError(err: any): any {
    this.setErrorMessage(err || 'An error has occurred');
  }
  private setErrorMessage(message: string): void {
    this.alertsService?.openToast('error', message)
    this.isLoadingCategories = false
    this.cdr.detectChanges();
  }

  filterCategory(event: any): void {
    this.isSearchCategories = true;
    this.searchCategories = this.categoriesList?.filter((category: any) => {
      return category?.name?.toLocaleLowerCase()?.includes(event?.toLocaleLowerCase());
    });
  }
  clearSearchValue(search: any): void {
    search.value = '';
    this.isSearchCategories = false;
    this.searchCategories = this.categoriesList;
  }

  selectCategory(): void {
    this.categoriesIds = this.getSelectedCategoryIds(this.searchCategories);
    this.selectedCategoryEmitter();
    this.cdr.detectChanges();
  }
  selectAll(): void {
    // Update all items
    this.searchCategories.forEach((item: any) => {
      item.isChecked = true;
    });
    this.categoriesList.forEach((item: any) => {
      item.isChecked = true;
    });
    
    // Re-create array to trigger change detection
    this.searchCategories = [...this.searchCategories];
    this.categoriesIds = this.getSelectedCategoryIds(this.categoriesList);
    this.selectedCategoryEmitter();
    this.cdr.detectChanges();
  }
  resetCategory(): void {
    // Update all items
    this.searchCategories.forEach((item: any) => {
      item.isChecked = false;
    });
    this.categoriesList.forEach((item: any) => {
      item.isChecked = false;
    });
    
    // Re-create array to trigger change detection
    this.searchCategories = [...this.searchCategories];
    this.categoriesIds = [];
    this.selectedCategoryEmitter();
    this.cdr.detectChanges();
  }
  private toggleCheckStatus(items: any[], isChecked: boolean): void {
    items.forEach((item: any) => {
      item.isChecked = isChecked;
    });
  }
  private getSelectedCategoryIds(items: any[]): any[] {
    return items?.filter((item: any) => item?.isChecked)?.map((item: any) => item?.id) || [];
  }

  selectedCategoryEmitter(): void {
    this.selectedCategory.emit({
      categoriesIds: this.categoriesIds
    })
  }

  ngOnDestroy(): void {
    this.subscriptions?.forEach((sb) => sb?.unsubscribe());
  }
}
