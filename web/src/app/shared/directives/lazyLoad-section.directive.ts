import { isPlatformBrowser } from '@angular/common';
import { Directive, ElementRef, EventEmitter, HostListener, Input, Output, AfterViewInit, PLATFORM_ID, Inject } from '@angular/core';

@Directive({
  selector: '[appLazyLoadSection]',
  standalone: true
})
export class LazyLoadSectionDirective implements AfterViewInit {
  @Input() threshold: number = 0.1; // Default threshold for triggering
  @Output() sectionInView = new EventEmitter<boolean>(); // Event emitter to notify when the section is in view

  private hasIntersected: boolean = false;

  constructor(private el: ElementRef,
    @Inject(PLATFORM_ID) private platformId: Object
  ) { }

  ngAfterViewInit() {
    if (isPlatformBrowser(this.platformId)) {
      setTimeout(() => {
        if (this.isInViewport()) {
          this.sectionInView.emit(true);
        }
      }, 100); // Adjust timeout as necessary
    }
  }

  @HostListener('window:scroll', [])
  onWindowScroll(): void {
    if (isPlatformBrowser(this.platformId)) {
      this.checkVisibility();
    }
  }

  @HostListener('window:resize', [])
  onResize(): void {
    this.checkVisibility();
  }

  private checkVisibility(): void {
    if (this.isInViewport() && !this.hasIntersected) {
      this.hasIntersected = true; // Ensure it only triggers once
      this.sectionInView.emit(true);
    }
  }

  private isInViewport(): boolean {
    if (isPlatformBrowser(this.platformId)) {
      const rect = this.el.nativeElement.getBoundingClientRect();
      const windowHeight = window.innerHeight || document.documentElement.clientHeight;
      const windowWidth = window.innerWidth || document.documentElement.clientWidth;
      // Check if the element is in the viewport with the given threshold
      return (
        rect.top <= windowHeight * (1 + this.threshold) &&
        rect.bottom >= 0 &&
        rect.left <= windowWidth &&
        rect.right >= 0
      );
    }
    return false;
  }
}

@Directive({
  selector: '[appLazyLoadSectionNotStandAlone]',
})
export class LazyLoadSectionDirectiveNotStandAlone implements AfterViewInit {
  @Input() threshold: number = 0.1; // Default threshold for triggering
  @Output() sectionInView = new EventEmitter<boolean>(); // Event emitter to notify when the section is in view

  private hasIntersected: boolean = false;

  constructor(private el: ElementRef,
    @Inject(PLATFORM_ID) private platformId: Object
  ) { }

  ngAfterViewInit() {
    setTimeout(() => {
      if (this.isInViewport()) {
        this.sectionInView.emit(true);
      }
    }, 100); // Adjust timeout as necessary
  }

  @HostListener('window:scroll', [])
  onWindowScroll(): void {
    this.checkVisibility();
  }

  @HostListener('window:resize', [])
  onResize(): void {
    this.checkVisibility();
  }

  private checkVisibility(): void {
    if (this.isInViewport() && !this.hasIntersected) {
      this.hasIntersected = true; // Ensure it only triggers once
      this.sectionInView.emit(true);
    }
  }

  private isInViewport(): boolean {
    if (isPlatformBrowser(this.platformId)) {
      const rect = this.el.nativeElement.getBoundingClientRect();
      const windowHeight = window.innerHeight || document.documentElement.clientHeight;
      const windowWidth = window.innerWidth || document.documentElement.clientWidth;
      // Check if the element is in the viewport with the given threshold
      return (
        rect.top <= windowHeight * (1 + this.threshold) &&
        rect.bottom >= 0 &&
        rect.left <= windowWidth &&
        rect.right >= 0
      );
    }
    return false
  }
}