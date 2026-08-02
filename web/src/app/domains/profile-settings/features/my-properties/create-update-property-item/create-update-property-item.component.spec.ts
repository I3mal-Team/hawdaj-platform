import { ComponentFixture, TestBed } from '@angular/core/testing';

import { CreateUpdatePropertyItemComponent } from './create-update-property-item.component';

describe('CreateUpdatePropertyItemComponent', () => {
  let component: CreateUpdatePropertyItemComponent;
  let fixture: ComponentFixture<CreateUpdatePropertyItemComponent>;

  beforeEach(() => {
    TestBed.configureTestingModule({
      imports: [CreateUpdatePropertyItemComponent]
    });
    fixture = TestBed.createComponent(CreateUpdatePropertyItemComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
