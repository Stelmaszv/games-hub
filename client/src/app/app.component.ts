import { Component,OnInit } from '@angular/core';
import { AuthGuard } from './gards/auth-guard';
import { ActivatedRouteSnapshot, Router, RouterStateSnapshot} from '@angular/router';
import { HttpServiceService } from './services/common/http-service/http-service.service';
import { AuthService } from './services/common/auth/auth.service';

@Component({
  selector: 'app-root',
  templateUrl: './app.component.html',
  styleUrls: ['./app.component.scss']
})
export class AppComponent implements OnInit {
  public title : string = 'Gamers Hub';
  public auth : boolean = false;
  
  constructor(public authService: AuthService, private HttpServiceService : HttpServiceService, private authGuard: AuthGuard, private router: Router){}

  ngOnInit(): void {
    const emptyRouteSnapshot = {} as ActivatedRouteSnapshot;

    this.auth = !this.authGuard.canActivate(emptyRouteSnapshot, this.router.routerState.snapshot)
    let authRouts: string[] = ['/register','/login','/forgot-password'];

    setTimeout(() => {
      if (!authRouts.includes(this.router.url) && this.auth){
        this.router.navigate(['/login']);
      }
    }, 100);

    if(this.authService.isTokenNeedRefresh()){
      this.HttpServiceService.getData('http://localhost/api/refresh-tokken/'+this.authService.getUserInfoFromToken()['id']).subscribe((data) => {
        this.authService.setToken(data['token'])
      });
    }
  }
}
