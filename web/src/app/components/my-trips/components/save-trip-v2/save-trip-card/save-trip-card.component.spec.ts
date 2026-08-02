import { ComponentFixture, TestBed } from '@angular/core/testing';

import { SaveTripCardComponent } from './save-trip-card.component';

describe('SaveTripCardComponent', () => {
  let component: SaveTripCardComponent;
  let fixture: ComponentFixture<SaveTripCardComponent>;

  beforeEach(() => {
    TestBed.configureTestingModule({
      imports: [SaveTripCardComponent]
    });
    fixture = TestBed.createComponent(SaveTripCardComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
