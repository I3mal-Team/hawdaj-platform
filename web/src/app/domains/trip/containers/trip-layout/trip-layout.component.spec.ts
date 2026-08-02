import { ComponentFixture, TestBed } from '@angular/core/testing';

import { TripLayoutComponent } from './trip-layout.component';

describe('TripLayoutComponent', () => {
  let component: TripLayoutComponent;
  let fixture: ComponentFixture<TripLayoutComponent>;

  beforeEach(() => {
    TestBed.configureTestingModule({
      imports: [TripLayoutComponent]
    });
    fixture = TestBed.createComponent(TripLayoutComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
