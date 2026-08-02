import { Component, ElementRef, HostListener, Inject, PLATFORM_ID, QueryList, ViewChild, ViewChildren, AfterViewInit, Input, Output, EventEmitter, SimpleChanges, inject, ChangeDetectorRef } from '@angular/core';
import { CommonModule, isPlatformBrowser } from '@angular/common';
import { TranslateModule } from '@ngx-translate/core';
import { SkeletonComponent } from 'src/app/modules/shared/components/skeleton/skeleton.component';
import { PublicService } from 'src/app/modules/shared/services/public.service';
import { Categories } from './interface/list-categories';
import { ActivatedRoute } from '@angular/router';

@Component({
  selector: 'app-tabs',
  standalone: true,
  imports: [CommonModule, TranslateModule, SkeletonComponent],
  templateUrl: './tabs.component.html',
  styleUrls: ['./tabs.component.scss']
})
export class TabsComponent {
  @Input() items: Categories = [];
  @Input() multiselect: boolean = true;
  @Input() defaultSelectedTab: number;
  @Input() enableAll: boolean = false;
  @Output() selectedItemsChange: EventEmitter<any[]> = new EventEmitter();

  selectedItems: any[] = [];

  currentLanguage!: string;

  @ViewChildren('placeItem') placeItems!: QueryList<ElementRef>;
  @ViewChild('previousBtn') previousBtn!: ElementRef;
  @ViewChild('nextBtn') nextBtn!: ElementRef;
  @ViewChild('listContainer') listContainer!: ElementRef;
  @ViewChild('list') list!: ElementRef;


  private platformId = inject(PLATFORM_ID);
  private cdr = inject(ChangeDetectorRef);
  public publicService = inject(PublicService);
  private route = inject(ActivatedRoute);


  constructor() {
    this.currentLanguage = this.publicService.getCurrentLanguage();
  }

  private prependAllCategory(): void {
    setTimeout(() => {
      const allCategory = {
        id: 0,
        name: this.publicService.translateTextFromJson('general.all'),
        icon: 'path_to_all_icon',
        isSelected: true
      };

      if (!this.items.some(item => item.id === 0)) {
        this.items = [allCategory, ...this.items];
        this.checkCategoryFromUrl()
      }
      this.cdr.detectChanges();
    });
  }


  ngAfterViewChecked() {
    if (isPlatformBrowser(this.platformId)) {
      this.updateSwiperBtnsVisibility();
      this.cdr.detectChanges();

      if (this.enableAll && this.items.length > 0) {
        this.prependAllCategory();
        this.cdr.detectChanges();
      }
    }
  }

  private checkCategoryFromUrl(): void {
    if (isPlatformBrowser(this.platformId)) {
      const categoryId = this.route.snapshot.queryParams['categoryId'];
      if (categoryId) {
        const category = this.items.find(item => item.id == categoryId);
        if (category) {
          this.selectedItems = [category];
          this.selectedItemsChange.emit(this.selectedItems);
        }
      }
    }
  }
  scrollLeft(container: HTMLElement) {
    if (isPlatformBrowser(this.platformId)) {
      const itemWidth = this.placeItems.first.nativeElement.offsetWidth;
      container.scrollBy({ left: -itemWidth, behavior: 'smooth' });
    }
  }

  scrollRight(container: HTMLElement) {
    if (isPlatformBrowser(this.platformId)) {
      const itemWidth = this.placeItems.first.nativeElement.offsetWidth;
      container.scrollBy({ left: itemWidth, behavior: 'smooth' });
    }
  }

  @HostListener('window:resize')
  onResize(): void {
    if (isPlatformBrowser(this.platformId)) {
      this.updateSwiperBtnsVisibility();
    }
  }

  updateSwiperBtnsVisibility(): void {
    let totalWidth = 0;

    this.placeItems.forEach((placeItem) => {
      totalWidth += placeItem.nativeElement.clientWidth;
    });
    if (isPlatformBrowser(this.platformId)) {
      if (this.list.nativeElement.clientWidth > (totalWidth + 21) || this.items.length === 0) {
        this.previousBtn.nativeElement.style.display = 'none';
        this.nextBtn.nativeElement.style.display = 'none';

      } else if (this.items) {
        this.previousBtn.nativeElement.style.display = 'flex';
        this.nextBtn.nativeElement.style.display = 'flex';
      }
    }
  }

  selectItem(item: any): void {

    if (item.id === 0) {
      if (this.selectedItems.includes(item)) {
        return;
      }
      this.selectedItems = [item];
    } else {
      if (this.multiselect) {
        const index = this.selectedItems.findIndex(selected => selected.id === item.id);

        if (index > -1) {
          this.selectedItems.splice(index, 1);
        } else {
          this.selectedItems.push(item);
        }

        this.selectedItems = this.selectedItems.filter(selected => selected.id !== 0);
      } else {
        this.selectedItems = [item];
      }
    }
    if (this.enableAll === true && this.selectedItems.length === 0) {
      this.selectedItems = [this.items[0]];
      this.currentLanguage === 'ar' ? this.listContainer.nativeElement.scrollBy({ left: this.listContainer.nativeElement.clientWidth, behavior: 'smooth' }) : this.listContainer.nativeElement.scrollBy({ left: -this.listContainer.nativeElement.clientWidth, behavior: 'smooth' });
    }

    this.selectedItemsChange.emit(this.selectedItems);
  }

  isSelected(item: any): boolean {
    if (item.id === 0) {
      return this.selectedItems.length > 0 && this.selectedItems[0].id === 0;
    }
    return this.selectedItems.includes(item);
  }

  resetCategories(): void {
    this.enableAll == true ? this.selectedItems = [this.items.find(item => item.id === 0)] : this.selectedItems = [];
    this.selectedItemsChange.emit(this.selectedItems);
    this.currentLanguage === 'ar' ? this.listContainer.nativeElement.scrollBy({ left: this.listContainer.nativeElement.clientWidth, behavior: 'smooth' }) : this.listContainer.nativeElement.scrollBy({ left: -this.listContainer.nativeElement.clientWidth, behavior: 'smooth' });
  }
}
