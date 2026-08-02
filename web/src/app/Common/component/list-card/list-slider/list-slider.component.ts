import { Component, EventEmitter, inject, Inject, Input, Output, PLATFORM_ID } from '@angular/core';
import { CommonModule, isPlatformBrowser, NgOptimizedImage } from '@angular/common';
import { environment } from 'src/environments/environment';
import { TranslateModule } from '@ngx-translate/core';
import { CarouselModule } from 'primeng/carousel';
import { ListCardComponent } from "../list-card.component";
import { SkeletonComponent } from "../../../../modules/shared/components/skeleton/skeleton.component";
import { PublicService } from 'src/app/modules/shared/services/public.service';
import { RouterModule } from '@angular/router';
import { LazyLoadImageDirective } from 'src/app/modules/shared/directives/lazy-load-image.directive';
import { ListCardType } from '../types-models/listType';
import { StripHtmlPipe } from '../../../pipes/strip-html.pipe';

@Component({
  selector: 'app-list-slider',
  standalone: true,
  imports: [CommonModule, TranslateModule, CarouselModule, ListCardComponent, SkeletonComponent, RouterModule, LazyLoadImageDirective, NgOptimizedImage, StripHtmlPipe],
  templateUrl: './list-slider.component.html',
  styleUrls: ['./list-slider.component.scss']
})
export class ListSliderComponent {
  @Input() items: any;
  @Input() link: string;
  @Input() type: ListCardType;
  @Input() isLoadingFavourite: boolean
  @Output() viewAllClicked = new EventEmitter<void>();
  @Output() favouriteClicked = new EventEmitter<void>();
  currentIndex = 2;
  isBrowser: boolean;
  responsiveOptions: any;
  currentLanguage!: string;

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
  }

  getClass(index: number): string {
    const totalItems = this.items.length;
    const position = (index - this.currentIndex + totalItems) % totalItems;

    // If there are only 3 items, treat the 4th position as the 3rd item
    if (totalItems === 3) {
      if (position === 2) {
        return 'gallery-item-2'; // Place 3rd item in the 4th position
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
    this.currentIndex = index;
  }

  viewAll() {
    this.viewAllClicked.emit();
  }
  addToFavourite(item: any) {
    this.favouriteClicked.emit(item);
  }
}
