import { ComponentFixture, TestBed } from '@angular/core/testing';

import { TripListCardComponent } from './trip-list-card.component';

describe('TripListCardComponent', () => {
  let component: TripListCardComponent;
  let fixture: ComponentFixture<TripListCardComponent>;

  beforeEach(() => {
    TestBed.configureTestingModule({
      imports: [TripListCardComponent]
    });
    fixture = TestBed.createComponent(TripListCardComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
