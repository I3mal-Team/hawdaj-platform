import { Component, HostListener, Inject, PLATFORM_ID, Renderer2, ElementRef, ViewChild } from '@angular/core';
import { isPlatformBrowser } from '@angular/common';
import { DialogService } from 'primeng/dynamicdialog';
import { CreateTripComponent } from '../../../../components/create-trip/create-trip.component';
import { AuthService } from '../../../../services/auth.service';
import { TranslateModule } from '@ngx-translate/core';
import { PrepearTripStepperComponent } from 'src/app/domains';

@Component({
  standalone: true,
  imports: [
    TranslateModule
  ],
  selector: 'app-scroll-top',
  templateUrl: './scroll-top.component.html',
  styleUrls: ['./scroll-top.component.scss'],
})
export class ScrollTopComponent {
  scrollProgress: number = 0;

  // References to elements in the template
  @ViewChild('progress', { static: false }) progressElement!: ElementRef<HTMLDivElement>;
  @ViewChild('camelContent', { static: false }) camelContentElement!: ElementRef<HTMLDivElement>;

  constructor(
    @Inject(PLATFORM_ID) private platformId: Object,
    private dialogService: DialogService,
    private authService: AuthService,
    private renderer: Renderer2
  ) { }

  ngOnInit(): void { }

  @HostListener('window:scroll', [])
  onWindowScroll(): void {
    if (isPlatformBrowser(this.platformId)) {
      const scrollTop = window.scrollY || document.documentElement.scrollTop || document.body.scrollTop || 0;

      this.updateScrollProgress(scrollTop);
      this.toggleVisibility(scrollTop);
    }
  }

  private updateScrollProgress(scrollTop: number): void {
    const winHeight = window.innerHeight;
    this.scrollProgress = (scrollTop / (document.body.scrollHeight - winHeight)) * 100;

    if (this.progressElement) {
      this.renderer.setStyle(
        this.progressElement.nativeElement,
        'background',
        `conic-gradient(#7939a7 ${this.scrollProgress}%,#d7d7d7 ${this.scrollProgress}%)`
      );
      const scrollTop = window.scrollY || document.documentElement.scrollTop || document.body.scrollTop || 0;
      const winHeight = window.innerHeight;
      this.scrollProgress = (scrollTop / (document.body.scrollHeight - winHeight)) * 100;
      let progress: any = document?.querySelector('#progress');
      progress.style.background = `conic-gradient(#7939a7 ${this.scrollProgress}%,#d7d7d7  ${this.scrollProgress}%)`
    }
  }

  private toggleVisibility(scrollTop: number): void {
    const isScrolled = scrollTop > 50;

    if (this.progressElement) {
      this.toggleClass(this.progressElement.nativeElement, 'd-none', !isScrolled);
    }

    if (this.camelContentElement) {
      this.toggleClass(this.camelContentElement.nativeElement, 'd-none', !isScrolled);
    }
  }

  private toggleClass(element: HTMLElement, className: string, add: boolean): void {
    if (add) {
      this.renderer.addClass(element, className);
    } else {
      this.renderer.removeClass(element, className);
    }
  }

  scrollTop(): void {
    if (isPlatformBrowser(this.platformId)) {
      window.scrollTo({ top: 0, behavior: 'smooth' });
    }
  }

  startTrip(): void {
    if (isPlatformBrowser(this.platformId)) {
      const ref = this.dialogService.open(PrepearTripStepperComponent, {
        width: '65%',
        height: '100vh',
        dismissableMask: false,
        styleClass: 'start-trip-dialog',
        baseZIndex: 10001,
      });
    }
  }
}
