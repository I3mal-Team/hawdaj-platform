import { ComponentFixture, TestBed } from '@angular/core/testing';

import { MyTripsListV2Component } from './my-trips-list-v2.component';

describe('MyTripsListV2Component', () => {
  let component: MyTripsListV2Component;
  let fixture: ComponentFixture<MyTripsListV2Component>;

  beforeEach(() => {
    TestBed.configureTestingModule({
      imports: [MyTripsListV2Component]
    });
    fixture = TestBed.createComponent(MyTripsListV2Component);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
