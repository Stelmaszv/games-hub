import { Injectable } from '@angular/core';
import { TranslationService } from '../translation/translation.service';

@Injectable({
  providedIn: 'root'
})
export class FormValidatorService {

  private form: HTMLFormElement | null = null;
  private formControls: NodeListOf<Element> | null = null;
  private errorsList:Array<any> = []

  public constructor(public translationService: TranslationService) { }

  public setForm(id: string): void {
    const idTag = '#' + id;
    this.form = document.querySelector(idTag);
    if (this.form !== null) {
      this.formControls = this.form.querySelectorAll('.form-control');
    }
    console.log(this.formControls)
  }

  public showErrors(errorList: { [key: string]: string }): void {
    Object.entries(errorList).forEach(([inputName, value]) => {
      const input = inputName.replace(/\./g, "");
      const inputClass = document!.querySelector('[formId ="'+input+'"]');
      const inputErrorValue = document!.querySelector('[formFeedback ="'+input+'"]');

      const values = value

      if (inputClass){
        this.errorsList.push({'formId':inputClass,'formFeedback':inputErrorValue})
      }

      if (inputClass) inputClass.classList.add('is-invalid');
      if (inputErrorValue) inputErrorValue.innerHTML = values[1];
    });
  }

  public  restNotUseInputs(errorList: { [key: string]: string }): void {
    let errorsStan = Object.entries(errorList).length != this.errorsList.length
    
    this.errorsList.forEach(element => {
      console.log(element)
      /*
      if (element instanceof HTMLElement && element.id && !(element.id in errorList)) {
        element.classList.remove('is-invalid');
    
        const inputErrorClassName = '#' + element.id + 'Feedback';
        const inputErrorValue = this.form!.querySelector(inputErrorClassName);
        if (inputErrorValue) inputErrorValue.innerHTML = '';
      }
      */
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