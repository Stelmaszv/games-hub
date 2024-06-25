import { Injectable } from '@angular/core';

@Injectable({
  providedIn: 'root'
})
export class BootstrapService {

  public showAlert(id: string, className:string ,messages: string, status : string|null = null ): void {

    var alert = document.getElementById(id);

    if (alert && alert.style) {
      alert.style.display = 'block';
      let alertClass = alert.querySelector('.alert');
      let alertHeading = alert.querySelector('.alert-heading');
      let alertBody = alert.querySelector('.alert-body');

      if (alertHeading && status) {
        alertHeading.innerHTML = status
      }

      if (alertClass) {
        alertClass.classList.add(className);
      }

      if (alertBody) {
        alertBody.innerHTML = messages
      }
    }

    setTimeout(() => {
      var alert = document.getElementById(id);

      if (alert && alert.style) {
        alert.style.display = 'none'
      }

    }, 5000);
  
  }

  public closeModal(id: string): void {
    const modal = document.getElementById(id);
    if (modal) {
      modal.classList.remove('show');
      modal.classList.add('fade');
      modal.style.display = 'none';
      modal.setAttribute('aria-hidden', 'true');

      document.body.classList.remove('modal-open');
      
      const backdrop = document.getElementsByClassName('modal-backdrop')[0];
      if (backdrop) {
        backdrop.parentNode?.removeChild(backdrop);
      }
    }
  }

  public openModal(id: string): void {
    const modal = document.getElementById(id);
    if (modal) {
      modal.style.display = 'block';
      modal.setAttribute('aria-hidden', 'false');
      modal.classList.add('show');
      modal.classList.remove('fade');

      document.body.classList.add('modal-open');
      
      const backdrop = document.createElement('div');
      backdrop.className = 'modal-backdrop show';
      document.body.appendChild(backdrop);
    }
  }

}
