import { ComponentFixture, TestBed } from '@angular/core/testing';

import { MessagesModelComponent } from './messages-model.component';

describe('MessagegesModelComponent', () => {
  let component: MessagesModelComponent;
  let fixture: ComponentFixture<MessagesModelComponent>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      declarations: [ MessagesModelComponent ]
    })
    .compileComponents();

    fixture = TestBed.createComponent(MessagesModelComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
