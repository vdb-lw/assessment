import { browser, by, element } from 'protractor';

export class AppPage {
  navigateTo(): Promise<unknown> {
    return browser.get(browser.baseUrl) as Promise<unknown>;
  }

  getTitleText(): Promise<string> {
    return element(by.css('.navbar-text')).getText() as Promise<string>;
  }

  getPayoffText(): Promise<string> {
    return element(by.css('app-root h2')).getText() as Promise<string>;
  }

  getProductListElements() {
    return element.all(by.css('.mat-row'));
  }

  getFormRamElementsCheckboxes() {
    return element.all(by.css('.form-check .mat-checkbox'));
  }

  getFormRamElementsSlider() {
    return element.all(by.css('.form-group .mat-slider'));
  }

  getFormRamElementsSelect() {
    return element.all(by.css('.form-group .mat-select'));
  }

  getFormRamElementsButton() {
    return element.all(by.css('.row .mat-button-base'));
  }

  submitFormButton() {
    return element(by.buttonText('Submit'));
  }

  resetFormButton() {
    return element(by.buttonText('Reset'));
  }

  compareFormButton() {
    return element(by.buttonText('Compare'));
  }
}
