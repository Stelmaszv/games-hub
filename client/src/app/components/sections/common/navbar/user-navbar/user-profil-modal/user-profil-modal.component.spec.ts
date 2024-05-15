import { ComponentFixture, TestBed } from '@angular/core/testing';

import { UserProfilModalComponent } from './user-profil-modal.component';

describe('UserProfilModalComponent', () => {
  let component: UserProfilModalComponent;
  let fixture: ComponentFixture<UserProfilModalComponent>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      declarations: [ UserProfilModalComponent ]
    })
    .compileComponents();

    fixture = TestBed.createComponent(UserProfilModalComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
