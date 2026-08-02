import { Component, inject, Input, PLATFORM_ID } from '@angular/core';
import { CommonModule, isPlatformBrowser, NgOptimizedImage } from '@angular/common';
import { PublicService } from 'src/app/modules/shared/services/public.service';
import { keys } from 'src/app/modules/shared/configs/localstorage-key';
import { Subscription } from 'rxjs';
import { LazyLoadImageDirective } from 'src/app/modules/shared/directives/lazy-load-image.directive';
import { IUserReview } from '../interface/user-review.model';
import { ICurrentLoginInformation } from '../interface/current-login-information.model';

@Component({
  selector: 'app-reviews-slider-card',
  standalone: true,
  imports: [CommonModule, LazyLoadImageDirective, NgOptimizedImage],
  templateUrl: './reviews-slider-card.component.html',
  styleUrls: ['./reviews-slider-card.component.scss']
})
export class ReviewsSliderCardComponent {
  currentLanguage!: string;
  currentLoginInformation: ICurrentLoginInformation;
  @Input() review: IUserReview;

  private publicService = inject(PublicService);
  private platformId = inject(PLATFORM_ID);
  private profileSubscription!: Subscription;

  // Start ngOnInit
  ngOnInit(): void {
    this.currentLanguage = this.publicService.getCurrentLanguage();

    if (isPlatformBrowser(this.platformId)) {
      this.getProfileData();
    }

    this.profileSubscription = this.publicService?.recallProfileDataLocalStorage?.subscribe((res: any) => {
      if (res === true && isPlatformBrowser(this.platformId)) {
        this.getProfileData();
      }
    });
  }
  // End ngOnInit

  // Start getProfileData Function
  private getProfileData(): void {
    const storedData = window?.localStorage?.getItem(keys?.profileData);
    this.currentLoginInformation = storedData ? JSON.parse(storedData) : {};
  }
  // End getProfileData Function

  // Start getImage Function
  getImage(): string {
    const userPhoto = this.currentLoginInformation?.PersonalData?.id == this.review?.user_id ? this.currentLoginInformation?.PersonalData?.photo : this.review?.image;
    return userPhoto || 'assets/images-v2/pages/place-details/arab-man.jpg';
  }
  // End getImage Function


  // Start ngOnDestroy Function
  ngOnDestroy(): void {
    this.profileSubscription?.unsubscribe();
  }
  // End ngOnDestroy Function
}
