import { isPlatformBrowser, CommonModule, NgOptimizedImage } from '@angular/common';
import { Component, Inject, OnInit, PLATFORM_ID } from '@angular/core';
import { DomSanitizer, SafeResourceUrl } from '@angular/platform-browser';
import { DynamicDialogConfig, DynamicDialogRef } from 'primeng/dynamicdialog';
import { PublicService } from 'src/app/modules/shared/services/public.service';

@Component({
  selector: 'app-video-modal',
  standalone: true,
  imports: [
    CommonModule,
    NgOptimizedImage
  ],
  templateUrl: './video-modal.component.html',
  styleUrls: ['./video-modal.component.scss']
})
export class VideoModalComponent implements OnInit {
  data: any;
  isPlay: boolean = false;
  url: SafeResourceUrl | null = null;
  trustedVideoUrl: SafeResourceUrl | null = null;
  isValidUrl: boolean = true;
  newTabUrl: string = '';

  constructor(
    private publicService: PublicService,
    private config: DynamicDialogConfig,
    private sanitizer: DomSanitizer,
    private ref: DynamicDialogRef,
    @Inject(PLATFORM_ID) private platformId: Object
  ) { }

  ngOnInit(): void {
    // Subscribe to the close modal observable
    this.publicService.closeModal.subscribe((res: boolean) => {
      if (res === true) {
        this.ref.close();
        this.publicService.closeModal.next(false);
      }
    });

    this.data = this.config?.data;
    this.subscribeToCloseModal();
    this.initializeVideoData();
  }

  private subscribeToCloseModal(): void {
    this.publicService.closeModal.subscribe((res: boolean) => {
      if (res) {
        this.closeModal();
      }
    });
  }

  private initializeVideoData(): void {
    const videoUrl = this.config?.data?.url_video;
    // const videoUrl = 'https://www.youtube.com/watch?v=4mVYjXy7Zso&t=3s&ab_channel=%D9%86%D8%A7%D8%AF%D9%8A%D8%A7%D9%84%D8%B5%D9%82%D9%88%D8%B1%D8%A7%D9%84%D8%B3%D8%B9%D9%88%D8%AF%D9%8A';
    this.newTabUrl = videoUrl;
    this.checkAndSanitizeVideoUrl(videoUrl);
  }

  private checkAndSanitizeVideoUrl(url: string): void {
    if (!url) return;

    const sanitizedUrl = this.transformToEmbedUrl(url);
    this.trustedVideoUrl = this.sanitizer.bypassSecurityTrustResourceUrl(sanitizedUrl);
    this.isValidUrl = this.validateYouTubeUrl(sanitizedUrl);
  }

  private transformToEmbedUrl(url: string): string {
    const watchPattern = /watch\?v=([a-zA-Z0-9_-]+)/;
    const match = url.match(watchPattern);
    return match ? `https://www.youtube.com/embed/${match[1]}` : url;
  }

  private validateYouTubeUrl(url: string): boolean {
    const youtubeRegex = /^(https?:\/\/)?(www\.youtube\.com|youtu\.be)\/.+$/;
    return youtubeRegex.test(url);
  }

  playVideo(): void {
    if (this.isValidUrl) {
      this.isPlay = true;
    } else {
      this.openInNewTab(this.newTabUrl);
    }
  }

  openInNewTab(url: string): void {
    if (isPlatformBrowser(this.platformId)) {
      window.open(url, '_blank');
    }
    this.closeModal();
  }

  closeModal(): void {
    this.ref?.close();
    this.publicService.closeModal.next(false);
  }

  close(): void {
    this.ref?.close();
  }
}
