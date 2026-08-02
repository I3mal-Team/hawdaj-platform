import { Component, OnInit, Input } from '@angular/core';
import { TranslateModule } from '@ngx-translate/core';
import { CommonModule } from '@angular/common';
import { CarouselModule } from 'primeng/carousel';

@Component({
  standalone: true,
  imports: [CommonModule, TranslateModule, CarouselModule],
  selector: 'app-testimonial',
  templateUrl: './testimonial.component.html',
  styleUrls: ['./testimonial.component.scss']
})
export class TestimonialComponent implements OnInit {
  @Input() testimonialData: any = [];
  responsiveOptions = [
    {
      center: true,
      breakpoint: '1199px',
      numVisible: 1,
      numScroll: 1
    },
    {
      center: true,
      breakpoint: '991px',
      numVisible: 1,
      numScroll: 1
    },
    {
      center: true,
      breakpoint: '767px',
      numVisible: 1,
      numScroll: 1
    },
    {
      center: true,
      breakpoint: '420px',
      numVisible: 1,
      numScroll: 1
    }
  ];
  currentLanguage: any;
  ngOnInit(): void {
  }
}
