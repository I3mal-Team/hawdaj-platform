import { ComponentFixture, TestBed } from '@angular/core/testing';

import { EarthV2Component } from './earth-v2.component';

describe('EarthV2Component', () => {
  let component: EarthV2Component;
  let fixture: ComponentFixture<EarthV2Component>;

  beforeEach(() => {
    TestBed.configureTestingModule({
      imports: [EarthV2Component]
    });
    fixture = TestBed.createComponent(EarthV2Component);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
