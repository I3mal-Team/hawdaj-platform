import { Directive, ElementRef, Inject, PLATFORM_ID } from '@angular/core';
import { isPlatformBrowser } from '@angular/common';

@Directive({
  selector: 'img[lazyLoad]'  // Updated selector to avoid targeting all <img> tags
})
export class LazyImgDirective {
  constructor(
    private el: ElementRef<HTMLImageElement>,
    @Inject(PLATFORM_ID) private platformId: Object
  ) {
    if (isPlatformBrowser(this.platformId)) {
      this.lazyLoadImage();
    }
  }

  private lazyLoadImage() {
    const supports: any = 'loading' in HTMLImageElement?.prototype;

    if (supports) {
      this.el?.nativeElement?.setAttribute('loading', 'lazy');
    } else {
      // fallback to IntersectionObserver
      this.setupIntersectionObserver();
    }
  }

  private setupIntersectionObserver() {
    // IntersectionObserver setup logic here
  }
}
