import { Injectable } from '@angular/core';
import { TranslationService } from '../translation/translation.service';

@Injectable({
  providedIn: 'root'
})
export class FormValidatorService {

  private form: HTMLFormElement | null = null;
  private formControls: NodeListOf<Element> | null = null;

  public constructor(public translationService: TranslationService) { }

  public setForm(id: string): void {
    const idTag = '#' + id;
    this.form = document.querySelector(idTag);
    if (this.form !== null) {
      this.formControls = this.form.querySelectorAll('.form-control');
    }
  }

  public showErrors(errorList: { [key: string]: string }): void {
    if (!this.form) return;

    Object.entries(errorList).forEach(([inputName, value]) => {
      const idName = '#' + inputName;
      const inputClass = this.form!.querySelector(idName);
      const inputErrorClassName = '#' + inputName + 'Feedback';
      const inputErrorValue = this.form!.querySelector(inputErrorClassName);
      
      const values = value.split(' ')
      
      if (inputClass) inputClass.classList.add('is-invalid');
      if (inputErrorValue) inputErrorValue.innerHTML = this.translationService.translate(values[0],JSON.parse(this.convertStringToJson(value)));
    });
  }

  public  restNotUseInputs(errorList: { [key: string]: string }): void {
    if (!this.formControls || !this.form) return;

    this.formControls.forEach(element => {
      if (element instanceof HTMLElement && element.id && !(element.id in errorList)) {
        element.classList.remove('is-invalid');
    
        const inputErrorClassName = '#' + element.id + 'Feedback';
        const inputErrorValue = this.form!.querySelector(inputErrorClassName);
        if (inputErrorValue) inputErrorValue.innerHTML = '';
      }
    });
  }

  private convertStringToJson(str: string): string  {
    const keyValuePairs = str.split(' ');
    const result: { [key: string]: any } = {};

    keyValuePairs.forEach(pair => {
      const [key, value] = pair.split(':');
      if (key && value !== undefined) {
        result[key] = isNaN(Number(value)) ? value : Number(value);
      }
    });

    return JSON.stringify(result);
  }
}