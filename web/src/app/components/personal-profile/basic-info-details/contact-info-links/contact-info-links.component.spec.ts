import { ComponentFixture, TestBed } from '@angular/core/testing';

import { ContactInfoLinksComponent } from './contact-info-links.component';

describe('ContactInfoLinksComponent', () => {
  let component: ContactInfoLinksComponent;
  let fixture: ComponentFixture<ContactInfoLinksComponent>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      imports: [ContactInfoLinksComponent]
    })
    .compileComponents();

    fixture = TestBed.createComponent(ContactInfoLinksComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});

