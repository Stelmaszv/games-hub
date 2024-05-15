import { Injectable } from '@angular/core';

@Injectable({
  providedIn: 'root'
})
export class AuthService {
  private token: string | null = null;
  private expirationTimeRemaining: number | null = null;

  constructor() {
    this.token = this.getTokenFromLocalStorage();
    this.calculateExpirationTimeRemaining();
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
    const formattedDate = `${date.getDate()} - ${this.getMonthName(date.getMonth())} - ${date.getFullYear()} (left ${this.getDaysUntilDate(date)} days)`;
    return formattedDate;
  }

  private getDaysUntilDate(targetDate: Date): number {
    const currentDate = new Date();
    const differenceInTime = targetDate.getTime() - currentDate.getTime();
    const differenceInDays = Math.ceil(differenceInTime / (1000 * 3600 * 24));
    return differenceInDays;
  }

  private getMonthName(month: number): string {
    const months = [
      "January", "February", "March", "April", "May", "June",
      "July", "August", "September", "October", "November", "December"
    ];
    return months[month] || "Invalid month";
  }
}