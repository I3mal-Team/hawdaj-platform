import { Component, inject, Inject, Input, PLATFORM_ID } from '@angular/core';
import { CommonModule, isPlatformBrowser, NgOptimizedImage } from '@angular/common';
import { PublicService } from 'src/app/modules/shared/services/public.service';
import { TranslateModule } from '@ngx-translate/core';
import { RouterModule } from '@angular/router';
import { environment } from 'src/environments/environment';
import { CarouselModule } from 'primeng/carousel';
import { Router } from '@angular/router';
import { StripHtmlPipe } from 'src/app/Common/pipes/strip-html.pipe';

@Component({
  selector: 'app-events-home-section-v2',
  standalone: true,
  imports: [CommonModule, TranslateModule, RouterModule, NgOptimizedImage, CarouselModule, StripHtmlPipe],
  templateUrl: './events-home-section-v2.component.html',
  styleUrls: ['./events-home-section-v2.component.scss']
})
export class EventsHomeSectionV2Component {
  @Input() items: any[] = [];  // This should be populated with the actual interface structure.
  @Input() autoPlay: boolean = false;
  public _PublicService = inject(PublicService)
  private router = inject(Router)
  private platformId = inject(PLATFORM_ID)

  activeIndex: number = 2;
  count: number;
  responsiveOptions: any;
  rating: number;

  currentDate: string = '';
  soon: boolean = false;

  constructor(
  ) { }

  ngOnInit() {
    this.slicedItems()
    if (isPlatformBrowser(this.platformId)) {
      this.rating = this.items[this.activeIndex].rate || 0;
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
    if (isPlatformBrowser(this.platformId)) {
      this.rating = this.items[this.activeIndex].rate || 0;
    }
  }
  showDetails(item: any): void {
    if (isPlatformBrowser(this.platformId) && item?.slug) {
      this.router.navigate(['/events/event-details/' + item?.slug])
    }
  }
  checkEventStatus(dateFrom: string, dateTo: string): string {
    const today = new Date();
    const eventStartDate = new Date(dateFrom);
    const eventEndDate = new Date(dateTo);

    if (today < eventStartDate) {
      return 'soon';
    } else if (today >= eventStartDate && today <= eventEndDate) {
      return 'open';
    } else {
      return 'closed';
    }
  }
  slicedItems() {
    this.count = window.innerWidth >= 1550 ? 4 : 5;
  }

}
