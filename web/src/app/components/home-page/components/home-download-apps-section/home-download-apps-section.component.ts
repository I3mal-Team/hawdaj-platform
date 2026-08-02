import { HOME_DOWNLOAD_APPS_CONFIG } from '../../../../core/configs/home-download-apps-section.config';
import { LazyLoadImageDirective } from 'src/app/modules/shared/directives/lazy-load-image.directive';
import { CommonModule, isPlatformBrowser, NgOptimizedImage } from '@angular/common';
import { Component, inject, PLATFORM_ID, signal, OnInit } from '@angular/core';
import { PublicService } from 'src/app/modules/shared/services/public.service';

@Component({
  selector: 'app-home-download-apps-section',
  standalone: true,
  imports: [
    CommonModule,
    NgOptimizedImage,
    LazyLoadImageDirective
  ],
  templateUrl: './home-download-apps-section.component.html',
  styleUrls: ['./home-download-apps-section.component.scss']
})
export class HomeDownloadAppsSectionComponent implements OnInit {
  protected readonly config = HOME_DOWNLOAD_APPS_CONFIG;
  protected readonly currentLanguage = signal<'ar' | 'en' | 'zh' | 'ru'>('ar');

  protected readonly imageBaseUrl =
    'assets/newImages/homepage/home-download-apps';

  private readonly platformId = inject(PLATFORM_ID);
  private readonly publicService = inject(PublicService);

  ngOnInit(): void {
    if (!isPlatformBrowser(this.platformId)) return;
    this.currentLanguage.set(
      this.publicService.getCurrentLanguage() as 'ar' | 'en' | 'zh' | 'ru'
    );
  }
}
