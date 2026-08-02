import { ComponentFixture, TestBed } from '@angular/core/testing';

import { MyTripDetailsV2Component } from './my-trip-details-v2.component';

describe('MyTripDetailsV2Component', () => {
  let component: MyTripDetailsV2Component;
  let fixture: ComponentFixture<MyTripDetailsV2Component>;

  beforeEach(() => {
    TestBed.configureTestingModule({
      imports: [MyTripDetailsV2Component]
    });
    fixture = TestBed.createComponent(MyTripDetailsV2Component);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
