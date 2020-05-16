https://stackblitz.com/edit/angular-pipes-v8

//**********Built in Pipes********* */
Example
File: app.component.ts
import { Component } from '@angular/core';

@Component({
  selector: 'app-root',
  templateUrl: './app.component.html'
})
export class AppComponent {
  title = 'my-first-app';  
  todaydate = new Date();  
  jsonval = {name: 'Alex', age: '25', address:{a1: 'Paris', a2: 'France'}};  
  months = ['Jan', 'Feb', 'Mar', 'April', 'May', 'Jun',  
    'July', 'Aug', 'Sept', 'Oct', 'Nov', 'Dec'];  
}

File: app.component.html
<h1>Uppercase Pipe</h1>  
    <b>{{title | uppercase}}</b><br/>  
    <h1>Lowercase Pipe</h1>  
    <b>{{title | lowercase}}</b>  
    <h1>Currency Pipe</h1>  
    <b>{{6589.23 | currency:"USD"}}</b><br/>  
    <b>{{6589.23 | currency:"USD":true}}</b> <br>
     <b>{{6589.23 | currency: "USD":false}}</b> 
    <h1>Date pipe</h1>  
    <b>{{todaydate | date:'d/M/y'}}</b><br/>  
    <b>{{todaydate | date:'shortTime'}}</b>  
    <h1>Decimal Pipe</h1>  
    <b>{{ 454.78787814 | number: '3.4-4' }}</b> // 3 is for main integer, 4 -4 are for integers to be displayed.  
  
    <h1>Json Pipe</h1>  
    <b>{{ jsonval | json }}</b>  
    <h1>Percent Pipe</h1>  
    <b>{{00.54565 | percent}}</b>  
    <h1>Slice Pipe</h1>  
    <b>{{months | slice:2:6}}</b>  
    // here 2 and 6 refers to the start and the end index

//**********Custom Pipe Development********* */
//File: sqrt-pipe.ts
import {Pipe, PipeTransform} from '@angular/core';  
@Pipe ({  
  name : 'sqrt'  
})  
export class SqrtPipe implements PipeTransform {  
  transform(val : number) : number {  
    return Math.sqrt(val);  
  }  
} 

//File: app.module.ts
import { NgModule } from '@angular/core';
import { BrowserModule }  from '@angular/platform-browser';

import { AppComponent } from './app.component';
import { SqrtPipe } from './sqrt-pipe'; 

@NgModule({
  imports: [
    BrowserModule
  ],
  declarations: [
    AppComponent,
    SqrtPipe
  ],
  bootstrap: [ AppComponent ]
})
export class AppModule { }

//File: app.component.html
<h1>Example of Custom Pipe</h1>  
<h2>Square root of 16 is: {{16 | sqrt}}</h2><br/>  