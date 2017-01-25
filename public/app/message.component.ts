import {Component, OnInit} from "@angular/core";
import {ErrorService, IMessage} from "./error.service";
import {Router} from "@angular/router";
import {Subscription} from "rxjs";


@Component({
    selector: 'message',
    styles: [`
        :host {
            position: absolute;
            width: 70%;
            right: 15%;
            z-index: 100;
            
        }
        .messageContent .card{
            background: transparent;
        }
        .messageContent.flash {
            background: rgba(255, 255, 141, 0.8);
            color: #444;
        }
        .messageContent.error {
            background: rgba(229, 100, 73, 0.8);
            color: #FFF;
        }

    `],
    template: `
      <div *ngIf="open" class="message">
            <div class="messageContent" [ngClass]="{'flash': message.type === 'flash', 'error': message.type === 'error'}">
                  <div 
                        
                        class="card">
                    <div class="card-content">
                          <span class="card-title">
                            <i class="material-icons">error_outline</i>
                            {{message.title}}
                          </span>
                            <p>{{message.message}}</p>
                    </div>
                    <div class="card-action">
                      <a href="#" (click)="close($event)">
                            Close
                            <span *ngIf="open && currentSeconds">({{currentSeconds}})</span>
                      </a>
                    </div>
                  </div>
            </div>
      </div>
    `
})
export class MessageComponent implements OnInit {

    open = false;
    message : IMessage;
    currentInterval = null;
    currentSeconds = 0;
    subscription :Subscription = null;
    constructor(private errorService:ErrorService) {
        const SECONDS = 4;
        this.message = <IMessage>{message: '', title: '', type: "message", code: 0};
        console.log('Loaded MessageComponent error subscribing', errorService.errorAnnounced);
        this.subscription = errorService.errorAnnounced.subscribe((message : IMessage) => {
            if (message.type === 'ignore') {
                return;
            }
            console.log('Message Component receive', message);
            this.message = message;
            clearInterval(this.currentInterval);
            this.currentSeconds = 0;
            if (message.type === 'flash') {
                this.currentSeconds = SECONDS;
                this.currentInterval = setInterval(()=>{
                    if (this.currentSeconds-- <= 0) {
                        clearInterval(this.currentInterval);
                        this.close();
                    }
                }, 1000);
            }
            this.open = true;
        });
    }

    private close(event?) {
        if (event) event.preventDefault();
        this.open = false;
    }

    ngOnInit() {
        console.log('Message Component ngOnInit', this.subscription)
    }
}

