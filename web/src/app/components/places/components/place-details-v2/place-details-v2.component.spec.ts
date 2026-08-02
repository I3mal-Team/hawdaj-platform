import { ComponentFixture, TestBed } from '@angular/core/testing';

import { PlaceDetailsV2Component } from './place-details-v2.component';

describe('PlaceDetailsV2Component', () => {
  let component: PlaceDetailsV2Component;
  let fixture: ComponentFixture<PlaceDetailsV2Component>;

  beforeEach(() => {
    TestBed.configureTestingModule({
      imports: [PlaceDetailsV2Component]
    });
    fixture = TestBed.createComponent(PlaceDetailsV2Component);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
