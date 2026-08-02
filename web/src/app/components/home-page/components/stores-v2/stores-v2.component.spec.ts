import { ComponentFixture, TestBed } from '@angular/core/testing';

import { StoresV2Component } from './stores-v2.component';

describe('StoresV2Component', () => {
  let component: StoresV2Component;
  let fixture: ComponentFixture<StoresV2Component>;

  beforeEach(() => {
    TestBed.configureTestingModule({
      imports: [StoresV2Component]
    });
    fixture = TestBed.createComponent(StoresV2Component);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
