import { ChangeDetectorRef, Component, Inject, Input, PLATFORM_ID } from '@angular/core';
import { PublicService } from './../../../shared/services/public.service';
import { environment } from './../../../../../environments/environment';
import { CommonModule, NgOptimizedImage, isPlatformBrowser } from '@angular/common';
import { Subscription, interval, takeWhile } from 'rxjs';
import { TranslateModule } from '@ngx-translate/core';
import { RouterModule } from '@angular/router';
import { LazyLoadImageDirective } from '../../directives/lazy-load-image.directive';

@Component({
  selector: 'app-animated-image-slider-v4',
  standalone: true,
  imports: [CommonModule, RouterModule, TranslateModule, NgOptimizedImage, LazyLoadImageDirective],
  templateUrl: './animated-image-slider-v4component.html',
  styleUrls: ['./animated-image-slider-v4component.scss']
})
export class AnimatedImageSliderV4Component {
  currentLanguage: string = '';
  @Input() autoPlay: boolean = false;
  @Input() autoPlayInterval: number = 7000;  // milliseconds
  @Input() enableTimeProgress: boolean = false;
  activeIndex: number = 2;
  timeProgressWidth: string = '0%';
  private isComponentActive: boolean = true;
  private autoSlideSubscription: Subscription;

  @Input() items: any = [];  // This should be populated with the actual interface structure.

  constructor(
    @Inject(PLATFORM_ID) private platformId: Object,
    private publicService: PublicService,
    private cdr: ChangeDetectorRef,
  ) { }

  ngOnInit(): void {
    this.currentLanguage = this.publicService.getCurrentLanguage();
    // this.initializeItems();
    if (isPlatformBrowser(this.platformId) && this.autoPlay) {
      this.setupAutoSlide();
    }
  }

  private reorderItems(): void {
    // Check if there's a need to rotate
    if (this.activeIndex !== 2) {
      // Rotate the array to make the active item the first element
      this.items = [
        ...this.items?.slice(this.activeIndex),
        ...this.items?.slice(0, this.activeIndex)
      ];
      // Reset active index to 0
      this.activeIndex = 2;
    }
  }
  setActive(index: number): void {
    this.activeIndex = index;
    this.reorderItems(); // Rotate items to make the new active item first
    this.resetAutoSlide();
  }
  next(): void {
    this.activeIndex = (this.activeIndex + 1) % this.items?.length;
    this.reorderItems(); // Rotate items to make the new active item first
    this.timeProgressWidth = '0%';
    this.resetAutoSlide();
    this.startProgress();
  }
  prev(): void {
    this.activeIndex = (this.activeIndex - 1 + this.items?.length) % this.items?.length;
    this.reorderItems(); // Rotate items to make the new active item first
    this.timeProgressWidth = '0%';
    this.resetAutoSlide();
  }

  private setupAutoSlide(): void {
    if (isPlatformBrowser(this.platformId)) {
      this.autoSlideSubscription = interval(this.autoPlayInterval)
        .pipe(takeWhile(() => this.isComponentActive))
        .subscribe(() => {
          this.next();
          this.startProgress();
        });
      this.startProgress();
    }
  }
  private startProgress(): void {
    this.enableTimeProgress ? this.reIntialProgress() : '';
    if (isPlatformBrowser(this.platformId)) {
      const increment = 100 / (this.autoPlayInterval / 100); // Calculate progress increment per 100ms
      let progress = 0;
      const progressInterval = setInterval(() => {
        progress += increment;
        this.timeProgressWidth = `${progress}%`;
        if (progress >= 100) {
          clearInterval(progressInterval);
        }
      }, 100);
    }
  }
  private reIntialProgress(): void {
    this.enableTimeProgress = false;
    setTimeout(() => {
      this.enableTimeProgress = true;
    }, 0);
  }

  private resetAutoSlide(): void {
    if (this.autoSlideSubscription) {
      this.autoSlideSubscription.unsubscribe();
      if (isPlatformBrowser(this.platformId) && this.autoPlay) {
        this.setupAutoSlide();
      }
    }
  }

  trackByFn(index: number, item: any): number {
    return index; // or item.id if your items have a unique identifier
  }

  ngOnDestroy(): void {
    this.isComponentActive = false;
    if (this.autoSlideSubscription) {
      this.autoSlideSubscription.unsubscribe();
    }
  }
}
