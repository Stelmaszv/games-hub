import { Injectable } from '@angular/core';
import { Observable, BehaviorSubject } from 'rxjs';
import { HttpServiceService } from '../http-service/http-service.service';

@Injectable({
  providedIn: 'root'
})
export class IsGrantedService {

  private data: any;
  private responseData!: Observable<any>;

  public constructor(private httpServiceService: HttpServiceService) { }

  public isGranted(attribute: string, subject: object | null = null): void {
    this.data = {
      'attribute': attribute,
      'subject': subject
    }

    this.getIsGranted();
  }

  public getIsGranted(): void {
    const subject = new BehaviorSubject<any>(null);
    this.responseData = subject.asObservable();

    this.httpServiceService.postData('http://localhost/api/isGranted', this.data).subscribe((data: any) => {
      subject.next(data);
    });
  }

  public getPermisionForList(permision: string): Observable<any> {
    this.isGranted(permision);

    return this.responseData;
  }

  public setPermision(permision: string, element: any, permisionStringName: string,entity: string ) {
    this.isGranted(permision, { "entity": entity, "id": element.id });
      
    this.responseData.subscribe((el) => {
      if (el && 'success' in el) {
        element[permisionStringName] = el.success;
      }
    });
  }

  public getPermision(permision: string, entity: string | null = null , id : number | null = null  ) {
    this.isGranted(permision, { "entity": entity, "id": id });
      
    return this.responseData;
  }
}