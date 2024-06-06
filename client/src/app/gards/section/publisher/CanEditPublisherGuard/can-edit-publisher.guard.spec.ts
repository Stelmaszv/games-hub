import { TestBed } from '@angular/core/testing';

import { CanEditPublisherGuard } from './can-edit-publisher.guard';

describe('CanEditPublisherGuard', () => {
  let guard: CanEditPublisherGuard;

  beforeEach(() => {
    TestBed.configureTestingModule({});
    guard = TestBed.inject(CanEditPublisherGuard);
  });

  it('should be created', () => {
    expect(guard).toBeTruthy();
  });
});
