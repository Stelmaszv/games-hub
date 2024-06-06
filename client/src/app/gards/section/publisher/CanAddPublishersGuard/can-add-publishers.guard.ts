import { Injectable } from '@angular/core';
import { ActivatedRouteSnapshot, CanActivate, RouterStateSnapshot, UrlTree } from '@angular/router';
import { Observable } from 'rxjs';
import { IsGrantedService } from 'src/app/services/common/is-granted/is-granted.service';

@Injectable({
  providedIn: 'root'
})
export class CanAddPublishersGuard implements CanActivate {

  constructor(private isGrantedService : IsGrantedService){}

  async canActivate(route: ActivatedRouteSnapshot): Promise<boolean | UrlTree> {
    return await this.isGrantedService.checkIfGuardCanActivate('CAN_ADD_PUBLISHER', 'Publisher');
  }
  
}
