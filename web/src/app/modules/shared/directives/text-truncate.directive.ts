import { isPlatformBrowser } from '@angular/common';
import { Directive, ElementRef, Inject, Input, PLATFORM_ID, Renderer2 } from '@angular/core';

@Directive({
  selector: '[appTextTruncate]',
  standalone: true
})
export class TextTruncateDirective {
  @Input() maxLines: number = 2;
  @Input() readMoreText: string = 'Read More';
  @Input() color: string = 'blue';
  @Input() readLessText: string = 'Read Less';

  private fullText: string = '';
  private isCollapsed: boolean = true;
  private readMoreLink!: HTMLElement;

  constructor(
    private el: ElementRef,
    private renderer: Renderer2,
    @Inject(PLATFORM_ID) private platformId: Object
  ) {}

  ngAfterViewInit(): void {
    if (isPlatformBrowser(this.platformId)) {
      this.fullText = this.el.nativeElement.innerText;
      this.checkTextOverflow();
    }
  }

  private checkTextOverflow(): void {
    const element = this.el.nativeElement;
    const computedStyles = window.getComputedStyle(element);
    const lineHeight = parseFloat(computedStyles.lineHeight);
    const maxHeight = this.maxLines * lineHeight;
    
    if (element.scrollHeight > maxHeight) {
      this.renderer.setStyle(element, 'max-height', `${maxHeight}px`);
      this.renderer.setStyle(element, 'overflow', 'hidden');
      this.addReadMoreLink();
    }
  }

  private addReadMoreLink(): void {
    this.readMoreLink = this.renderer.createElement('a');
    const text = this.renderer.createText(this.readMoreText);

    this.renderer.appendChild(this.readMoreLink, text);
    this.renderer.setAttribute(this.readMoreLink, 'href', '#');
    this.renderer.setStyle(this.readMoreLink, 'display', 'block');
    this.renderer.setStyle(this.readMoreLink, 'margin-top', '10px');
    this.renderer.setStyle(this.readMoreLink, 'color', this.color);
    this.renderer.setStyle(this.readMoreLink, 'font-size', 'small');
    this.renderer.setStyle(this.readMoreLink, 'font-weight', '500');
    this.renderer.setStyle(this.readMoreLink, 'text-decoration', 'underline');
    
    this.renderer.listen(this.readMoreLink, 'click', (event) => this.toggleText(event));

    this.renderer.appendChild(this.el.nativeElement.parentNode, this.readMoreLink);
  }

  private toggleText(event: Event): void {
    event.preventDefault();
    const element = this.el.nativeElement;

    if (this.isCollapsed) {
      this.renderer.setStyle(element, 'max-height', 'none');
      this.readMoreLink.innerText = this.readLessText;
    } else {
      const computedStyles = window.getComputedStyle(element);
      const lineHeight = parseFloat(computedStyles.lineHeight);
      const maxHeight = this.maxLines * lineHeight;
      this.renderer.setStyle(element, 'max-height', `${maxHeight}px`);
      this.readMoreLink.innerText = this.readMoreText;
    }
    this.isCollapsed = !this.isCollapsed;
  }
}
