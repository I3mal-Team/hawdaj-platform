import { ComponentFixture, TestBed } from '@angular/core/testing';

import { CreateTripDetailsComponent } from './create-trip-details.component';

describe('CreateTripDetailsComponent', () => {
  let component: CreateTripDetailsComponent;
  let fixture: ComponentFixture<CreateTripDetailsComponent>;

  beforeEach(() => {
    TestBed.configureTestingModule({
      imports: [CreateTripDetailsComponent]
    });
    fixture = TestBed.createComponent(CreateTripDetailsComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
