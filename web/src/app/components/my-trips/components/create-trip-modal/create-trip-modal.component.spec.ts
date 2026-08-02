import { ComponentFixture, TestBed } from '@angular/core/testing';

import { CreateTripModalComponent } from './create-trip-modal.component';

describe('CreateTripModalComponent', () => {
  let component: CreateTripModalComponent;
  let fixture: ComponentFixture<CreateTripModalComponent>;

  beforeEach(() => {
    TestBed.configureTestingModule({
      declarations: [CreateTripModalComponent]
    });
    fixture = TestBed.createComponent(CreateTripModalComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
