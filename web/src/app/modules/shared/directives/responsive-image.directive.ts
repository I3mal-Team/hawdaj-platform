import { Directive, ElementRef, HostListener, Input, PLATFORM_ID, Inject } from '@angular/core';
import { isPlatformBrowser } from '@angular/common';

@Directive({
  standalone: true,
  selector: '[appResponsiveImage]'
})
export class ResponsiveImageDirective {
  @Input() mobileWidth: string = '50'; // Default mobile width
  @Input() mobileHeight: string = '50'; // Default mobile height

  private originalWidth: string;
  private originalHeight: string;

  constructor(private el: ElementRef, @Inject(PLATFORM_ID) private platformId: object) {
    // Save original width and height
    this.originalWidth = this.el.nativeElement.getAttribute('width');
    this.originalHeight = this.el.nativeElement.getAttribute('height');
  }

  @HostListener('window:resize')
  onResize() {
    this.adjustImageSize();
  }

  ngOnInit() {
    if (isPlatformBrowser(this.platformId)) {
      this.adjustImageSize();
    }
  }

  private adjustImageSize() {
    if (window.innerWidth <= 767) {
      this.el.nativeElement.setAttribute('width', this.mobileWidth);
      this.el.nativeElement.setAttribute('height', this.mobileHeight);
    } else {
      this.el.nativeElement.setAttribute('width', this.originalWidth);
      this.el.nativeElement.setAttribute('height', this.originalHeight);
    }
  }
}
