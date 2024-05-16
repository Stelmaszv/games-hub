import { Injectable } from '@angular/core';
import { TranslationService } from '../translation/translation.service';
import { HttpServiceService } from '../http-service/http-service.service';

@Injectable({
  providedIn: 'root'
})
export class AuthService {
  private token: string | null = null;
  private expirationTimeRemaining: number | null = null;

  constructor(public TranslationService : TranslationService) {
    this.token = this.getTokenFromLocalStorage();
    this.calculateExpirationTimeRemaining();
  }

  public isTokenNeedRefresh() : boolean{
    if (this.expirationTimeRemaining === null) {
      return false;
    }

    const expirationDate = new Date(Date.now() + this.expirationTimeRemaining);

    return (this.getDaysUntilDate(expirationDate) < 2)
  }

  public setToken(token: string): void {
    localStorage.setItem('token', token);
    this.token = token;
    this.calculateExpirationTimeRemaining();
  }

  public getToken(): string | null {
    return this.token;
  }

  public removeToken(): void {
    localStorage.removeItem('token');
    this.token = null;
    this.expirationTimeRemaining = null;
  }

  public getUserInfoFromToken(): any {
    const token = this.getToken();
    if (!token) return null;

    const decodedData = this.decodeToken(token);
    return decodedData;
  }

  public isTokenExpired(): boolean {
    const token = this.getToken();
    if (!token) return true;

    const decodedData = this.getUserInfoFromToken();
    if (!decodedData || !decodedData.exp) return true;

    const now = new Date();
    const expirationDate = new Date(decodedData.exp * 1000);

    return now >= expirationDate;
  }

  public getExpirationTimeRemaining(): string | null {
    if (this.expirationTimeRemaining === null) {
      return null;
    }

    const expirationDate = new Date(Date.now() + this.expirationTimeRemaining);
    const formattedDate = this.formatDate(expirationDate);

    return formattedDate;
  }

  private getTokenFromLocalStorage(): string | null {
    return localStorage.getItem('token');
  }

  private calculateExpirationTimeRemaining(): void {
    const token = this.getToken();
    if (!token) {
      this.expirationTimeRemaining = null;
      return;
    }

    const decodedData = this.decodeToken(token);
    if (!decodedData || !decodedData.exp) {
      this.expirationTimeRemaining = null;
      return;
    }

    const now = new Date();
    const expirationDate = new Date(decodedData.exp * 1000);
    const timeDifference = expirationDate.getTime() - now.getTime();
    this.expirationTimeRemaining = Math.max(0, timeDifference);
  }

  private decodeToken(token: string): any {
    const base64Url = token.split('.')[1];
    const base64 = base64Url.replace(/-/g, '+').replace(/_/g, '/');
    return JSON.parse(atob(base64));
  }

  private formatDate(date: Date): string {
    const translation = this.TranslationService.translate('sesionExpirationLeft',{'days':String(this.getDaysUntilDate(date))});
    const month = this.TranslationService.translateMonth(date.getMonth())

    const formattedDate = `${date.getDate()} - ${month} - ${date.getFullYear()} (${translation})`;
    return formattedDate;
  }

  private getDaysUntilDate(targetDate: Date): number {
    const currentDate = new Date();
    const differenceInTime = targetDate.getTime() - currentDate.getTime();
    const differenceInDays = Math.ceil(differenceInTime / (1000 * 3600 * 24));
    return differenceInDays;
  }
}