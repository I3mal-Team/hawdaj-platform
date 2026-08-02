import { ChangeDetectorRef, Component, Inject, Input, PLATFORM_ID } from '@angular/core';
import { CommonModule, NgOptimizedImage, isPlatformBrowser } from '@angular/common';
import { Subscription, interval, takeWhile } from 'rxjs';
import { TranslateModule } from '@ngx-translate/core';
import { RouterModule } from '@angular/router';
import { LazyLoadImageDirective } from 'src/app/modules/shared/directives/lazy-load-image.directive';
import { environment } from 'src/environments/environment';
import { IAppItem } from 'src/app/interfaces/home';
import { PublicService } from 'src/app/modules/shared/services/public.service';
import { StripHtmlPipe } from 'src/app/Common/pipes/strip-html.pipe';

@Component({
  selector: 'popular-apps',
  standalone: true,
  imports: [CommonModule, RouterModule, TranslateModule, LazyLoadImageDirective, NgOptimizedImage, StripHtmlPipe],
  templateUrl: './popular-apps.component.html',
  styleUrls: ['./popular-apps.component.scss']
})
export class PopularAppsComponent {
  currentLanguage: string = '';
  @Input() autoPlay: boolean = false;
  @Input() autoPlayInterval: number = 7000;  // milliseconds
  @Input() enableTimeProgress: boolean = false;
  activeIndex: number = 2;
  timeProgressWidth: string = '0%';
  private isComponentActive: boolean = true;
  private autoSlideSubscription: Subscription;

  @Input() items: IAppItem[] = [];  // This should be populated with the actual interface structure.

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
    this.autoSlideSubscription = interval(this.autoPlayInterval)
      .pipe(takeWhile(() => this.isComponentActive))
      .subscribe(() => {
        this.next();
        this.startProgress();
      });
    this.startProgress();
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
      if (this.autoPlay) {
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
