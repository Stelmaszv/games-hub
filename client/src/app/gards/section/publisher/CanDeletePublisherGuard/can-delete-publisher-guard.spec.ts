import { TestBed } from '@angular/core/testing';

import { CanDeletePublisherGuard } from './can-delete-publisher-guard';

describe('CanDeletPublisherGuardGuard', () => {
  let guard: CanDeletePublisherGuard;

  beforeEach(() => {
    TestBed.configureTestingModule({});
    guard = TestBed.inject(CanDeletePublisherGuard);
  });

  it('should be created', () => {
    expect(guard).toBeTruthy();
  });
});
