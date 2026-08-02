import { Component, inject, Input, PLATFORM_ID } from '@angular/core';
import { CommonModule, isPlatformBrowser } from '@angular/common';
import { PublicService } from 'src/app/modules/shared/services/public.service';
import { keys } from 'src/app/modules/shared/configs/localstorage-key';

@Component({
  selector: 'app-review',
  standalone: true,
  imports: [CommonModule],
  templateUrl: './review.component.html',
  styleUrls: ['./review.component.scss']
})
export class ReviewComponent {
  currentLanguage: string;
  currentLoginInformation: any;
  @Input() review: any;
  private publicService = inject(PublicService);
  private platformId = inject(PLATFORM_ID);

  constructor() {
    this.currentLanguage = this.publicService.getCurrentLanguage();
  }

  ngOnInit(): void {
    if (isPlatformBrowser(this.platformId)) {
      if (JSON.parse(window?.localStorage?.getItem(keys?.profileData) || '{}')) {
        this.currentLoginInformation = JSON.parse(window?.localStorage?.getItem(keys?.profileData) || '{}');
      }
    }
    this.publicService?.recallProfileDataLocalStorage?.subscribe((res: any) => {
      if (res === true && isPlatformBrowser(this.platformId)) {
        if (isPlatformBrowser(this.platformId)) {
          if (JSON.parse(window?.localStorage?.getItem(keys?.profileData) || '{}')) {
            this.currentLoginInformation = JSON.parse(window?.localStorage?.getItem(keys?.profileData) || '{}');
          }
        }
      }
    });
  }
}
