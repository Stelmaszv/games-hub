import { Injectable } from '@angular/core';
import { CanActivate, UrlTree } from '@angular/router';
import { IsGrantedService } from 'src/app/services/common/is-granted/is-granted.service';

@Injectable({
  providedIn: 'root'
})
export class CanListPublishersGuard implements CanActivate {
  constructor(private isGrantedService : IsGrantedService){}

  async canActivate(): Promise<boolean | UrlTree> {
    return await this.isGrantedService.checkIfGuardCanActivate('CAN_LIST_PUBLISHERS');
  }
  
}
