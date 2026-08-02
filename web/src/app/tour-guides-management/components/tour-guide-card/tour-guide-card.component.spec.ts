import { ComponentFixture, TestBed } from '@angular/core/testing';

import { TourGuideCardComponent } from './tour-guide-card.component';

describe('TourGuideCardComponent', () => {
  let component: TourGuideCardComponent;
  let fixture: ComponentFixture<TourGuideCardComponent>;

  beforeEach(() => {
    TestBed.configureTestingModule({
      imports: [TourGuideCardComponent]
    });
    fixture = TestBed.createComponent(TourGuideCardComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
