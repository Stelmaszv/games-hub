import { Injectable } from '@angular/core';
import { ActivatedRouteSnapshot, CanActivate, RouterStateSnapshot, UrlTree, Router } from '@angular/router';
import { IsGrantedService } from 'src/app/services/common/is-granted/is-granted.service';

@Injectable({
  providedIn: 'root'
})
export class CanShowPublisherGuard implements CanActivate {

  constructor(private isGrantedService : IsGrantedService,private router: Router){}

  async canActivate(
    route: ActivatedRouteSnapshot,
    state: RouterStateSnapshot
  ): Promise<boolean | UrlTree> {
    const idParam = route.paramMap.get('id');

    if (!idParam) {
      return this.router.createUrlTree(['/']);
    }

    const id = +idParam;
    const isGranted = await this.isGrantedService.checkIsGardCanActive('CAN_SHOW_PUBLISHER', 'Publisher', id);

    return (isGranted) ? true :  this.router.createUrlTree(['/'])
  }
  
}
