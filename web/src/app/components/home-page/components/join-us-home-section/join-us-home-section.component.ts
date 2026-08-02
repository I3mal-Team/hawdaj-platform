import { DynamicSvgComponent } from 'src/app/modules/shared/components/icons/dynamic-svg/dynamic-svg.component';
import { CommonModule, isPlatformBrowser, NgOptimizedImage } from '@angular/common';
import { keys } from 'src/app/modules/shared/configs/localstorage-key';
import { Component, Inject, PLATFORM_ID } from '@angular/core';
import { AuthService } from 'src/app/services/auth.service';
import { TranslateModule } from '@ngx-translate/core';
import { Router } from '@angular/router';

@Component({
  selector: 'app-join-us-home-section',
  standalone: true,
  imports: [CommonModule, TranslateModule, NgOptimizedImage, DynamicSvgComponent],
  templateUrl: './join-us-home-section.component.html',
  styleUrls: ['./join-us-home-section.component.scss']
})
export class JoinUsHomeSectionComponent {

  isLoggedIn: boolean = false;

  constructor(
    @Inject(PLATFORM_ID) private platformId: Object,
    private authService: AuthService,
    private router: Router
  ) {
    this.isLoggedIn = this.authService.isLoggedIn();
  }

  joinUs(): void {
    if (isPlatformBrowser(this.platformId)) {
      if (localStorage.getItem(keys?.userLoginData)) {
        this.router.navigate(['/Profile']);
      } else {
        this.authService.promptJoinUsDialog();
      }
    }
  }

}
