import { ComponentFixture, TestBed } from '@angular/core/testing';

import { PublishersMainListComponent } from './publishers-main-list.component';

describe('PublishersMainListComponent', () => {
  let component: PublishersMainListComponent;
  let fixture: ComponentFixture<PublishersMainListComponent>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      declarations: [ PublishersMainListComponent ]
    })
    .compileComponents();

    fixture = TestBed.createComponent(PublishersMainListComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
