import { HttpClient, HttpHeaders  } from '@angular/common/http';
import { Injectable } from '@angular/core';
import { Observable } from 'rxjs';
import { AuthService } from '../../sevices/auth/auth.service'

@Injectable({
  providedIn: 'root'
})
export class HttpServiceService {

  constructor(private http: HttpClient, private authService: AuthService) { }

  getData(url : string): Observable<any> {

    const token = this.authService.getToken();

    const headers = new HttpHeaders({
      'Authorization': `Bearer ${token}`
    });

    // Wysyłanie żądania GET z tokenem JWT w nagłówku
    return this.http.get<any>(url, { headers });
  }
}
