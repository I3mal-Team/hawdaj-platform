import { ComponentFixture, TestBed } from '@angular/core/testing';

import { ReviewsSliderListComponent } from './reviews-slider-list.component';

describe('ReviewsSliderListComponent', () => {
  let component: ReviewsSliderListComponent;
  let fixture: ComponentFixture<ReviewsSliderListComponent>;

  beforeEach(() => {
    TestBed.configureTestingModule({
      imports: [ReviewsSliderListComponent]
    });
    fixture = TestBed.createComponent(ReviewsSliderListComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
