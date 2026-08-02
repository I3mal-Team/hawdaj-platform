import { ComponentFixture, TestBed } from '@angular/core/testing';

import { ReviewEventSliderComponent } from './review-event-slider.component';

describe('ReviewEventSliderComponent', () => {
  let component: ReviewEventSliderComponent;
  let fixture: ComponentFixture<ReviewEventSliderComponent>;

  beforeEach(() => {
    TestBed.configureTestingModule({
      declarations: [ReviewEventSliderComponent]
    });
    fixture = TestBed.createComponent(ReviewEventSliderComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
