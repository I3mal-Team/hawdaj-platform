import { ChangeDetectorRef, Component, Inject, Input, OnInit, PLATFORM_ID, OnDestroy } from '@angular/core';
import { environment } from '../../../../../environments/environment';
import { CommonModule, NgOptimizedImage, isPlatformBrowser } from '@angular/common';
import { TranslateModule } from '@ngx-translate/core';
import { Subscription, interval } from 'rxjs';
import { Router, RouterModule } from '@angular/router';
import { takeWhile } from 'rxjs/operators';
import { LazyLoadDirective } from 'src/app/shared/directives/lazy-load.directive';
import { StripHtmlPipe } from 'src/app/Common/pipes/strip-html.pipe';

@Component({
  selector: 'app-animated-image-slider-v3',
  standalone: true,
  imports: [
    TranslateModule,
    CommonModule,
    RouterModule,
    NgOptimizedImage,
    // Directives
    LazyLoadDirective,
    // Pipes
    StripHtmlPipe
  ],
  templateUrl: './animated-image-slider-v3.component.html',
  styleUrls: ['./animated-image-slider-v3.component.scss']
})
export class AnimatedImageSliderV3Component implements OnInit, OnDestroy {
  @Input() autoPlay: boolean = false;
  @Input() autoPlayInterval: number = 7000;  // milliseconds
  @Input() enableTimeProgress: boolean = false;
  activeIndex: number = 0;
  timeProgressWidth: string = '0%';
  private isComponentActive: boolean = true;
  private autoSlideSubscription: Subscription;

  private isBrowser: boolean;

  @Input() items: any = [];  // This should be populated with the actual interface structure.

  constructor(
    @Inject(PLATFORM_ID) private platformId: Object,
    private cdr: ChangeDetectorRef,
    private router: Router
  ) {
    this.isBrowser = isPlatformBrowser(this.platformId);
  }

  ngOnInit(): void {
    // this.initializeItems();
    if (isPlatformBrowser(this.platformId) && this.autoPlay) {
      this.setupAutoSlide();
    }
  }


  private reorderItems(): void {
    // Check if there's a need to rotate
    if (this.activeIndex !== 0) {
      // Rotate the array to make the active item the first element
      this.items = [
        ...this.items?.slice(this.activeIndex),
        ...this.items?.slice(0, this.activeIndex)
      ];
      // Reset active index to 0
      this.activeIndex = 0;
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
    if (this.isBrowser) {
      this.startProgress();
    }
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
  private reIntialProgress(): void {
    this.enableTimeProgress = false;
    setTimeout(() => {
      this.enableTimeProgress = true;
    }, 0);
  }

  private resetAutoSlide(): void {
    if (this.autoSlideSubscription) {
      this.autoSlideSubscription.unsubscribe();
      if (this.autoPlay && this.isBrowser) {
        this.setupAutoSlide();
      }
    }
  }

  trackByFn(index: number, item: any): number {
    return index; // or item.id if your items have a unique identifier
  }

  showDetails(item: any): void {
    if (item?.slug) {
      this.router.navigate(['/places/details/', item?.slug])
    }
  }


  ngOnDestroy(): void {
    this.isComponentActive = false;
    if (this.autoSlideSubscription) {
      this.autoSlideSubscription.unsubscribe();
    }
  }
}
