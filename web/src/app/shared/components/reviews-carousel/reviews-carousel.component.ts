import { TranslateModule } from '@ngx-translate/core';
import { CarouselModule } from 'primeng/carousel';
import { Component, Input } from '@angular/core';
import { ReviewCardComponent } from './review-card/review-card.component';
import { PublicService } from 'src/app/modules/shared/services/public.service';

@Component({
  standalone: true,
  imports: [CarouselModule, ReviewCardComponent, TranslateModule],
  selector: 'app-reviews-carousel',
  templateUrl: './reviews-carousel.component.html',
  styleUrls: ['./reviews-carousel.component.scss']
})
export class ReviewsCarouselComponent {
  currentLanguage: string = '';

  @Input() items: any = [];
  reviewOptions: any = [
    {
      breakpoint: '1024px',
      numVisible: 3,
      numScroll: 1
    },
    {
      breakpoint: '768px',
      numVisible: 2,
      numScroll: 1
    },
    {
      breakpoint: '560px',
      numVisible: 1,
      numScroll: 1
    },
    {
      breakpoint: '460px',
      numVisible: 1,
      numScroll: 1
    }
  ];

  constructor(
    private publicService: PublicService
  ) { }

  ngOnInit(): void {
    this.currentLanguage = this.publicService.getCurrentLanguage();
  }
}
