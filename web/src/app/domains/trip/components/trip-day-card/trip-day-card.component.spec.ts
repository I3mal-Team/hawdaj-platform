import { ComponentFixture, TestBed } from '@angular/core/testing';

import { TripDayCardComponent } from './trip-day-card.component';

describe('TripDayCardComponent', () => {
  let component: TripDayCardComponent;
  let fixture: ComponentFixture<TripDayCardComponent>;

  beforeEach(() => {
    TestBed.configureTestingModule({
      imports: [TripDayCardComponent]
    });
    fixture = TestBed.createComponent(TripDayCardComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
