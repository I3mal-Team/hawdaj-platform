import { TourGuideCardComponent } from '../../../tour-guides/tour-guide-card/tour-guide-card.component';
import { PublicService } from '../../../../modules/shared/services/public.service';
import { CommonModule, isPlatformBrowser, NgOptimizedImage } from '@angular/common';
import { keys } from 'src/app/modules/shared/configs/localstorage-key';
import { Component, Inject, Input, PLATFORM_ID } from '@angular/core';
import { AuthService } from 'src/app/services/auth.service';
import { Router, RouterModule } from '@angular/router';
import { TranslateModule } from '@ngx-translate/core';
import { CarouselModule } from 'primeng/carousel';

@Component({
  selector: 'app-tour-guide-home',
  standalone: true,
  imports: [CommonModule, RouterModule, TranslateModule, CarouselModule, NgOptimizedImage, TourGuideCardComponent],
  templateUrl: './tour-guide-home.component.html',
  styleUrls: ['./tour-guide-home.component.scss']
})
export class TourGuideHomeComponent {
  @Input() items: any[] = [];  // This should be populated with the actual interface structure.
  isLoggedIn: boolean = false;
  responsiveOptions: any;
  currentLanguage: string = '';

  constructor(
    @Inject(PLATFORM_ID) private platformId: Object,
    public _PublicService: PublicService,
    private authService: AuthService,
    private router: Router
  ) {
    this.isLoggedIn = this.authService.isLoggedIn();
    this.responsiveOptions = [
      {
        breakpoint: '1240px',
        numVisible: 1,
        numScroll: 1
      },
      {
        breakpoint: '1024px',
        numVisible: 2,
        numScroll: 1
      },
      {
        breakpoint: '767px',
        numVisible: 1,
        numScroll: 1
      }
    ];
  }
  ngOnInit(): void {
    if (isPlatformBrowser(this.platformId)) {
      this.isLoggedIn = this.authService.isLoggedIn();
    }
    this.currentLanguage = this._PublicService.getCurrentLanguage();
  }
  joinUs(): void {
    if (isPlatformBrowser(this.platformId)) {
      if (localStorage.getItem(keys?.userLoginData)) {
        this.router.navigate(['/Profile/tour-guide-info']);
      } else {
        this.authService.promptJoinUsDialog();
      }
    }
  }
}
