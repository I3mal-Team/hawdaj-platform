import { ComponentFixture, TestBed } from '@angular/core/testing';

import { HomeTripV2Component } from './home-trip-v2.component';

describe('HomeTripV2Component', () => {
  let component: HomeTripV2Component;
  let fixture: ComponentFixture<HomeTripV2Component>;

  beforeEach(() => {
    TestBed.configureTestingModule({
      imports: [HomeTripV2Component]
    });
    fixture = TestBed.createComponent(HomeTripV2Component);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
