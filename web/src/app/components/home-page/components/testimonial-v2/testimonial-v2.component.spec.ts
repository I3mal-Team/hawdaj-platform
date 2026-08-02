import { ComponentFixture, TestBed } from '@angular/core/testing';

import { TestimonialV2Component } from './testimonial-v2.component';

describe('TestimonialV2Component', () => {
  let component: TestimonialV2Component;
  let fixture: ComponentFixture<TestimonialV2Component>;

  beforeEach(() => {
    TestBed.configureTestingModule({
      imports: [TestimonialV2Component]
    });
    fixture = TestBed.createComponent(TestimonialV2Component);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
