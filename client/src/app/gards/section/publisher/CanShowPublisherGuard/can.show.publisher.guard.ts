import { Injectable } from '@angular/core';
import { ActivatedRouteSnapshot, CanActivate, UrlTree, Router } from '@angular/router';
import { IsGrantedService } from 'src/app/services/common/is-granted/is-granted.service';

@Injectable({
  providedIn: 'root'
})
export class CanShowPublisherGuard implements CanActivate {

  constructor(private isGrantedService : IsGrantedService){}

  async canActivate(route: ActivatedRouteSnapshot): Promise<boolean | UrlTree> {
    return await this.isGrantedService.checkIfGuardCanActivate('CAN_SHOW_PUBLISHER', 'Publisher', Number(route.paramMap.get('id')));
  }
  
}
