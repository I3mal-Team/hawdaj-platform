import { ComponentFixture, TestBed } from '@angular/core/testing';

import { NoResult2Component } from './no-result-2.component';

describe('NoResult2Component', () => {
  let component: NoResult2Component;
  let fixture: ComponentFixture<NoResult2Component>;

  beforeEach(() => {
    TestBed.configureTestingModule({
      imports: [NoResult2Component]
    });
    fixture = TestBed.createComponent(NoResult2Component);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
