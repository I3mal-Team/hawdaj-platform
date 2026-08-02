import { Component, Input } from '@angular/core';
import { CommonModule, NgOptimizedImage } from '@angular/common';
import { environment } from 'src/environments/environment';
import { PublicService } from 'src/app/modules/shared/services/public.service';
import { TranslateModule } from '@ngx-translate/core';
import { StripHtmlPipe } from 'src/app/Common/pipes/strip-html.pipe';

@Component({
  selector: 'app-home-page-applications-mobile-v2',
  standalone: true,
  imports: [CommonModule, TranslateModule, NgOptimizedImage, StripHtmlPipe],
  templateUrl: './home-page-applications-mobile-v2.component.html',
  styleUrls: ['./home-page-applications-mobile-v2.component.scss']
})
export class HomePageApplicationsMobileV2Component {
  @Input() items: any;
  activeIndex: number = 3;
  constructor(
    private _publicService: PublicService,
  ) { }
  ngOnInit() {
    this.items = this._publicService.slicedData(this.items, 7);
    setInterval(() => {
      this.activeIndex = this.activeIndex + 1;
      if (this.activeIndex == 7) {
        this.activeIndex = 0;
      }
    }, 4000);
  }

  setActiveIndex(index: number) {
    this.activeIndex = index;
  }
}
