import { Component, Inject, Input, PLATFORM_ID } from '@angular/core';
import { CommonModule, isPlatformBrowser, NgOptimizedImage } from '@angular/common';
import { environment } from 'src/environments/environment';
import { PublicService } from 'src/app/modules/shared/services/public.service';
import { RouterModule } from '@angular/router';
import { CarouselModule } from 'primeng/carousel';
import { TranslateModule } from '@ngx-translate/core';
import { RatingModule } from 'primeng/rating';
import { FormsModule } from '@angular/forms';

@Component({
  selector: 'app-stores-v2',
  standalone: true,
  imports: [CommonModule, RouterModule, NgOptimizedImage, CarouselModule, TranslateModule, RatingModule, FormsModule],
  templateUrl: './stores-v2.component.html',
  styleUrls: ['./stores-v2.component.scss']
})
export class StoresV2Component {
  @Input() items: any;
  responsiveOptions: any;

  constructor(private _publicService: PublicService,
    @Inject(PLATFORM_ID) private platformId: Object) { }
  ngOnInit() {
    if (isPlatformBrowser(this.platformId)) {
      this.items = this._publicService.slicedData(this.items, 6)
    }
    this.responsiveOptions = [
      {
        breakpoint: '1240px',
        numVisible: 1,
        numScroll: 1
      },
      {
        breakpoint: '991px',
        numVisible: 2,
        numScroll: 2,
      },
      {
        breakpoint: '767px',
        numVisible: 1,
        numScroll: 1
      }
    ];
  }
}
