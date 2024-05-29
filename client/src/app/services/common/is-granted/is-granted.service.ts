import { Injectable } from '@angular/core';
import { Observable, BehaviorSubject } from 'rxjs';
import { HttpServiceService } from '../http-service/http-service.service';


@Injectable({
  providedIn: 'root'
})
export class IsGrantedService {

  private responseData!: Observable<any>;

  constructor(private httpServiceService: HttpServiceService) { }

  private isGranted(attribute: string, subject: object | null = null): void {
    const data = {
      'attribute': attribute,
      'subject': subject
    };

    this.getIsGranted(data);
  }

  private getIsGranted(data: any): void {
    const subject = new BehaviorSubject<any>(null);
    this.responseData = subject.asObservable();

    this.httpServiceService.postData('http://localhost/api/isGranted', data).subscribe((response: any) => {
      subject.next(response);
    });
  }

  public getPermissionForList(permission: string): Observable<any> {
    this.isGranted(permission);
    return this.responseData;
  }

  public setPermission(permission: string, element: any, permissionStringName: string, entity: string): void {
    this.isGranted(permission, { "entity": entity, "id": element.id });

    this.responseData.subscribe((response: any) => {
      if (response && 'success' in response) {
        element[permissionStringName] = response.success;
      }
    });
  }

  public getPermission(permission: string, entity: string | null = null, id: number | null = null): Observable<any> {
    this.isGranted(permission, { "entity": entity, "id": id });
    return this.responseData;
  }

  public checkIfGuardCanActivate(permission: string, entity: string | null = null, id: number | null = null): Promise<boolean> {
    return new Promise<boolean>((resolve) => {
      this.getPermission(permission, entity, id).subscribe((response: any) => {
        if (response?.success !== undefined) {
          resolve(response.success);
        }
      });
    });
  }

}
