import { CommonModule, isPlatformBrowser } from '@angular/common';
import { PublicService } from '../../services/public.service';
import { Component, Inject, PLATFORM_ID } from '@angular/core';


@Component({
  standalone:true,
  imports: [
    CommonModule
  ],
  selector: 'app-overlay-loading',
  templateUrl: './overlay-loading.component.html',
  styleUrls: ['./overlay-loading.component.scss']
})
export class OverlayLoadingComponent {
  show_overlay: boolean = false;
  show_loader: boolean = false;
  constructor(private publicService: PublicService,
    @Inject(PLATFORM_ID) private platformId: Object // Inject PLATFORM_ID to check the platform
  ) { }

  ngOnInit(): void {
    if (isPlatformBrowser(this.platformId)) {
    this.publicService.show_loader.subscribe((res: any) => {
      if (res == true) {
        this.show_overlay = true;
        this.show_loader = true;
      } else {
        this.show_overlay = false;
        this.show_loader = false;
      }
    });
  }
  }
}

