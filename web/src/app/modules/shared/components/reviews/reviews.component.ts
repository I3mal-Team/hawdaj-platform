import { TranslationChildModule } from '../../../../services/translation-child.module';
import { DynamicDialogConfig } from 'primeng/dynamicdialog';
import { CarouselModule } from 'primeng/carousel';
import { Component, OnInit } from '@angular/core';
import { RatingModule } from 'primeng/rating';
import { FormsModule } from '@angular/forms';

@Component({
  standalone: true,
  imports: [CarouselModule, RatingModule, FormsModule, TranslationChildModule],
  selector: 'app-reviews',
  templateUrl: './reviews.component.html',
  styleUrls: ['./reviews.component.scss']
})
export class ReviewsComponent implements OnInit {
  reviews: any = [];
  responsiveOptions = [
    {
      breakpoint: '1024px',
      numVisible: 2,
      numScroll: 1
    },
    {
      breakpoint: '768px',
      numVisible: 2,
      numScroll: 1
    },
    {
      breakpoint: '560px',
      numVisible: 2,
      numScroll: 1
    },
    {
      breakpoint: '470px',
      numVisible: 1,
      numScroll: 1
    }
  ];
  constructor(
    private config: DynamicDialogConfig
  ) { }

  ngOnInit(): void {
    this.reviews = this.config?.data;
  }
}
