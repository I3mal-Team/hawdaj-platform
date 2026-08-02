import { ComponentFixture, TestBed } from '@angular/core/testing';

import { ApplicationCardV2Component } from './application-card-v2.component';

describe('ApplicationCardV2Component', () => {
  let component: ApplicationCardV2Component;
  let fixture: ComponentFixture<ApplicationCardV2Component>;

  beforeEach(() => {
    TestBed.configureTestingModule({
      imports: [ApplicationCardV2Component]
    });
    fixture = TestBed.createComponent(ApplicationCardV2Component);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
