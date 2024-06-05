import { ComponentRef, Injectable } from '@angular/core';
import { TranslationService } from '../translation/translation.service';

@Injectable({
  providedIn: 'root'
})
export class FormValidatorService {

  private formControls: NodeListOf<Element> | null = null;

  public constructor(public translationService: TranslationService) { }

  public setForm(id: string): void {
    this.formControls = document.querySelectorAll('[form ="'+id+'"]');
  }

  public showErrors(errorList: { [key: string]: string }): void {
    Object.entries(errorList).forEach(([inputName, value]) => {
      const input = inputName.replace(/\./g, "");
      const inputClass = document!.querySelector('[formId ="'+input+'"]');
      const inputErrorValue = document!.querySelector('[formFeedback ="'+input+'"]');

      if (inputClass) inputClass.classList.add('is-invalid');
      if (inputErrorValue) inputErrorValue.innerHTML = value[1];
    });
  }

  public  restNotUseInputs(errorList:any): void {

    let inValidClass = new Array();
    Object.entries(errorList).forEach(([inputName]) => {
      inValidClass.push(inputName)
    });

    this.formControls?.forEach(element => {
      let id = element.getAttribute('formId')
      if(id !== null){
        if(!inValidClass.includes(id)){
          element.classList.remove('is-invalid')
        }
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