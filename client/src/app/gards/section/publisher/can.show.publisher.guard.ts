import { Injectable } from '@angular/core';
import { ActivatedRouteSnapshot, CanActivate, RouterStateSnapshot, UrlTree } from '@angular/router';
import { Observable } from 'rxjs';
import { IsGranted } from 'src/app/interface/common';
import { HttpServiceService } from 'src/app/services/common/http-service/http-service.service';
import { IsGrantedService } from 'src/app/services/common/is-granted/is-granted.service';

@Injectable({
  providedIn: 'root'
})
export class CanShowPublisherGuard implements CanActivate {

  constructor(private isGrantedService : IsGrantedService){}

  canActivate(
    route: ActivatedRouteSnapshot,
    state: RouterStateSnapshot): Observable<boolean | UrlTree> | Promise<boolean | UrlTree> | boolean | UrlTree {

    return new Observable<boolean | UrlTree>(observer => {
      this.isGrantedService.getPermision('CAN_EDIT_PUBLISHER','Publisher',69).subscribe((isGrantedList: IsGranted) => {
        if(isGrantedList?.success !== undefined){
          observer.next(isGrantedList?.success);
          observer.complete();
        }
      });
    });
  }
  
}
