import { Component, EventEmitter, inject, Input, Output, PLATFORM_ID, OnInit, OnDestroy, OnChanges, SimpleChanges } from '@angular/core'; // Added OnDestroy and OnChanges
import { CommonModule, isPlatformBrowser, NgOptimizedImage } from '@angular/common';
import { ListCardType } from 'src/app/Common/component/list-card/types-models/listType';
import { environment } from 'src/environments/environment';
import { TranslateModule } from '@ngx-translate/core';
import { CarouselModule } from 'primeng/carousel';
import { ListCardComponent } from 'src/app/Common/component/list-card/list-card.component';
import { SkeletonComponent } from 'src/app/modules/shared/components/skeleton/skeleton.component';
import { RouterModule } from '@angular/router';
import { LazyLoadImageDirective } from 'src/app/modules/shared/directives/lazy-load-image.directive';
import { PublicService } from 'src/app/modules/shared/services/public.service';
import { StripHtmlPipe } from 'src/app/Common/pipes/strip-html.pipe';

@Component({
  selector: 'app-swalef-section',
  standalone: true,
  imports: [CommonModule, TranslateModule, CarouselModule, ListCardComponent, SkeletonComponent, RouterModule, LazyLoadImageDirective, NgOptimizedImage, StripHtmlPipe],
  templateUrl: './swalef-section.component.html',
  styleUrls: ['./swalef-section.component.scss']
})
export class SwalefSectionComponent implements OnInit, OnDestroy, OnChanges { // Implemented OnDestroy and OnChanges
  @Input() items: any[] = [];
  @Input() link!: string;
  @Input() type!: ListCardType;
  @Input() isLoadingFavourite!: boolean;
  @Output() viewAllClicked = new EventEmitter<void>();
  @Output() favouriteClicked = new EventEmitter<any>();

  currentIndex = 0;
  isBrowser: boolean;
  responsiveOptions: any;
  currentLanguage!: string;

  selectedItem: any;
  private autoPlayInterval: any; // To store the interval ID

  private platformId = inject(PLATFORM_ID);
  public publicService = inject(PublicService);

  constructor() {
    this.isBrowser = isPlatformBrowser(this.platformId);
    this.currentLanguage = this.publicService.getCurrentLanguage();
  }

  ngOnInit() {
    if (this.isBrowser) {
      this.responsiveOptions = [
        {
          breakpoint: '767px',
          numVisible: 1,
          numScroll: 1
        }
      ];
    }
    // We'll handle initial selection and auto-play in ngOnChanges
    // to ensure items are loaded.
  }

  // Use ngOnChanges to react when the 'items' input data becomes available or changes.
  ngOnChanges(changes: SimpleChanges): void {
    if (changes['items'] && this.items && this.items.length > 0) {
      // Set the first item as selected when items are loaded
      this.selectedItem = this.items[this.currentIndex];

      // Start auto-play only if in browser and items exist
      if (this.isBrowser) {
        this.startAutoPlay();
      }
    }
  }

  // Lifecycle hook to clean up the interval when the component is destroyed
  ngOnDestroy(): void {
    this.stopAutoPlay();
  }

  // --- Auto-play Logic ---
  startAutoPlay(): void {
    // Clear any existing interval to prevent multiple intervals running
    this.stopAutoPlay();

    this.autoPlayInterval = setInterval(() => {
      this.nextItem();
    }, 3000);
  }

  stopAutoPlay(): void {
    if (this.autoPlayInterval) {
      clearInterval(this.autoPlayInterval);
      this.autoPlayInterval = null;
    }
  }

  nextItem(): void {
    // Calculate the next index, looping back to 0 if it goes past the last item
    this.currentIndex = (this.currentIndex + 1) % this.items.length;
    this.selectedItem = this.items[this.currentIndex];
  }
  // --- End Auto-play Logic ---

  getClass(index: number): string {
    const totalItems = this.items.length;
    if (totalItems === 0) return '';
    const position = (index - this.currentIndex + totalItems) % totalItems;

    if (totalItems === 3) {
      if (position === 2) {
        return 'gallery-item-2';
      }
    }

    switch (position) {
      case 0:
        return 'gallery-item-3';
      case 1:
        return 'gallery-item-4';
      case 2:
        return 'gallery-item-5';
      case totalItems - 1:
        return 'gallery-item-1';
      case totalItems - 2:
        return 'gallery-item-2';
      default:
        return '';
    }
  }

  onImageClick(index: number): void {
    // When an image is manually clicked, stop auto-play and then start it again
    // This allows the user to interact without the slider immediately jumping
    this.stopAutoPlay();
    this.currentIndex = index;
    this.selectedItem = this.items[this.currentIndex];
    this.startAutoPlay(); // Restart auto-play after a manual selection
  }

  viewAll() {
    this.viewAllClicked.emit();
  }

  addToFavourite(item: any) {
    this.favouriteClicked.emit(item);
  }
  getCategoryBackgroundColor(index: number): string {
    const colors = ['#CAD6FF', '#EDD3FF', '#C7F0D6', '#FFE0B2', '#FFC1E3']; // Add more colors as needed
    return colors[index % colors.length]; // Use modulo to cycle through colors if more categories than defined colors
  }
}
