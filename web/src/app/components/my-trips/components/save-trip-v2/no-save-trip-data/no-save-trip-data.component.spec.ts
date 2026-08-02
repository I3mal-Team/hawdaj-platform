import { ComponentFixture, TestBed } from '@angular/core/testing';

import { NoSaveTripDataComponent } from './no-save-trip-data.component';

describe('NoSaveTripDataComponent', () => {
  let component: NoSaveTripDataComponent;
  let fixture: ComponentFixture<NoSaveTripDataComponent>;

  beforeEach(() => {
    TestBed.configureTestingModule({
      imports: [NoSaveTripDataComponent]
    });
    fixture = TestBed.createComponent(NoSaveTripDataComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
