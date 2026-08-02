import { Directive, ElementRef, Input, OnInit, Renderer2, AfterViewInit, ChangeDetectorRef } from '@angular/core';

@Directive({
  selector: '[appLazyLoad]',
  standalone: true
})
export class LazyLoadDirective implements OnInit, AfterViewInit {
  @Input() dataSrc: string;
  @Input() placeholder: string = 'assets/newImages/not-found/no-img.svg';
  // @Input() errorImage: string = 'assets/images/not-found/no-image.svg';
  @Input() errorImage: string = '';
  @Input() sliderContainer: HTMLElement;

  private observer: IntersectionObserver;

  constructor(private el: ElementRef, private renderer: Renderer2, private cdr: ChangeDetectorRef) {
    
  }

  ngOnInit() {
    this.setPlaceholder();
    this.setupObserver();
  }

  ngAfterViewInit() {
    if (this.sliderContainer) {
      this.sliderContainer.addEventListener('slideChange', () => {
        this.cdr.detectChanges();  // Trigger change detection on custom events

        this.setPlaceholder();
        this.checkIfImageInView();
      });
    } 
  }  

  private setupObserver() {
    const options = {
      root: this.sliderContainer || null,
      rootMargin: '0px',
      threshold: 0.1
    };

    this.observer = new IntersectionObserver((entries, observer) => {
      entries.forEach(entry => {
        if (entry.isIntersecting) {
          this.loadImage();
          observer.unobserve(entry.target);
        }
      });
    }, options);

    this.observer.observe(this.el.nativeElement);
  }

  private setPlaceholder() {
    const imgElement = this.el.nativeElement as HTMLImageElement;
    this.renderer.setAttribute(imgElement, 'src', this.placeholder);
    this.renderer.setAttribute(imgElement, 'onerror', `this.src='${this.errorImage}'`);
  }

  private loadImage() {
    const imgElement = this.el.nativeElement as HTMLImageElement;
    if (this.dataSrc) {
      this.renderer.setAttribute(imgElement, 'src', this.dataSrc);
    }
  }

  private checkIfImageInView() {
    const imgElement = this.el.nativeElement as HTMLImageElement;
    const rect = imgElement.getBoundingClientRect();
    const inView = (
      rect.top >= 0 &&
      rect.left >= 0 &&
      rect.bottom <= (window.innerHeight || document.documentElement.clientHeight) &&
      rect.right <= (window.innerWidth || document.documentElement.clientWidth)
    );

    if (inView) {
      this.loadImage();
    }
  }
}



@Directive({
  selector: '[appLazyLoadNotStandAlone]',
})
export class LazyLoadDirectiveNotStandAlone implements OnInit, AfterViewInit {
  @Input() dataSrc: string;
  @Input() placeholder: string = 'assets/newImages/not-found/no-img.svg';
  // @Input() errorImage: string = 'assets/images/not-found/no-image.svg';
  @Input() errorImage: string = '';
  @Input() sliderContainer: HTMLElement;

  private observer: IntersectionObserver;

  constructor(private el: ElementRef, private renderer: Renderer2, private cdr: ChangeDetectorRef) {
    
  }

  ngOnInit() {
    this.setPlaceholder();
    this.setupObserver();
  }

  ngAfterViewInit() {
    if (this.sliderContainer) {
      this.sliderContainer.addEventListener('slideChange', () => {
        this.cdr.detectChanges();  // Trigger change detection on custom events

        this.setPlaceholder();
        this.checkIfImageInView();
      });
    } 
  }  

  private setupObserver() {
    const options = {
      root: this.sliderContainer || null,
      rootMargin: '0px',
      threshold: 0.1
    };

    this.observer = new IntersectionObserver((entries, observer) => {
      entries.forEach(entry => {
        if (entry.isIntersecting) {
          this.loadImage();
          observer.unobserve(entry.target);
        }
      });
    }, options);

    this.observer.observe(this.el.nativeElement);
  }

  private setPlaceholder() {
    const imgElement = this.el.nativeElement as HTMLImageElement;
    this.renderer.setAttribute(imgElement, 'src', this.placeholder);
    this.renderer.setAttribute(imgElement, 'onerror', `this.src='${this.errorImage}'`);
  }

  private loadImage() {
    const imgElement = this.el.nativeElement as HTMLImageElement;
    if (this.dataSrc) {
      this.renderer.setAttribute(imgElement, 'src', this.dataSrc);
    }
  }

  private checkIfImageInView() {
    const imgElement = this.el.nativeElement as HTMLImageElement;
    const rect = imgElement.getBoundingClientRect();
    const inView = (
      rect.top >= 0 &&
      rect.left >= 0 &&
      rect.bottom <= (window.innerHeight || document.documentElement.clientHeight) &&
      rect.right <= (window.innerWidth || document.documentElement.clientWidth)
    );

    if (inView) {
      this.loadImage();
    }
  }
}
