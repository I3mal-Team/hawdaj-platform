import { isPlatformBrowser } from "@angular/common";
import { Directive, OnInit, Input, TemplateRef, ViewContainerRef, HostListener, PLATFORM_ID, Inject } from "@angular/core";

@Directive({
  selector: '[appResponsiveIfNotStandalone]',
  standalone: true // Add this line
})
export class ResponsiveIfDirectiveNotStandalone implements OnInit {
  @Input() appResponsiveIfNotStandalone: string;

  constructor(private templateRef: TemplateRef<any>
    , private viewContainer: ViewContainerRef,
    @Inject(PLATFORM_ID) private platformId: Object
  ) { }

  ngOnInit(): void {
    if (isPlatformBrowser(this.platformId)) {
      this.updateView();
    }
  }

  @HostListener('window:resize', [])
  onResize() {
    if (isPlatformBrowser(this.platformId)) {
      this.updateView();
    }
  }

  private updateView(): void {
    if (isPlatformBrowser(this.platformId)) {
      const condition = this.appResponsiveIfNotStandalone === 'desktop' ? window.innerWidth >= 1200 : window.innerWidth < 1200;

      this.viewContainer.clear();

      if (condition) {
        this.viewContainer.createEmbeddedView(this.templateRef);
      }
    }
  }
}

@Directive({
  selector: '[appResponsiveIfStandalone]',
  standalone: true // Add this line to make the directive standalone
})
export class ResponsiveIfStandaloneDirective implements OnInit {
  @Input() appResponsiveIfStandalone: string;

  constructor(private templateRef: TemplateRef<any>
    , private viewContainer: ViewContainerRef,
    @Inject(PLATFORM_ID) private platformId: Object
  ) { }

  ngOnInit(): void {
    this.updateView();
  }

  @HostListener('window:resize', [])
  onResize() {
    if (isPlatformBrowser(this.platformId)) {
      this.updateView();
    }
  }

  private updateView(): void {
    if (isPlatformBrowser(this.platformId)) {
      const condition = this.appResponsiveIfStandalone === 'desktop' ? window.innerWidth >= 1200 : window.innerWidth < 1200;

      this.viewContainer.clear();

      if (condition) {
        this.viewContainer.createEmbeddedView(this.templateRef);
      }
    }
  }
}