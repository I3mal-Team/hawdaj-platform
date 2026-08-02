import { Component, inject, Input, PLATFORM_ID } from '@angular/core';
import { CommonModule, isPlatformBrowser } from '@angular/common';
import { TranslateModule } from '@ngx-translate/core';
import { ReviewsSliderCardComponent } from "../reviews-slider-card/reviews-slider-card.component";

@Component({
  selector: 'app-reviews-slider-list',
  standalone: true,
  imports: [CommonModule, TranslateModule, ReviewsSliderCardComponent],
  templateUrl: './reviews-slider-list.component.html',
  styleUrls: ['./reviews-slider-list.component.scss']
})
export class ReviewsSliderListComponent {
  private platformId = inject(PLATFORM_ID);

  @Input() entityDetails: any;
  @Input() ratingsPerPage: number = 3;

  startIndex = 0;

  ngOnInit() {
    if (isPlatformBrowser(this.platformId)) {
      this.setRatingsPerPage(window.innerWidth);
    }
  }

  getDisplayedRatings() {
    return this.entityDetails?.ratings?.slice(this.startIndex, this.startIndex + this.ratingsPerPage) || [];
  }

  changeRatingPage(direction: 'prev' | 'next') {
    const totalRatings = this.entityDetails?.ratings?.length || 0;

    if (direction === 'next' && this.startIndex + this.ratingsPerPage < totalRatings) {
      this.startIndex++;
    } else if (direction === 'prev' && this.startIndex > 0) {
      this.startIndex--;
    }
    this.smoothScroll();
  }
  private smoothScroll() {
    if (isPlatformBrowser(this.platformId)) {
      const scrollTop = this.ratingsPerPage === 3 ? 450 : 900;
      window.scrollTo({ top: scrollTop, behavior: 'smooth' });
    }
  }
  setRatingsPerPage(windowWidth: number) {
    this.ratingsPerPage = windowWidth < 767 ? 1 : 3;
  }
}
