import { AppPage } from './app.po';
import {browser, by, element, ExpectedConditions, logging, protractor} from 'protractor';

describe('workspace-project App', () => {
  let page: AppPage;

  beforeEach(() => {
    page = new AppPage();
  });

  it('should display the correct title', () => {
    page.navigateTo();
    expect(page.getTitleText()).toEqual('LWComparator');
  });

  it('should display the correct payoff', () => {
    page.navigateTo();
    expect(page.getPayoffText()).toEqual('Products which meet your requirements');
  });

  it('should display the correct number of products', () => {
    page.navigateTo();
    expect(page.getProductListElements().count()).toBe(486);
  });

  it('should display the correct number of form elements', () => {
    page.navigateTo();
    expect(page.getFormRamElementsCheckboxes().count()).toBe(10);
    expect(page.getFormRamElementsSelect().count()).toBe(2);
    expect(page.getFormRamElementsSlider().count()).toBe(1);
    expect(page.getFormRamElementsButton().count()).toBe(3);
  });

  it('should possible take 0 results after form submit and then restore previous data', () => {
    page.navigateTo();

    // Ram.
    element(by.id('mat-checkbox-1')).click();
    // Hdd.
    element(by.id('mat-select-0')).click();
    element(by.cssContainingText('mat-option .mat-option-text', 'SATA')).click();
    page.submitFormButton().click();

    // No results
    expect(element(by.css('app-content .ng-star-inserted')).getText()).toEqual('No products found');
    expect(page.getProductListElements().count()).toBe(0);

    // Reset.
    page.resetFormButton().click();
    expect(page.getProductListElements().count()).toBe(486);
  });

  it('should possible compare product results', () => {
    page.navigateTo();

    // Ram.
    element(by.id('mat-checkbox-3')).click();
    page.submitFormButton().click();
    // Results
    expect(page.getProductListElements().count()).toBe(35);

    // Reset.
    page.resetFormButton().click();

    // Checkboxes compare
    element(by.id('mat-checkbox-983')).click();
    element(by.id('mat-checkbox-986')).click();
    browser.executeScript('window.scrollTo(0,0);');
    page.compareFormButton().click();
    expect(page.getProductListElements().count()).toBe(2);
    // Reset.
    page.resetFormButton().click();
    expect(page.getProductListElements().count()).toBe(486);
  });

  it('should not possible submit empty form', () => {
    page.navigateTo();
    page.submitFormButton().click();
    expect(element(by.css('.mat-simple-snackbar')).getText()).toContain('You should set at least a filter');
  });

  afterEach(async () => {
    // Assert that there are no errors emitted from the browser
    const logs = await browser.manage().logs().get(logging.Type.BROWSER);
    expect(logs).not.toContain(jasmine.objectContaining({
      level: logging.Level.SEVERE,
    } as logging.Entry));
  });
});
