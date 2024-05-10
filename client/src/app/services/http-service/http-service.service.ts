import { HttpClient, HttpHeaders  } from '@angular/common/http';
import { Injectable } from '@angular/core';
import { Observable } from 'rxjs';
import { AuthService } from '../../services/auth/auth.service'

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

    return this.http.get<any>(url, { headers });
  }

  postData(url: string, data: any): Observable<any> {
    const token = this.authService.getToken();

    // Utwórz nagłówek z tokenem JWT
    const headers = new HttpHeaders({
      'Authorization': `Bearer ${token}`,
      'Content-Type': 'application/json' // Ustawienie typu zawartości jako JSON
    });

    // Wyślij żądanie POST z danymi i nagłówkiem
    return this.http.post<any>(url, data, { headers });
  }
}
