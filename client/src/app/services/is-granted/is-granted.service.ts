import { Injectable } from '@angular/core';
import { HttpServiceService } from '../http-service/http-service.service'
import { Observable, BehaviorSubject } from 'rxjs';

@Injectable({
  providedIn: 'root'
})
export class IsGrantedService {

  private data: any;
  public responseData!: Observable<any>; // Deklarujesz responseData jako Observable

  constructor(private httpServiceService: HttpServiceService) { }

  setData(attribute: string, subject: object): void {
    this.data = {
      'attribute': attribute,
      'subject': subject
    }

    this.init();
  }

  init(): void {
    // Tutaj możesz użyć BehaviorSubject, aby `responseData` było od razu subskrybowalne
    const subject = new BehaviorSubject<any>(null);
    this.responseData = subject.asObservable();

    this.httpServiceService.postData('http://localhost/api/isGranted', this.data).subscribe((data: any) => {
      subject.next(data); // Aktualizujesz dane w BehaviorSubject
    });
  }
}
