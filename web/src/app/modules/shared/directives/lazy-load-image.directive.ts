import { Directive, ElementRef, Input, OnInit, Renderer2, Inject, PLATFORM_ID } from '@angular/core';
import { isPlatformBrowser } from '@angular/common';

@Directive({
  selector: '[appLazyLoadImage]',
  standalone: true
})
export class LazyLoadImageDirective implements OnInit {
  @Input() appLazyLoadImage!: string;

  constructor(
    private el: ElementRef<HTMLImageElement>,
    private renderer: Renderer2,
    @Inject(PLATFORM_ID) private platformId: Object
  ) { }

  ngOnInit() {
    if (isPlatformBrowser(this.platformId)) {
      if ('IntersectionObserver' in window) {
        const observer = new IntersectionObserver(entries => {
          entries.forEach(entry => {
            if (entry.isIntersecting) {
              this.renderer.setAttribute(this.el.nativeElement, 'src', this.appLazyLoadImage);
              observer.unobserve(entry.target);
            }
          });
        });

        observer.observe(this.el.nativeElement);
      } else {
        // Fallback for older browsers
        this.renderer.setAttribute(this.el.nativeElement, 'src', this.appLazyLoadImage);
      }
    } else {
      // SSR fallback for SEO
      this.renderer.setAttribute(this.el.nativeElement, 'src', this.appLazyLoadImage);
    }
  }
}
