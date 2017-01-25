import { platformBrowserDynamic } from '@angular/platform-browser-dynamic';
import {AppModule} from "./app.module";
import {enableProdMode} from "@angular/core";

window['DEPLOY_MODE'] = window['DEPLOY_MODE'] || 'dev';
console.log('DEPLOY MODE', window['DEPLOY_MODE']);
if (window['DEPLOY_MODE'] === 'prod') {
    enableProdMode();
}
platformBrowserDynamic().bootstrapModule(AppModule);
