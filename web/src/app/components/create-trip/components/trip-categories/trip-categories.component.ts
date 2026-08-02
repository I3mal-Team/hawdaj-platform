import { CategoryCardComponent } from '../category-card/category-card.component';
import { PlacesService } from '../../../../services/places.service';
import { AlertsService } from 'src/app/services/alerts.service';
import { Component, EventEmitter, Output } from '@angular/core';
import { Category } from '../../../../interfaces/create-trip';
import { TranslateModule } from '@ngx-translate/core';
import { Subscription, catchError, tap } from 'rxjs';
import { SkeletonModule } from 'primeng/skeleton';
import { CommonModule } from '@angular/common';

@Component({
  selector: 'trip-categories',
  standalone: true,
  imports: [CommonModule, TranslateModule, CategoryCardComponent, SkeletonModule],
  templateUrl: './trip-categories.component.html',
  styleUrls: ['./trip-categories.component.scss']
})
export class TripCategoriesComponent {
  private subscriptions: Subscription[] = [];

  @Output() selectedCategory = new EventEmitter<{ categoriesIds: any[] }>();

  categoriesList: Category[] = [];
  searchCategories: Category[] = [];
  categoriesIds: any = [];
  isLoadingCategories: boolean = false;
  isSearchCategories: boolean = false;

  constructor(
    private placesService: PlacesService,
    private alertsService: AlertsService,
  ) { }
  ngOnInit(): void {
    this.getCategories();
  }

  getCategories(): void {
    this.isLoadingCategories = true;
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
  }
  selectAll(): void {
    this.toggleCheckStatus(this.searchCategories, true);
    this.toggleCheckStatus(this.categoriesList, true);
    this.categoriesIds = this.getSelectedCategoryIds(this.categoriesList);
    this.selectedCategoryEmitter();
  }
  resetCategory(): void {
    this.toggleCheckStatus(this.searchCategories, false);
    this.toggleCheckStatus(this.categoriesList, false);
    this.categoriesIds = [];
    this.selectedCategoryEmitter();
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
