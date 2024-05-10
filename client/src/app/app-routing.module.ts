import { NgModule } from '@angular/core';
import { RouterModule, Routes } from '@angular/router';
import { PublishersComponent } from '../app/components/sections/publishers/publishers.component'; // Importuj komponenty, które będą wykorzystywane w trasach

const routes: Routes = [
  { path: '', component: PublishersComponent },
  { path: 'about', component: PublishersComponent }
];

@NgModule({
  imports: [RouterModule.forRoot(routes)],
  exports: [RouterModule]
})
export class AppRoutingModule { }