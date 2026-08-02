import { Component, Inject, Input, PLATFORM_ID } from '@angular/core';
import { CommonModule, isPlatformBrowser, NgOptimizedImage } from '@angular/common';
import { environment } from 'src/environments/environment';
import { PublicService } from 'src/app/modules/shared/services/public.service';
import { RouterModule } from '@angular/router';
import { TranslateModule } from '@ngx-translate/core';
import { CarouselModule } from 'primeng/carousel';
import { StripHtmlPipe } from 'src/app/Common/pipes/strip-html.pipe';

@Component({
  selector: 'app-home-restaurants-v2',
  standalone: true,
  imports: [CommonModule, TranslateModule, RouterModule, NgOptimizedImage, CarouselModule, StripHtmlPipe],
  templateUrl: './home-restaurants-v2.component.html',
  styleUrls: ['./home-restaurants-v2.component.scss']
})
export class HomeRestaurantsV2Component {
  @Input() items: any[] = [];
  @Input() autoPlay: boolean = false;
  activeIndex: number = 2;
  rating: number;

  responsiveOptions: any;

  constructor(
    public _PublicService: PublicService,
    @Inject(PLATFORM_ID) private platformId: Object
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
    this.activeIndex = index;
    this.rating = this.items[this.activeIndex].rate || 0;
  }
}
