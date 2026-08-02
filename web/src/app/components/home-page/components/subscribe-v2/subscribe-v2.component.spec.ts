import { ComponentFixture, TestBed } from '@angular/core/testing';

import { SubscribeV2Component } from './subscribe-v2.component';

describe('SubscribeV2Component', () => {
  let component: SubscribeV2Component;
  let fixture: ComponentFixture<SubscribeV2Component>;

  beforeEach(() => {
    TestBed.configureTestingModule({
      imports: [SubscribeV2Component]
    });
    fixture = TestBed.createComponent(SubscribeV2Component);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
