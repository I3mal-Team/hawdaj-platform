import { Component, Input } from '@angular/core';
import { CommonModule } from '@angular/common';
import { RouterModule } from '@angular/router';
import { RatingModule } from 'primeng/rating';
import { FormsModule } from '@angular/forms';
import { PublicService } from 'src/app/modules/shared/services/public.service';

@Component({
  standalone: true,
  imports: [CommonModule, RouterModule, RatingModule, FormsModule],
  selector: 'app-review-card',
  templateUrl: './review-card.component.html',
  styleUrls: ['./review-card.component.scss']
})
export class ReviewCardComponent {
  @Input() item: any;
  currentLanguage: string = '';

  constructor(
    private publicService: PublicService
  ) { }

  ngOnInit(): void {
    this.currentLanguage = this.publicService.getCurrentLanguage();
  }
}

