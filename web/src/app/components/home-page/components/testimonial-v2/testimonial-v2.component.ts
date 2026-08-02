import { Component, Inject, Input, PLATFORM_ID } from '@angular/core';
import { CommonModule, isPlatformBrowser, NgOptimizedImage } from '@angular/common';
import { PublicService } from 'src/app/modules/shared/services/public.service';
import { RouterModule } from '@angular/router';
import { CarouselModule } from 'primeng/carousel';
import { LazyLoadImageDirective } from 'src/app/modules/shared/directives/lazy-load-image.directive';

@Component({
  selector: 'app-testimonial-v2',
  standalone: true,
  imports: [CommonModule, NgOptimizedImage, RouterModule, CarouselModule,LazyLoadImageDirective],
  templateUrl: './testimonial-v2.component.html',
  styleUrls: ['./testimonial-v2.component.scss']
})
export class TestimonialV2Component {
  @Input() testimonialData: any = [];
  testimonialDataCarousel: any;

  responsiveOptions: any;
  constructor(
    public _PublicService: PublicService,
    @Inject(PLATFORM_ID) private platformId: Object
  ) { }
  ngOnInit() {
    if (isPlatformBrowser(this.platformId)) {
      this.testimonialDataCarousel = [
        { image: 'assets/images-v2/pages/Home/Testimonial-section/haw2-web.webp', value: this.testimonialData[0]?.value },
        { image: 'assets/images-v2/pages/Home/Testimonial-section/haw1-web.webp', value: this.testimonialData[0]?.value }
      ];
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
  }

}
