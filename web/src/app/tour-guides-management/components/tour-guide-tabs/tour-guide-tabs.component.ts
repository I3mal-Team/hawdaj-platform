import { Component, inject, Input, PLATFORM_ID } from '@angular/core';
import { CommonModule, isPlatformBrowser } from '@angular/common';
import { TabViewModule } from 'primeng/tabview';
import { TranslateModule } from '@ngx-translate/core';
import { environment } from 'src/environments/environment';
import { PublicService } from 'src/app/modules/shared/services/public.service';
import { ReviewComponent } from "../../../Common/layout/review/review.component";
import { SkeletonComponent } from "../../../modules/shared/components/skeleton/skeleton.component";
import { TabDescriptionComponent } from './tab-description/tab-description.component';
import { LanguageCardComponent } from "./language-card/language-card.component";
import { RegionCardComponent } from "./region-card/region-card.component";
import { ChatComponent } from "../../../Common/layout/tabs2/chat/chat.component";

@Component({
  selector: 'app-tour-guide-tabs',
  standalone: true,
  imports: [CommonModule, TabViewModule, TranslateModule, ReviewComponent, SkeletonComponent, TabDescriptionComponent, LanguageCardComponent, RegionCardComponent, ChatComponent],
  templateUrl: './tour-guide-tabs.component.html',
  styleUrls: ['./tour-guide-tabs.component.scss']
})
export class TourGuideTabsComponent {
  @Input() tourGuideDetails: any;
  @Input() tabsConfig: any;
  @Input() type: string;

  @Input() isLoadingReviews: boolean;
  //rating
  ratingsPerPage = 3;
  currentRatingPage = 0;

  //regions
  currentRegionsPage = 0;
  RegionsPerPage = 3;


  private platformId = inject(PLATFORM_ID);
  public publicService = inject(PublicService)


  getDisplayedRatings() {
    const startIndex = this.currentRatingPage * this.ratingsPerPage;
    const endIndex = startIndex + this.ratingsPerPage;
    return this.tourGuideDetails.ratings.slice(startIndex, endIndex);
  }
  ngOnInit() {
    if (isPlatformBrowser(this.platformId)) {
      this.setPerPage(window.innerWidth);
    }
  }

  prevRatingPage() {
    if (this.currentRatingPage > 0) {
      this.currentRatingPage--;
      if (isPlatformBrowser(this.platformId)) {
        if (this.ratingsPerPage === 3) {
          window.scrollTo({ top: 400, behavior: 'smooth' });
        } else {
          window.scrollTo({ top: 900, behavior: 'smooth' });
        }
      }
    }
  }

  nextRatingPage() {
    const totalPages = Math.ceil(this.tourGuideDetails.ratings.length / this.ratingsPerPage);
    if (this.currentRatingPage < totalPages - 1) {
      this.currentRatingPage++;
      if (isPlatformBrowser(this.platformId)) {
        if (this.ratingsPerPage === 3) {
          window.scrollTo({ top: 400, behavior: 'smooth' });
        } else {
          window.scrollTo({ top: 900, behavior: 'smooth' });
        }
      }
    }
  }
  getDisplayedRegions() {
    const start = this.currentRegionsPage * this.RegionsPerPage;
    const end = start + this.RegionsPerPage;
    return this.tourGuideDetails?.regions?.slice(start, end);
  }
  prevRegionsPage() {
    if (this.currentRegionsPage > 0) {
      this.currentRegionsPage--;
      if (isPlatformBrowser(this.platformId)) {
        if (this.RegionsPerPage === 3) {
          window.scrollTo({ top: 200, behavior: 'smooth' });
        } else {
          window.scrollTo({ top: 700, behavior: 'smooth' });
        }
      }
    }
  }

  nextRegionsPage() {
    const totalPages = Math.ceil(this.tourGuideDetails.regions.length / this.RegionsPerPage);
    if (this.currentRegionsPage < totalPages - 1) {
      this.currentRegionsPage++;
      if (isPlatformBrowser(this.platformId)) {
        if (this.RegionsPerPage === 3) {
          window.scrollTo({ top: 200, behavior: 'smooth' });
        } else {
          window.scrollTo({ top: 700, behavior: 'smooth' });
        }
      }
    }
  }


  setPerPage(windowWidth: number) {
    this.ratingsPerPage = windowWidth < 767 ? 1 : 3;
    this.RegionsPerPage = windowWidth < 767 ? 1 : 3;
  }
}
