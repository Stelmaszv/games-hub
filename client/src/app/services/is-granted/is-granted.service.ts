import { Injectable } from '@angular/core';
import { HttpServiceService } from '../http-service/http-service.service'
import { Observable, BehaviorSubject } from 'rxjs';

@Injectable({
  providedIn: 'root'
})
export class IsGrantedService {

  private data: any;
  public responseData!: Observable<any>;

  constructor(private httpServiceService: HttpServiceService) { }

  setData(attribute: string, subject: object): void {
    this.data = {
      'attribute': attribute,
      'subject': subject
    }

    this.init();
  }

  init(): void {
    const subject = new BehaviorSubject<any>(null);
    this.responseData = subject.asObservable();

    this.httpServiceService.postData('http://localhost/api/isGranted', this.data).subscribe((data: any) => {
      subject.next(data);
    });
  }
}
