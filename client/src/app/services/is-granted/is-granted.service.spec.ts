import { TestBed } from '@angular/core/testing';

import { IsGrantedService } from './is-granted.service';

describe('IsGrantedService', () => {
  let service: IsGrantedService;

  beforeEach(() => {
    TestBed.configureTestingModule({});
    service = TestBed.inject(IsGrantedService);
  });

  it('should be created', () => {
    expect(service).toBeTruthy();
  });
});
