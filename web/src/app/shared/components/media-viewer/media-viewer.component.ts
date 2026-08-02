import { CommonModule, isPlatformBrowser, NgOptimizedImage } from '@angular/common';
import { Component, ElementRef, HostListener, Inject, Input, OnChanges, OnInit, PLATFORM_ID, SimpleChanges, ViewChild } from '@angular/core';
import { environment } from 'src/environments/environment';
import { LazyLoadImageDirective } from 'src/app/modules/shared/directives/lazy-load-image.directive';
import { PdfViewComponent } from './Attachments/pdf-view/pdf-view.component';
import { TranslateModule } from '@ngx-translate/core';
import { IContent } from './interface/media';
import { VideoViewComponent } from './Attachments/video-view/video-view.component';
import { DomSanitizer, SafeResourceUrl } from '@angular/platform-browser';
import { sanitizeUrl } from 'src/app/Common/functions/sanitizer.util';
import { IVideoPlayerConfig } from './Attachments/video-view/configs/video-player.config';
import { FileType } from 'src/app/Common/enums/Attachments.enum';
import { ExcelViewComponent } from "./Attachments/excel-view/excel-view.component";
import { WordViewComponent } from "./Attachments/word-view/word-view.component";

@Component({
  selector: 'app-media-viewer',
  standalone: true,
  imports: [CommonModule, LazyLoadImageDirective, NgOptimizedImage, VideoViewComponent, PdfViewComponent, TranslateModule, ExcelViewComponent, WordViewComponent],
  templateUrl: './media-viewer.component.html',
  styleUrls: ['./media-viewer.component.scss']
})
export class MediaViewerComponent implements OnInit, OnChanges {
  @Input() lang: string;
  @Input() pageContent: IContent;

  showButtons = true;
  fileType: string = '';
  videoConfig: IVideoPlayerConfig;
  @ViewChild('mediaSlider') mediaSlider: ElementRef;
  @ViewChild('media') media!: ElementRef;
  @ViewChild('mediaView') mediaView!: ElementRef;
  @ViewChild('mediaViewContainer') mediaViewContainer!: ElementRef;
  @ViewChild('sliderContainer') sliderContainer: ElementRef;

  currentIndex: number = 0;
  private isLocked: boolean = false;
  safeMediaUrl: SafeResourceUrl;
  FileType = FileType;

  constructor(@Inject(PLATFORM_ID) private platformId: Object, private sanitizer: DomSanitizer) { }

  ngOnChanges(changes: SimpleChanges): void {
    if (!changes['pageContent'] || !this.pageContent) {
      return;
    }
    if (!changes['pageContent'].firstChange) {
      this.currentIndex = 0;
    }
    this.normalizeGalleries();
  }

  ngOnInit() {
    this.videoConfig = {
      fullScreen: true,
      pictureInPicture: true,
      seekControls: true,
      speedControls: true,
      lockControl: true,
      seekControlsValues: {
        backward: 10,
        forward: 10
      },
      volumeControl: true,
      canDownload: true,
      progreesBar: true,
      mute: false,
      autoPlay: false
    }
    if (isPlatformBrowser(this.platformId)) {
      document.addEventListener("keydown", (event) => {
        if (event.key === "ArrowLeft" || event.key === "ArrowRight") {
          event.preventDefault();
        }
      });
    }


    this.normalizeGalleries();
  }

  private normalizeGalleries(): void {
    if (!this.pageContent) return;

    const defaultItem = {
      id: 0,
      file: this.pageContent.image || 'assets/newImages/not-found/no-img.svg',
      type: 'stores',
      mime_type: FileType.IMAGE
    };

    const galleries = Array.isArray(this.pageContent.galleries)
      ? this.pageContent.galleries
      : [];

    // Avoid duplicate default image
    const hasDefault = galleries.some(item => item?.id === 0);

    this.pageContent = {
      ...this.pageContent,
      galleries: hasDefault
        ? galleries
        : [defaultItem, ...galleries]
    };

  }

  changeMedia(i) {
    if (isPlatformBrowser(this.platformId)) {
      let mediaSlider = this.mediaView.nativeElement;
      let mediaViewWidth = this.mediaViewContainer.nativeElement.clientWidth;

      let container = this.mediaSlider.nativeElement;
      let mediaWidth = this.media.nativeElement.clientWidth;
      let reverseDirection = this.lang === 'ar';

      let directionMultiplier = reverseDirection ? -1 : 1;

      if (i > this.currentIndex) {
        mediaSlider.scrollLeft += directionMultiplier * (i - this.currentIndex) * mediaViewWidth;
        container.scrollLeft += directionMultiplier * (i - this.currentIndex) * mediaWidth;
      } else {
        mediaSlider.scrollLeft -= directionMultiplier * (this.currentIndex - i) * mediaViewWidth;
        container.scrollLeft -= directionMultiplier * (this.currentIndex - i) * mediaWidth;
      }
      this.currentIndex = i;
    }
  }

  sanitizeUrl(url: string): SafeResourceUrl {
    return sanitizeUrl(url, this.sanitizer);
  }

  backOrNext(direction: string) {
    if (this.isLocked) return;
    this.isLocked = true;
    if (isPlatformBrowser(this.platformId)) {
      let container = this.mediaSlider.nativeElement;
      let mediaWidth = this.media.nativeElement.clientWidth;
      let mediaSlider = this.mediaView.nativeElement;
      let mediaViewWidth = this.mediaViewContainer.nativeElement.clientWidth;

      let reverseDirection = this.lang === 'ar';
      let directionMultiplier = reverseDirection ? -1 : 1;

      if (direction === 'back') {
        if (this.currentIndex > 0) {
          this.currentIndex--;
          container.scrollLeft += directionMultiplier * -mediaWidth;
          mediaSlider.scrollLeft += directionMultiplier * -mediaViewWidth;
        }
      } else if (direction === 'next') {
        if (this.currentIndex < this.pageContent.galleries.length) {
          this.currentIndex++;
          container.scrollLeft += directionMultiplier * mediaWidth;
          mediaSlider.scrollLeft += directionMultiplier * mediaViewWidth;
        }
      }
      setTimeout(() => {
        this.isLocked = false;
      }, 500);
    }
  }

  handleImageError(event: Event) {
    const mediaElement = event.target as HTMLImageElement;
    mediaElement.src = 'assets/images-v2/pages/no-result/no-result.png';
  }
}
