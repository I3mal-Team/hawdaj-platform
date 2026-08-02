import { Component, Inject, Input, PLATFORM_ID } from '@angular/core';
import { CommonModule, isPlatformBrowser, NgOptimizedImage } from '@angular/common';
import { environment } from 'src/environments/environment';
import { PublicService } from 'src/app/modules/shared/services/public.service';
import { Router, RouterModule } from '@angular/router';
import { TranslateModule } from '@ngx-translate/core';
import { CarouselModule } from 'primeng/carousel';
import { LazyLoadImageDirective } from 'src/app/modules/shared/directives/lazy-load-image.directive';
import { LazyLoadSectionDirective } from 'src/app/shared/directives/lazyLoad-section.directive';
import { StripHtmlPipe } from 'src/app/Common/pipes/strip-html.pipe';

@Component({
  selector: 'app-slider',
  standalone: true,
  imports: [CommonModule, TranslateModule, RouterModule, NgOptimizedImage, CarouselModule, LazyLoadImageDirective, LazyLoadSectionDirective, StripHtmlPipe],
  templateUrl: './slider.component.html',
  styleUrls: ['./slider.component.scss']
})
export class SliderComponent {
  @Input() items: any[] = [];
  @Input() autoPlay: boolean = false;
  @Input() isSearch: boolean = false;
  activeIndex: number = 2;
  rating: number;

  responsiveOptions: any;

  constructor(
    public _PublicService: PublicService,
    @Inject(PLATFORM_ID) private platformId: Object,
    private router: Router
  ) { }

  ngOnInit() {
    if (isPlatformBrowser(this.platformId)) {
      this.rating = this.items[this.activeIndex]?.rate;
    }
    this.responsiveOptions = [
      {
        breakpoint: '1240px',
        numVisible: 1,
        numScroll: 1
      },
      {
        breakpoint: '991px',
        numVisible: 1,
        numScroll: 1,
      },
      {
        breakpoint: '767px',
        numVisible: 1,
        numScroll: 1
      }
    ];
    if (this.autoPlay) {
      setInterval(() => {
        this.activeIndex = this.activeIndex + 1;
        if (this.activeIndex == 5) {
          this.activeIndex = 0;
        }
      }, 4000);
    }
  }

  displayContent(index: number) {
    if (!this.isSearch) {
      this.activeIndex = index;
      this.rating = this.items[this.activeIndex].rate || 0;
    }
  }

  goToDetails(slug: string) {
    this.router.navigate([`/stories/${slug}`]);
  }
}
