import { Component } from '@angular/core';
import { TranslationService } from 'src/app/services/common/translation/translation.service';
import { HttpErrorResponse } from '@angular/common/http';
import { HttpServiceService } from 'src/app/services/common/http-service/http-service.service';
import { AuthService } from 'src/app/services/common/auth/auth.service';
import { FormValidatorService } from 'src/app/services/common/form-validator/form-validator.service';

@Component({
  selector: 'app-register',
  templateUrl: './register.component.html',
  styleUrls: ['./register.component.scss']
})
export class RegisterComponent {

  public showPasswordIcon :string = 'fa-solid fa-eye'
  public showPasswordRepartIcon :string = 'fa-solid fa-eye'
  public errorMessage: string  = '';
  public email: string = '';
  public password: string = '';
  public repartPassword: string = '';
  public registerFailed: boolean  | null = false;

  public constructor(
    public translationService: TranslationService, 
    private httpServiceService: HttpServiceService, 
    private authService : AuthService,
    private formValidatorService: FormValidatorService
  ) {}

  public showPassword(): void {
    const inputElement = document.querySelector<HTMLInputElement>('#password');
  
    if (!inputElement) {
      return;
    }
  
    this.showPasswordIcon = (inputElement.type === 'password') ? 'fa-solid fa-eye-slash' : 'fa-solid fa-eye';
    inputElement.type = (inputElement.type === 'password') ? 'text' : 'password';
  }
  
  public showRepartPassword(): void {
    const inputElement = document.querySelector<HTMLInputElement>('#repartPassword');
  
    if (!inputElement) {
      return;
    }
  
    this.showPasswordRepartIcon = (inputElement.type === 'password') ? 'fa-solid fa-eye-slash' : 'fa-solid fa-eye';
    inputElement.type = (inputElement.type === 'password') ? 'text' : 'password';
  }

  public passwordStrength(): void {
    this.httpServiceService.getData(`http://localhost/api/dynamic-validation/password-strength/${this.password}`).subscribe({
      next: (response) => {
        const inputElement = document.querySelector<HTMLInputElement>('#password');
        if (inputElement) {
          inputElement.classList.remove('form-success', 'form-warning', 'form-danger');
  
          switch(response.password){
            case "strong":
              inputElement.classList.add('form-success');
              break;
            case "medium":
              inputElement.classList.add('form-warning');
              break;
            case "weak":
              inputElement.classList.add('form-danger');
              break;
          }
        }
      }
    });
  }
  public validLogin(): void {
    this.httpServiceService.getData(`http://localhost/api/dynamic-validation/login/${this.email}`).subscribe({
      next: (response) => {
        this.handleSuccessResponse(response);
      },
      error: (errorList: HttpErrorResponse) => {
        this.handleErrorResponse(errorList);
      }
    });
  }

  public onSubmit() {
    this.httpServiceService.postData('http://localhost/api/register',{ 
      'email' : this.email,
      'password':this.password,
      'repartPassword':this.repartPassword
    }).subscribe({
      next: (response) => {
        this.registerFailed = false;
        this.authService.setToken(response.token)
        location.reload(); 
      },
      error: (errorList: HttpErrorResponse) => {
        const register = Object.keys(errorList.error.errors);
      
        let registerErrors: { [key: string]: any } = {};
  
        for (let error of Object.entries(errorList.error.errors)) {
          if (register.indexOf(error[0].toString()) !== -1) { 
            const key = error[0].replace(/\./g, "");
            registerErrors[key] = error;
          }
        }
  
        this.formValidatorService.setForm('registerForm')
        this.formValidatorService.showErrors(registerErrors, { 
          'email' : this.email,
          'password':this.password,
          'repartPassword':this.repartPassword
        })
        this.formValidatorService.restNotUseInputs(registerErrors)
      }
    });
  }

  private handleSuccessResponse(response: any): void {
    const emailInput = this.getElement('#email');
    const feedbackElement = this.getElement('#emailFeedback');

    if (response.available === false) {
      this.applyInvalidStyles(emailInput, feedbackElement, 'userExist');
    } else {
      this.applyValidStyles(emailInput);
    }
  }

  private handleErrorResponse(errorList: HttpErrorResponse): void {
    const emailInput = this.getElement('#email');
    const feedbackElement = this.getElement('#emailFeedback');

    this.applyInvalidStyles(emailInput, feedbackElement, errorList.error.message);
  }

  private getElement(selector: string): HTMLInputElement | null {
    return document.querySelector<HTMLInputElement>(selector);
  }

  private applyInvalidStyles(element: HTMLInputElement | null, feedbackElement: HTMLInputElement | null, message: string): void {
    element?.classList.add('is-invalid', 'form-danger');
    element?.classList.remove('form-success');
    if (feedbackElement) feedbackElement.innerHTML = this.translationService.translate(message);
  }

  private applyValidStyles(element: HTMLInputElement | null): void {
    element?.classList.remove('is-invalid', 'form-danger');
    element?.classList.add('form-success');
  }
}
