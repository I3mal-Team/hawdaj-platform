import { Component, inject, Inject, Input, PLATFORM_ID } from '@angular/core';
import { CommonModule, isPlatformBrowser } from '@angular/common';
import { PublicService } from 'src/app/modules/shared/services/public.service';
import { RouterModule } from '@angular/router';
import { TranslateModule } from '@ngx-translate/core';
import { CarouselModule } from 'primeng/carousel';
import { StripHtmlPipe } from 'src/app/Common/pipes/strip-html.pipe';

@Component({
  selector: 'app-app-animated-image-slider-v5',
  standalone: true,
  imports: [CommonModule, RouterModule, TranslateModule, CarouselModule, StripHtmlPipe],
  templateUrl: './app-animated-image-slider-v5.component.html',
  styleUrls: ['./app-animated-image-slider-v5.component.scss']
})
export class AppAnimatedImageSliderV5Component {
  private _publicService = inject(PublicService)
  private platformId = inject(PLATFORM_ID);
  @Input() items: any;

  responsiveOptions: any;

  ngOnInit() {
    if (isPlatformBrowser(this.platformId)) {
      this.items = this._publicService.slicedData(this.items, 6)
      this.responsiveOptions = [
        {
          breakpoint: '1281px',
          numVisible: 2,
          numScroll: 2
        },
        {
          breakpoint: '1024px',
          numVisible: 2,
          numScroll: 1
        },
        {
          breakpoint: '767px',
          numVisible: 1,
          numScroll: 1
        }
      ];
    }
  }
}
