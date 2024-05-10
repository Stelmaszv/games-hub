import { Injectable } from '@angular/core';

@Injectable({
  providedIn: 'root'
})
export class AuthService {

  public setToken(token: string): void {
    localStorage.setItem('token', token);
  }

  public getToken(): string | null {
    return localStorage.getItem('token');
  }

  public removeToken(): void {
    localStorage.removeItem('token');
  }

  public getUserInfoFromToken(token: string): any {
    if (!token) return null;

    const base64Url = token.split('.')[1];
    const base64 = base64Url.replace(/-/g, '+').replace(/_/g, '/');
    const decodedData = JSON.parse(atob(base64));

    return decodedData;
  }

  public isTokenExpired(token: string): boolean {
    const decodedData = this.getUserInfoFromToken(token);
    if (!decodedData || !decodedData.exp) return true;

    const now = new Date();
    const expirationDate = new Date(decodedData.exp);

    return now >= expirationDate;
  }
}
