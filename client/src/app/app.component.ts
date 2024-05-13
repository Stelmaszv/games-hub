import { Component } from '@angular/core';
import { AuthService } from '../app/services/auth/auth.service';

@Component({
  selector: 'app-root',
  templateUrl: './app.component.html',
  styleUrls: ['./app.component.scss']
})
export class AppComponent {
  title = 'client';

  constructor(private authService : AuthService) { }

  ngOnInit(): void {
    this.authService.isTokenExpired()
    console.log('ol')
  }
}
