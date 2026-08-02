import { Component, ElementRef, inject, Input, ViewChild, AfterViewInit, Renderer2, ViewChildren, QueryList, Inject, PLATFORM_ID, HostListener } from '@angular/core';
import { CommonModule, isPlatformBrowser } from '@angular/common';
import { environment } from 'src/environments/environment';
import { TranslateModule } from '@ngx-translate/core';
import { Router } from '@angular/router';
import { PublicService } from 'src/app/modules/shared/services/public.service';
import { Place, places } from 'src/app/components/places/components/places-list-v2/interfaces/places-list';

@Component({
  selector: 'app-recommended-places-slider',
  standalone: true,
  imports: [CommonModule, TranslateModule],
  templateUrl: './recommended-places-slider.component.html',
  styleUrls: ['./recommended-places-slider.component.scss']
})
export class RecommendedPlacesSliderComponent implements AfterViewInit {
  @Input() places: places[];
  @ViewChild('slider') slider!: ElementRef;
  private router = inject(Router);
  private renderer = inject(Renderer2);
  public publicService = inject(PublicService);

  @ViewChild('list', { static: false }) list!: ElementRef;
  @ViewChildren('placeItems') placeItems!: QueryList<ElementRef>;
  @ViewChild('previousBtn', { static: false }) previousBtn!: ElementRef;
  @ViewChild('nextBtn', { static: false }) nextBtn!: ElementRef;

  scrollLeftValue = 0;
  clientWidth = 0;
  scrollWidth = 0;
  dir: 'ltr' | 'rtl' = 'ltr';
  currentLanguage!: string;


  constructor(@Inject(PLATFORM_ID) private platformId: Object) {
    this.currentLanguage = this.publicService.getCurrentLanguage();

  }

  ngAfterViewInit() {
    if (isPlatformBrowser(this.platformId)) {
      this.clientWidth = this.list.nativeElement.clientWidth;
      this.scrollWidth = this.slider.nativeElement.scrollWidth;
      this.updateSwiperBtnsVisibility();
    }
  }

  scrollLeft() {
    if (isPlatformBrowser(this.platformId)) {
      const itemWidth = this.placeItems.first.nativeElement.offsetWidth;
      const scrollAmount = this.dir === 'rtl' ? itemWidth : -itemWidth;
      this.slider.nativeElement.scrollBy({ left: scrollAmount, behavior: 'smooth' });
      this.updateScrollValues();
    }
  }

  scrollRight() {
    if (isPlatformBrowser(this.platformId)) {
      const itemWidth = this.placeItems.first.nativeElement.offsetWidth;
      const scrollAmount = this.dir === 'rtl' ? -itemWidth : itemWidth;
      this.slider.nativeElement.scrollBy({ left: scrollAmount, behavior: 'smooth' });
      this.updateScrollValues();
    }
  }

  @HostListener('window:resize')
  onResize(): void {
    if (isPlatformBrowser(this.platformId)) {
      this.clientWidth = this.list.nativeElement.clientWidth;
      this.scrollWidth = this.slider.nativeElement.scrollWidth;
      this.updateSwiperBtnsVisibility();
    }
  }

  updateSwiperBtnsVisibility(): void {
    if (isPlatformBrowser(this.platformId)) {
      if (this.scrollWidth > this.clientWidth) {
        this.previousBtn.nativeElement.style.display = 'flex';
        this.nextBtn.nativeElement.style.display = 'flex';
      } else {
        this.previousBtn.nativeElement.style.display = 'none';
        this.nextBtn.nativeElement.style.display = 'none';
      }
    }
  }


  updateScrollValues(): void {
    this.scrollLeftValue = this.slider.nativeElement.scrollLeft;
  }


  showDetails(item: any): void {
    if (item?.slug) {
      this.router.navigate(['/places/details/', item?.slug]);
    }
  }
  goToPlacesList() {
    this.router.navigate(['/places']);
  }
}
