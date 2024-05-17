import { Component } from '@angular/core';
import { HttpServiceService } from 'src/app/services/http-service/http-service.service';
import { TranslationService } from 'src/app/services/translation/translation.service';
import { HttpErrorResponse } from '@angular/common/http';
import { AuthService } from 'src/app/services/auth/auth.service';

@Component({
  selector: 'app-login',
  templateUrl: './login.component.html',
  styleUrls: ['./login.component.scss']
})
export class LoginComponent {

  public email: string | null = 'user@qwe.com';
  public password: string | null = '123';
  public loginFailed: boolean  | null = false;
  public errorMessage: string = '';
  
  constructor(
    public translationService: TranslationService, 
    private httpServiceService: HttpServiceService, 
    private authService : AuthService
  ) {}

  onSubmit() {
    this.httpServiceService.postData('http://localhost/api/login',{ 
      'email' : this.email,
      'password':this.password
    }).subscribe({
      next: (response) => {
        this.loginFailed = false;
        this.authService.setToken(response.token)
        location.reload(); 
      },
      error: (error: HttpErrorResponse) => {
        this.loginFailed = true;
        this.errorMessage = error.error.message || 'An unknown error occurred';
      }
    });
  }
}
