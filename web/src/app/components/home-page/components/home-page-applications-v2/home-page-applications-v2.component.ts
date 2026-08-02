import { Component, Input, Inject, PLATFORM_ID, ViewChild, ElementRef } from '@angular/core';
import { CommonModule, NgOptimizedImage, isPlatformBrowser } from '@angular/common';
import { Renderer2 } from '@angular/core';
import { environment } from 'src/environments/environment';
import { PublicService } from 'src/app/modules/shared/services/public.service';
import { TranslateModule } from '@ngx-translate/core';
import { RouterModule } from '@angular/router';
import { CarouselModule } from 'primeng/carousel';
import { ApplicationCardHomeComponent } from "../apps-home-section/application-card-home/application-card-home.component";
import { HomePageApplicationsMobileV2Component } from "../home-page-applications-mobile-v2/home-page-applications-mobile-v2.component";
import { StripHtmlPipe } from 'src/app/Common/pipes/strip-html.pipe';

@Component({
  selector: 'app-home-page-applications-v2',
  standalone: true,
  imports: [
    CommonModule,
    TranslateModule,
    NgOptimizedImage,
    RouterModule,
    CarouselModule,
    ApplicationCardHomeComponent,
    HomePageApplicationsMobileV2Component,
    StripHtmlPipe
  ],
  templateUrl: './home-page-applications-v2.component.html',
  styleUrls: ['./home-page-applications-v2.component.scss']
})
export class HomePageApplicationsV2Component {
  @Input() items: any;
  @ViewChild('frame') frame!: ElementRef;
  activeIndex: number = 3;
  isBrowser: boolean;
  @Input() autoPlay: boolean = true;

  constructor(
    private _publicService: PublicService,
    private renderer: Renderer2,
    @Inject(PLATFORM_ID) private platformId: Object
  ) {
    this.isBrowser = isPlatformBrowser(this.platformId);
  }

  ngOnInit() {
    this.items = this._publicService.slicedData(this.items, 7);
    if (this.autoPlay) {
      setInterval(() => {
        this.activeIndex = this.activeIndex + 1;
        if (this.activeIndex == 7) {
          this.activeIndex = 0
        }
        this.moveFrame(this.activeIndex, this.frame.nativeElement)
      }, 4000);
    }
  }

  moveFrame(i: number, frame: any) {
    this.activeIndex = i;
    let translateValue = (i - 3) * 169;
    if (i > 3) {
      translateValue -= 40 * (i - 3);
      translateValue -= 17 * (i - 3);
    }
    if (i < 3) {
      translateValue += 40 * (3 - i);
      translateValue += 17 * (3 - i);
    }

    const direction = document.dir === 'rtl' ? -1 : 1;
    translateValue *= direction;
    if (this.activeIndex == 0) {
      translateValue -= direction * 30;
    } else if (this.activeIndex == this.items.length - 1) {
      translateValue += direction * 30;
    }

    if (this.isBrowser) {
      this.renderer.setStyle(frame, 'transform', `translateX(${translateValue}px)`);
    } else {
      this.renderer.setStyle(frame, 'transform', 'none');
    }
  }
}
