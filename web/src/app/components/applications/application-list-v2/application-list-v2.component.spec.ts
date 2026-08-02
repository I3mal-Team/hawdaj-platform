import { ComponentFixture, TestBed } from '@angular/core/testing';

import { ApplicationListV2Component } from './application-list-v2.component';

describe('ApplicationListV2Component', () => {
  let component: ApplicationListV2Component;
  let fixture: ComponentFixture<ApplicationListV2Component>;

  beforeEach(() => {
    TestBed.configureTestingModule({
      imports: [ApplicationListV2Component]
    });
    fixture = TestBed.createComponent(ApplicationListV2Component);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
