import { Injectable } from '@angular/core';

@Injectable({
  providedIn: 'root'
})
export class FormValidatorService {

  private form: HTMLFormElement | null = null;
  private formControls: NodeListOf<Element> | null = null;

  constructor() { }

  setForm(id: string): void {
    const idTag = '#' + id;
    this.form = document.querySelector(idTag);
    if (this.form !== null) {
      this.formControls = this.form.querySelectorAll('.form-control');
    }
  }

  showErrors(errorList: { [key: string]: string }): void {
    if (!this.form) return;

    Object.entries(errorList).forEach(([inputName, value]) => {
      const idName = '#' + inputName;
      const inputClass = this.form!.querySelector(idName);
      const inputErrorClassName = '#' + inputName + 'Feedback';
      const inputErrorValue = this.form!.querySelector(inputErrorClassName);

      if (inputClass) inputClass.classList.add('is-invalid');
      if (inputErrorValue) inputErrorValue.innerHTML = value;
    });
  }

  restNotUseInputs(errorList: { [key: string]: string }): void {
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
}