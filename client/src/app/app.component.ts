import { Component,OnInit } from '@angular/core';
import { AuthService } from './services/auth/auth.service';
import { HttpServiceService } from './services/http-service/http-service.service';

@Component({
  selector: 'app-root',
  templateUrl: './app.component.html',
  styleUrls: ['./app.component.scss']
})
export class AppComponent implements OnInit {
  title = 'client';

  constructor(public authService: AuthService, private HttpServiceService : HttpServiceService){}

  ngOnInit(): void {
    if(this.authService.isTokenNeedRefresh()){
      this.HttpServiceService.getData('http://localhost/api/refresh-tokken/'+this.authService.getUserInfoFromToken()['id']).subscribe((data) => {
        this.authService.setToken(data['token'])
      });
    }
  }
}
