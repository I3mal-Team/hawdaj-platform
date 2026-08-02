import { ComponentFixture, TestBed } from '@angular/core/testing';

import { TourGuideDetailsComponent } from './tour-guide-details.component';

describe('TourGuideDetailsComponent', () => {
  let component: TourGuideDetailsComponent;
  let fixture: ComponentFixture<TourGuideDetailsComponent>;

  beforeEach(() => {
    TestBed.configureTestingModule({
      imports: [TourGuideDetailsComponent]
    });
    fixture = TestBed.createComponent(TourGuideDetailsComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
