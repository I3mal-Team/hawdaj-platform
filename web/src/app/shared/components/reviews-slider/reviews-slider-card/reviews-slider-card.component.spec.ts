import { ComponentFixture, TestBed } from '@angular/core/testing';

import { ReviewsSliderCardComponent } from './reviews-slider-card.component';

describe('ReviewsSliderCardComponent', () => {
  let component: ReviewsSliderCardComponent;
  let fixture: ComponentFixture<ReviewsSliderCardComponent>;

  beforeEach(() => {
    TestBed.configureTestingModule({
      imports: [ReviewsSliderCardComponent]
    });
    fixture = TestBed.createComponent(ReviewsSliderCardComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
