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

  public showPasswordIcon :string = 'fa-solid fa-eye' // fa-sharp fa-solid fa-eye-slash
  public showPasswordRepartIcon :string = 'fa-solid fa-eye' // fa-sharp fa-solid fa-eye-slash
  public errorMessage: string = '';
  public email: string  = '';
  public password: string = '';
  public repartPassword: string  = '';
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
      console.error("Input element '#password' not found.");
      return;
    }
  
    this.showPasswordIcon = (inputElement.type === 'password') ? 'fa-solid fa-eye-slash' : 'fa-solid fa-eye';
    inputElement.type = (inputElement.type === 'password') ? 'text' : 'password';
  }
  
  public showRepartPassword(): void {
    const inputElement = document.querySelector<HTMLInputElement>('#repartPassword');
  
    if (!inputElement) {
      console.error("Input element '#repartPassword' not found.");
      return;
    }
  
    this.showPasswordRepartIcon = (inputElement.type === 'password') ? 'fa-solid fa-eye-slash' : 'fa-solid fa-eye';
    inputElement.type = (inputElement.type === 'password') ? 'text' : 'password';
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
        this.formValidatorService.setForm('registerForm')
        this.formValidatorService.showErrors(errorList.error.errors)
        this.formValidatorService.restNotUseInputs(errorList.error.errors)
      }
    });
  }

  private handleSuccessResponse(response: any): void {
    const emailInput = this.getElement('#email');
    const feedbackElement = this.getElement('#emailFeedback');

    if (response.available === false) {
      this.applyInvalidStyles(emailInput, feedbackElement, 'emailNotAvailable');
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
