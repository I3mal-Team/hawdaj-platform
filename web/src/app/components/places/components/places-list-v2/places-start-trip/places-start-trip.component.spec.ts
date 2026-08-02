import { ComponentFixture, TestBed } from '@angular/core/testing';

import { PlacesStartTripComponent } from './places-start-trip.component';

describe('PlacesStartTripComponent', () => {
  let component: PlacesStartTripComponent;
  let fixture: ComponentFixture<PlacesStartTripComponent>;

  beforeEach(() => {
    TestBed.configureTestingModule({
      imports: [PlacesStartTripComponent]
    });
    fixture = TestBed.createComponent(PlacesStartTripComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
